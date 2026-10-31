<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Uploaded logo, or the logo shipped with the design if none is set.
     */
    public function logoUrl(): string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : asset('images/logo.png');
    }

    public function footerLogoUrl(): string
    {
        return $this->footer_logo ? Storage::disk('public')->url($this->footer_logo) : asset('images/logo-footer.png');
    }

    /**
     * "tel:" link: keep only digits and the leading +.
     */
    public function phoneHref(): string
    {
        return 'tel:'.preg_replace('/[^\d+]/', '', (string) $this->phone);
    }

    public function whatsappUrl(): ?string
    {
        return $this->whatsapp_number ? 'https://wa.me/'.$this->whatsapp_number : null;
    }

    /**
     * schema.org Organization for the home page (JSON-LD), so Google can show
     * the company name, logo and contact details. Empty values are left out.
     *
     * @return array<string, mixed>
     */
    public function organizationSchema(): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Professional Photography Company',
            'url' => route('home'),
            'logo' => $this->logoUrl(),
            'email' => $this->email,
            'telephone' => $this->phone,
            'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'SA'],
            'areaServed' => 'SA',
            'parentOrganization' => ['@type' => 'Organization', 'name' => 'Studio 52 Group'],
            'sameAs' => array_column($this->social_links ?? [], 'url'),
        ]);
    }
}
