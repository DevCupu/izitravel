<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignAd;
use App\Models\ChatLog;
use App\Models\Setting;
use App\Models\WhatsAppCs;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatRouterService
{
    public const FALLBACK_PHONE_SETTING = 'contact_whatsapp';

    public const MESSAGE_TEMPLATE_SETTING = 'chat_router_message_template';

    public const DEFAULT_MESSAGE_TEMPLATE = "Assalamu'alaikum, saya tertarik dengan paket {campaign} dari iklan";

    /**
     * Name of the cookie that fingerprints a returning device/visitor so leads
     * are allocated by device rather than by (proxy) IP.
     */
    public const VISITOR_COOKIE = 'izi_chat_uid';

    /**
     * Window (in seconds) during which a repeated visit from the same visitor +
     * campaign reuses the existing chat log instead of creating a new one.
     */
    public const DEDUPE_WINDOW_SECONDS = 2;

    /**
     * Full routing pipeline: read UTM params, match campaign, pick an active CS by
     * weighted random, persist a chat log, and build the target wa.me URL.
     *
     * @return array{wa_url: ?string, wa_app_url: ?string, cs: array{id: ?int, name: ?string, phone: ?string, fallback: bool}, campaign: ?Campaign, ad: ?CampaignAd, utm_source: ?string, utm_medium: ?string, utm_campaign: ?string, utm_content: ?string, visitor_uid: string}
     */
    public function route(Request $request): array
    {
        $utmSource = $this->clean($request->query('utm_source'));
        $utmMedium = $this->clean($request->query('utm_medium'));
        $utmCampaign = $this->clean($request->query('utm_campaign'));
        $utmContent = $this->clean($request->query('utm_content'));

        $visitorUid = $this->resolveVisitorUid($request);

        $campaign = null;
        if ($utmCampaign !== null) {
            $campaign = Campaign::query()
                ->where('is_active', true)
                ->where('utm_campaign', $utmCampaign)
                ->first();
        }

        $ad = null;
        if ($campaign !== null && $utmContent !== null) {
            $ad = $campaign->ads()
                ->where('is_active', true)
                ->where('utm_content', $utmContent)
                ->first();
        }

        $cs = $this->pickCs();

        [$token, $cs] = $this->resolveOrPersistLog(
            $cs,
            $campaign,
            $ad,
            $request,
            $utmSource,
            $utmMedium,
            $utmCampaign,
            $utmContent,
            $visitorUid
        );

        $messageName = $campaign?->name ?? $utmCampaign;
        $messageTemplate = $ad?->wa_message_template ?? $campaign?->wa_message_template;
        $message = $this->buildMessage($messageName, $messageTemplate, $ad?->name);

        return [
            'wa_url' => $this->buildWaUrl($cs['phone'], $message),
            'wa_app_url' => $this->buildWaAppUrl($cs['phone'], $message),
            'token' => $token,
            'cs' => $cs,
            'campaign' => $campaign,
            'ad' => $ad,
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'utm_content' => $utmContent,
            'visitor_uid' => $visitorUid,
        ];
    }

    /**
     * Resolve (or mint) the persistent visitor fingerprint cookie value used to
     * dedupe repeated accesses of the same device on the same campaign.
     */
    private function resolveVisitorUid(Request $request): string
    {
        $uid = $this->clean($request->cookie(self::VISITOR_COOKIE));

        return $uid ?? (string) Str::uuid();
    }

    /**
     * Resolve the chat log to use for this request. When the same visitor (cookie
     * fingerprint) and UTM campaign already produced a log within the dedupe
     * window (e.g. a user re-visiting an ad / a reload loop), the existing log +
     * token + CS are reused so repeated accesses don't inflate lead counts with
     * duplicates. Keying on the visitor cookie (not IP) keeps weighted-rotation
     * fair even when many visitors share a proxy IP on shared hosting.
     *
     * @return array{0: string, 1: array{id: ?int, name: ?string, phone: ?string, fallback: bool}}
     */
    private function resolveOrPersistLog(
        array $cs,
        ?Campaign $campaign,
        ?CampaignAd $ad,
        Request $request,
        ?string $utmSource,
        ?string $utmMedium,
        ?string $utmCampaign,
        ?string $utmContent,
        string $visitorUid
    ): array {
        $recent = ChatLog::query()
            ->where('visitor_uid', $visitorUid)
            ->where('utm_campaign', $utmCampaign)
            ->where('utm_content', $utmContent)
            ->where('created_at', '>=', now()->subSeconds(self::DEDUPE_WINDOW_SECONDS))
            ->latest('id')
            ->first();

        if ($recent !== null) {
            return [$recent->token, $this->csFromLog($recent)];
        }

        $token = (string) Str::uuid();

        ChatLog::create([
            'campaign_id' => $campaign?->id,
            'campaign_ad_id' => $ad?->id,
            'cs_id' => $cs['id'],
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'utm_content' => $utmContent,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'token' => $token,
            'visitor_uid' => $visitorUid,
        ]);

        return [$token, $cs];
    }

    /**
     * Rebuild the CS array from an existing log so a repeated access routes to
     * the same CS (and fallback flag) instead of re-rolling the weighted pick.
     *
     * @return array{id: ?int, name: ?string, phone: ?string, fallback: bool}
     */
    private function csFromLog(ChatLog $log): array
    {
        if ($log->cs_id === null) {
            $phone = $this->clean(Setting::getValue(self::FALLBACK_PHONE_SETTING));

            return [
                'id' => null,
                'name' => $phone ? 'CS Utama' : null,
                'phone' => $phone,
                'fallback' => true,
            ];
        }

        $cs = WhatsAppCs::find($log->cs_id);

        if ($cs === null || ! $cs->is_active) {
            return $this->pickCs();
        }

        return [
            'id' => $cs->id,
            'name' => $cs->name,
            'phone' => $this->normalizePhone($cs->phone),
            'fallback' => false,
        ];
    }

    /**
     * Mark a chat log as "clicked through" (user actually tapped the WhatsApp
     * button / auto-redirect fired). Looks the log up by its unique token so the
     * beacon cannot guess arbitrary ids.
     *
     * @return bool true when a log was found and updated
     */
    public function markClicked(?string $token): bool
    {
        if ($token === null || $token === '') {
            return false;
        }

        $updated = ChatLog::query()
            ->where('token', $token)
            ->whereNull('clicked_at')
            ->update(['clicked_at' => now()]);

        return $updated > 0;
    }

    /**
     * Pick the CS that should receive the next lead. Only active CS are eligible.
     * Returns a fallback (main WhatsApp number) when no CS is active.
     *
     * @return array{id: ?int, name: ?string, phone: ?string, fallback: bool}
     */
    public function pickCs(): array
    {
        $csList = WhatsAppCs::query()->where('is_active', true)->get();

        if ($csList->isEmpty()) {
            $phone = $this->clean(Setting::getValue(self::FALLBACK_PHONE_SETTING));

            return [
                'id' => null,
                'name' => $phone ? 'CS Utama' : null,
                'phone' => $phone,
                'fallback' => true,
            ];
        }

        return $this->selectWeighted($csList);
    }

    /**
     * Weighted-random selection over a given set of CS. Exposed so unit tests can
     * drive selection deterministically with an explicit $roll.
     *
     * @param  Collection<int, WhatsAppCs>  $csList
     * @return array{id: ?int, name: ?string, phone: ?string, fallback: bool}
     */
    public function selectWeighted(Collection $csList, ?int $roll = null): array
    {
        $picked = $this->pickWeighted($csList, $roll);

        return [
            'id' => $picked->id,
            'name' => $picked->name,
            'phone' => $this->normalizePhone($picked->phone),
            'fallback' => false,
        ];
    }

    private function pickWeighted(Collection $csList, ?int $roll): WhatsAppCs
    {
        $weights = $csList->map(fn (WhatsAppCs $cs) => max(0, (int) $cs->weight));
        $total = $weights->sum();

        if ($total <= 0) {
            return $csList->random();
        }

        $roll ??= random_int(1, $total);
        $roll = max(1, min($total, $roll));

        $cumulative = 0;
        foreach ($csList as $key => $cs) {
            $cumulative += $weights[$key];
            if ($roll <= $cumulative) {
                return $cs;
            }
        }

        return $csList->last();
    }

    private function buildMessage(?string $campaignName, ?string $messageTemplate = null, ?string $adName = null): string
    {
        $template = trim((string) $messageTemplate);
        if ($template === '') {
            $template = Setting::getValue(self::MESSAGE_TEMPLATE_SETTING) ?: self::DEFAULT_MESSAGE_TEMPLATE;
        }

        return str_replace(
            ['{campaign}', '{ad}'],
            [$campaignName ?: 'umrah', $adName ?: 'iklan'],
            $template
        );
    }

    private function buildWaUrl(?string $phone, ?string $message): ?string
    {
        $phone = $this->normalizePhone($phone ?? '');

        if ($phone === '') {
            return null;
        }

        $url = "https://wa.me/{$phone}";

        if ($message !== null && $message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    private function buildWaAppUrl(?string $phone, ?string $message): ?string
    {
        $phone = $this->normalizePhone($phone ?? '');

        if ($phone === '') {
            return null;
        }

        $url = "whatsapp://send?phone={$phone}";

        if ($message !== null && $message !== '') {
            $url .= '&text='.rawurlencode($message);
        }

        return $url;
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        return $phone;
    }

    private function clean(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return trim((string) $value) ?: null;
    }
}
