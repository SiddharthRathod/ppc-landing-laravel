<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo',
        'footer_logo',
        'phone',
        'email',
        'whatsapp_number',
        'footer_tagline',
        'footer_tagline_ar',
        'cr_number',
        'vat_number',
        'footer_links',
        'cities',
        'social_links',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'footer_links' => 'array',
            'cities' => 'array',
            'social_links' => 'array',
        ];
    }

    /**
     * The single settings row, created empty if it doesn't exist yet.
     */
    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
