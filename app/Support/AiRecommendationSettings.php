<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiRecommendationSettings
{
    public static function current(): array
    {
        $values = Setting::allValues();
        $defaultModel = (string) config('services.openai.model', 'gpt-5.4-mini');
        $model = (string) ($values['ai_recommendation_model'] ?? $defaultModel);
        $panelKey = self::panelApiKey();
        $serverKey = trim((string) config('services.openai.key'));
        $key = $panelKey ?? ($serverKey !== '' ? $serverKey : null);
        $model = preg_match('/^[A-Za-z0-9._-]{1,80}$/', $model) ? $model : $defaultModel;
        $verifiedFingerprint = (string) ($values['ai_recommendation_verified_fingerprint'] ?? '');

        return [
            'enabled' => ($values['ai_recommendation_enabled'] ?? '1') === '1',
            'key_configured' => $key !== null,
            'key_verified' => $key !== null && $verifiedFingerprint !== ''
                && hash_equals(self::fingerprint($key, $model), $verifiedFingerprint),
            'key_source' => $panelKey !== null ? 'panel' : 'server',
            'model' => $model,
            'guidance' => mb_substr(trim((string) ($values['ai_recommendation_guidance'] ?? '')), 0, 1000),
            'candidate_limit' => self::number($values, 'ai_recommendation_candidate_limit', 12, 6, 30),
            'result_limit' => self::number($values, 'ai_recommendation_result_limit', 3, 1, 5),
            'daily_limit' => self::number($values, 'ai_recommendation_daily_limit', 100, 0, 10000),
            'timeout_seconds' => self::number($values, 'ai_recommendation_timeout_seconds', 10, 3, 30),
        ];
    }

    public static function apiKey(): ?string
    {
        $panelKey = self::panelApiKey();
        if ($panelKey !== null) {
            return $panelKey;
        }

        $serverKey = trim((string) config('services.openai.key'));

        return $serverKey !== '' ? $serverKey : null;
    }

    public static function verifyConnection(): bool
    {
        $key = self::apiKey();
        $model = self::current()['model'];
        $verified = false;

        if ($key !== null) {
            try {
                $response = Http::withToken($key)
                    ->acceptJson()
                    ->timeout(5)
                    ->get('https://api.openai.com/v1/models/'.rawurlencode($model));
                $verified = $response->successful() && $response->json('id') === $model;
            } catch (Throwable $exception) {
                Log::warning('OpenAI connection verification failed', ['error' => $exception->getMessage()]);
            }
        }

        Setting::setValue('ai_recommendation_verified_fingerprint', $verified
            ? self::fingerprint($key, $model)
            : '');

        return $verified;
    }

    public static function invalidateVerification(): void
    {
        Setting::setValue('ai_recommendation_verified_fingerprint', '');
    }

    public static function fingerprint(string $key, string $model): string
    {
        return hash_hmac('sha256', $key."\0".$model, (string) config('app.key'));
    }

    private static function panelApiKey(): ?string
    {
        $encrypted = Setting::getValue('ai_recommendation_api_key');
        if (is_string($encrypted) && $encrypted !== '') {
            try {
                $key = trim(Crypt::decryptString($encrypted));
                if ($key !== '') {
                    return $key;
                }
            } catch (Throwable) {
                // A damaged or old encrypted value must not break public searches.
            }
        }

        return null;
    }

    private static function number(array $values, string $key, int $default, int $minimum, int $maximum): int
    {
        $value = filter_var($values[$key] ?? null, FILTER_VALIDATE_INT);

        return $value === false || $value === null ? $default : max($minimum, min($maximum, $value));
    }
}
