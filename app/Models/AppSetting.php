<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
        'is_secret',
    ];

    protected function casts(): array
    {
        return [
            'is_secret' => 'boolean',
        ];
    }

    public const CACHE_PREFIX = 'aeee_app_setting_';
    public const FAST2SMS_CACHE_KEY = 'aeee_fast2sms_config';

    /**
     * Retrieve setting value with sub-millisecond RAM caching.
     */
    public static function get(string $key, $default = null): ?string
    {
        return Cache::rememberForever(self::CACHE_PREFIX . $key, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value and flush cache immediately.
     */
    public static function set(string $key, ?string $value, string $group = 'general', ?string $description = null, bool $isSecret = false): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'description' => $description,
                'is_secret' => $isSecret,
            ]
        );

        Cache::forget(self::CACHE_PREFIX . $key);
        Cache::forget(self::FAST2SMS_CACHE_KEY);

        return $setting;
    }

    /**
     * Retrieve aggregated Fast2SMS settings (DB priority with .env fallback).
     */
    public static function getFast2SmsConfig(): array
    {
        return Cache::rememberForever(self::FAST2SMS_CACHE_KEY, function () {
            $settings = static::where('group', 'fast2sms')->pluck('value', 'key');
            $envConfig = config('services.fast2sms', []);

            return [
                'api_key' => $settings['fast2sms_api_key'] ?? $envConfig['api_key'] ?? '',
                'endpoint' => $settings['fast2sms_endpoint'] ?? $envConfig['endpoint'] ?? 'https://www.fast2sms.com/dev/bulkV2',
                'sender_id' => $settings['fast2sms_sender_id'] ?? $envConfig['sender_id'] ?? 'FSTSMS',
                'route' => $settings['fast2sms_route'] ?? $envConfig['route'] ?? 'otp',
                'entity_id' => $settings['fast2sms_entity_id'] ?? $envConfig['entity_id'] ?? null,
                'template_id' => $settings['fast2sms_template_id'] ?? $envConfig['template_id'] ?? null,
                'variable_2' => $settings['fast2sms_variable_2'] ?? 'Crack AEEE',
                'mock' => filter_var($settings['fast2sms_mock_mode'] ?? $envConfig['mock'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'default_test_otp' => $settings['fast2sms_default_test_otp'] ?? $envConfig['default_test_otp'] ?? '123456',
                'otp_expiry_minutes' => !empty($settings['fast2sms_otp_expiry_minutes']) && (int) $settings['fast2sms_otp_expiry_minutes'] > 0
                    ? (int) $settings['fast2sms_otp_expiry_minutes'] 
                    : (!empty($envConfig['otp_expiry_minutes']) && (int) $envConfig['otp_expiry_minutes'] > 0 ? (int) $envConfig['otp_expiry_minutes'] : 15),
                'max_attempts' => !empty($settings['fast2sms_max_attempts']) && (int) $settings['fast2sms_max_attempts'] > 0 
                    ? (int) $settings['fast2sms_max_attempts'] 
                    : (!empty($envConfig['max_verification_attempts']) ? (int) $envConfig['max_verification_attempts'] : 5),
            ];
        });
    }

    protected static function booted(): void
    {
        static::saved(function ($model) {
            Cache::forget(self::CACHE_PREFIX . $model->key);
            Cache::forget(self::FAST2SMS_CACHE_KEY);
        });

        static::deleted(function ($model) {
            Cache::forget(self::CACHE_PREFIX . $model->key);
            Cache::forget(self::FAST2SMS_CACHE_KEY);
        });
    }
}
