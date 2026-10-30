<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Database\Seeders\Concerns\CopiesDemoImages;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    use CopiesDemoImages;

    /**
     * Fill the single settings row with the values from the design.
     */
    public function run(): void
    {
        SiteSetting::current()->update([
            'logo' => $this->demoImage('logo.png'),
            'footer_logo' => $this->demoImage('logo-footer.png'),
            'phone' => '+966 55 111 6121',
            'email' => 'moiz@professionalphotography.sa',
            'whatsapp_number' => '966551116121',
            'footer_tagline' => 'MEDIA PRODUCTION · SAUDI ARABIA',
            'footer_tagline_ar' => 'شركة التصوير الاحترافي · إحدى شركات مجموعة ستوديو ٥٢',
            'footer_links' => [
                [
                    'heading' => 'Services',
                    'links' => [
                        ['label' => 'Video production', 'url' => '#'],
                        ['label' => 'Photography', 'url' => '#'],
                        ['label' => 'Safety & HSE', 'url' => '#'],
                        ['label' => 'Timelapse & drone', 'url' => '#'],
                        ['label' => 'Animation & CGI', 'url' => '#'],
                        ['label' => 'Audio & IVR', 'url' => '#'],
                    ],
                ],
                [
                    'heading' => 'Company',
                    'links' => [
                        ['label' => 'Work', 'url' => '/#portfolio'],
                        ['label' => 'Industries', 'url' => '/#industries'],
                        ['label' => 'About', 'url' => '#'],
                        ['label' => 'Blog', 'url' => '/blog'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ],
                ],
            ],
            'cities' => ['Riyadh', 'Jeddah', 'Medinah', 'Al Khobar'],
            'social_links' => [],
        ]);
    }
}
