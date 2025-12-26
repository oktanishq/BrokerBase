<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all settings as a key-value array.
     */
    public static function getSettings(): \stdClass
    {
        $settings = static::all()->pluck('value', 'key');
        
        // Set defaults if not found
        $defaults = [
            'agency_name' => 'Elite Homes Real Estate',
            'rera_id' => 'ORN-882910',
            'w_no' => '+91 50 123 4567',
            'office_address' => 'Office 402, Business Bay Tower, Dubai, UAE',
            'logo_url' => null,
            'theme_color' => 'blue',
            'custom_color' => null,
        ];

        // Merge defaults with database values, ensuring database values take precedence
        $settings = collect($defaults)->merge($settings);
        
        return (object) $settings->toArray();
    }

    /**
     * Update settings with validation and error handling.
     */
    public static function updateSettings(array $data): \stdClass
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return static::getSettings();
    }

    /**
     * Get a specific setting value.
     */
    public static function getSetting(string $key): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }

    /**
     * Update a specific setting.
     */
    public static function setSetting(string $key, string $value): bool
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        return true;
    }
}
