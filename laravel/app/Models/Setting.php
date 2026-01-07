<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'agency_name',
        'rera_id',
        'w_no',
        'office_address',
        'logo_url',
        'theme_color',
        'custom_color',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all settings as an object.
     * Creates default record if none exists.
     */
    public static function getSettings(): \stdClass
    {
        $settings = static::first();

        if (!$settings) {
            $settings = static::create([
                'agency_name' => 'Elite Homes Real Estate',
                'rera_id' => 'ORN-882910',
                'w_no' => '+91 50 123 4567',
                'office_address' => 'Office 402, Business Bay Tower, Dubai, UAE',
                'logo_url' => null,
                'theme_color' => 'blue',
                'custom_color' => '1E3A8A',
            ]);
        }

        return (object) $settings->toArray();
    }

    /**
     * Update settings with the given data.
     */
    public static function updateSettings(array $data): \stdClass
    {
        $settings = static::first() ?? static::create([
            'agency_name' => 'Elite Homes Real Estate',
            'rera_id' => 'ORN-882910',
            'w_no' => '+91 50 123 4567',
            'office_address' => 'Office 402, Business Bay Tower, Dubai, UAE',
            'logo_url' => null,
            'theme_color' => 'blue',
            'custom_color' => '1E3A8A',
        ]);
        
        $settings->update($data);
        $settings->refresh();
        
        return (object) $settings->toArray();
    }
}
