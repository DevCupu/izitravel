<?php

namespace App\Services;

use App\Models\Campaign;
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
     * Full routing pipeline: read UTM params, match campaign, pick an active CS by
     * weighted random, persist a chat log, and build the target wa.me URL.
     *
     * @return array{wa_url: ?string, cs: array{id: ?int, name: ?string, phone: ?string, fallback: bool}, campaign: ?Campaign, utm_source: ?string, utm_medium: ?string, utm_campaign: ?string}
     */
    public function route(Request $request): array
    {
        $utmSource = $this->clean($request->query('utm_source'));
        $utmMedium = $this->clean($request->query('utm_medium'));
        $utmCampaign = $this->clean($request->query('utm_campaign'));

        $campaign = null;
        if ($utmCampaign !== null) {
            $campaign = Campaign::query()
                ->where('is_active', true)
                ->where('utm_campaign', $utmCampaign)
                ->first();
        }

        $cs = $this->pickCs();

        $token = (string) Str::uuid();

        ChatLog::create([
            'campaign_id' => $campaign?->id,
            'cs_id' => $cs['id'],
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'token' => $token,
        ]);

        $messageName = $campaign?->name ?? $utmCampaign;

        return [
            'wa_url' => $this->buildWaUrl($cs['phone'], $this->buildMessage($messageName)),
            'token' => $token,
            'cs' => $cs,
            'campaign' => $campaign,
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
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

    private function buildMessage(?string $campaignName): string
    {
        $template = Setting::getValue(self::MESSAGE_TEMPLATE_SETTING) ?: self::DEFAULT_MESSAGE_TEMPLATE;

        return str_replace('{campaign}', $campaignName ?: 'umrah', $template);
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
