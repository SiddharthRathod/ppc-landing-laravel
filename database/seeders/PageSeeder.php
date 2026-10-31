<?php

namespace Database\Seeders;

use App\Models\Page;
use Database\Seeders\Concerns\CopiesDemoImages;
use Illuminate\Database\Seeder;

/**
 * Home and Contact Us with the content of the reference design, as
 * Builder blocks ([type, data]), so the site looks complete on first run.
 * Everything here can be edited in the admin under Pages.
 */
class PageSeeder extends Seeder
{
    use CopiesDemoImages;

    public function run(): void
    {
        Page::updateOrCreate(['slug' => 'home'], [
            'title' => 'Home',
            'meta_title' => 'Professional Photography Company | Media Production in Saudi Arabia',
            'meta_description' => 'Film, CGI, animation, photography, drone, timelapse and audio production across Saudi Arabia. Saudi-licensed, bilingual, backed by Studio 52 Group since 1977.',
            'robots' => 'index,follow',
            'is_published' => true,
            'content' => [
                $this->block('hero', [
                    'eyebrow' => 'Full-service media production · Saudi Arabia',
                    'heading' => "Your Story.\nIn Professional Hands.",
                    'heading_ar' => 'قصتك… بأيدٍ محترفة.',
                    'text' => 'One production partner for film, CGI, 2D and 3D animation, photography, drone, timelapse and audio, for companies and government entities across the Kingdom. Saudi-licensed, bilingual, and backed by Studio 52 Group since 1977.',
                    'chips' => ['Film', 'Photography', 'Animation & CGI', 'Drone', 'Timelapse', 'Audio & IVR'],
                    'primary_label' => 'Get a quote in 24 hours',
                    'primary_url' => '/contact',
                    'secondary_label' => 'See all services',
                    'secondary_url' => '#services',
                    'image' => $this->demoImage('hero.webp'),
                    'image_alt' => 'Film crew shooting on location in Saudi Arabia',
                    'video' => null,
                    'showreel_url' => null,
                    'stats' => [
                        ['value' => '1977', 'label' => 'Producing since, Studio 52 Group'],
                        ['value' => '5,000+', 'label' => 'Projects delivered (group)'],
                        ['value' => '200+', 'label' => 'Production professionals (group)'],
                        ['value' => '4.8', 'label' => 'Rating from 5,000+ reviews (group)'],
                    ],
                ]),
                $this->block('clients', [
                    'heading' => 'Trusted by Saudi clients',
                    'logos' => [
                        ['image' => $this->demoImage('client-saedan.png'), 'name' => 'Saedan'],
                        ['image' => $this->demoImage('client-npcc.png'), 'name' => 'NPCC'],
                        ['image' => $this->demoImage('client-arabian-drilling.png'), 'name' => 'Arabian Drilling'],
                        ['image' => $this->demoImage('client-medica.png'), 'name' => 'Medica'],
                        ['image' => $this->demoImage('client-sew-eurodrive.png'), 'name' => 'SEW Eurodrive'],
                        ['image' => $this->demoImage('client-turkish-aerospace.png'), 'name' => 'Turkish Aerospace'],
                    ],
                ]),
                $this->block('services', [
                    'eyebrow' => 'What we produce',
                    'heading' => "Every format.\nOne production partner.",
                    'text' => 'Film, photography, animation, aerial, timelapse and audio, planned, produced and delivered in-house, from a single executive portrait to a multi-camera live relay.',
                    'cards' => [
                        $this->card('Corporate Video', 'Brand films, CEO messages and testimonials that build trust and deliver results.', 'service-corporate-video.webp'),
                        $this->card('Event Coverage & Live Streaming', 'Multi-camera coverage, live streams and same-day highlights.', 'service-events.webp'),
                        [
                            ...$this->card('Photography', 'People, events, plants and buildings: one team, one house standard.', null),
                            'badge' => '4 specialisms',
                            'sub_images' => [
                                ['image' => $this->demoImage('photo-corporate.webp'), 'label' => 'Corporate'],
                                ['image' => $this->demoImage('photo-event.webp'), 'label' => 'Event'],
                                ['image' => $this->demoImage('photo-industrial.webp'), 'label' => 'Industrial'],
                                ['image' => $this->demoImage('photo-architectural.webp'), 'label' => 'Architectural'],
                            ],
                        ],
                        $this->card('Chroma / Green Screen', 'Studio presenters, e-learning and virtual sets.', 'service-chroma.webp'),
                        $this->card('Safety & HSE Video', 'Filming, 2D and 3D animation that strengthens your safety culture.', 'service-hse.webp'),
                        $this->card('Construction Timelapse', 'Solar-powered 4K to 8K cameras with live remote monitoring.', 'photo-architectural.webp'),
                        $this->card('Drone Filming', 'GACA-licensed aerial filming, inspection and monitoring.', 'service-drone.webp'),
                    ],
                    'cta_text' => '11 services in total. Most clients combine film, photography and drone in a single shoot.',
                    'cta_primary_label' => 'View all services',
                    'cta_primary_url' => '#services',
                    'cta_secondary_label' => 'Plan a combined shoot',
                    'cta_secondary_url' => '/contact',
                ]),
                $this->block('portfolio', [
                    'eyebrow' => 'Portfolio',
                    'heading' => 'Frames from the field',
                    'text' => 'Portraits, plants, events and skylines: stills and film frames shot by our own crews across the Kingdom.',
                    'button_label' => 'View full portfolio',
                    'button_url' => '#portfolio',
                    'frames' => [
                        ['image' => $this->demoImage('portfolio-event.webp'), 'caption' => 'Event coverage'],
                        ['image' => $this->demoImage('photo-corporate.webp'), 'caption' => 'Corporate portraits'],
                        ['image' => $this->demoImage('photo-industrial.webp'), 'caption' => 'Industrial photography'],
                        ['image' => $this->demoImage('service-drone.webp'), 'caption' => 'Aerial & drone'],
                        ['image' => $this->demoImage('portfolio-timelapse.webp'), 'caption' => 'Construction timelapse'],
                        ['image' => $this->demoImage('portfolio-skyline.webp'), 'caption' => 'Architecture & skylines'],
                        ['image' => $this->demoImage('photo-event.webp'), 'caption' => 'Event photography'],
                    ],
                ]),
                $this->block('industries', [
                    'eyebrow' => 'Industries',
                    'heading' => 'Built for the sectors shaping the Kingdom',
                    'text' => 'Our crews hold site inductions, work to protocol at government events, and deliver in Arabic and English.',
                    'cards' => [
                        ['title' => 'Energy & petrochemical', 'text' => 'HSE films, industrial photography, plant timelapse.', 'icon' => 'energy'],
                        ['title' => 'Construction & real estate', 'text' => 'Timelapse, drone, launch films, architecture.', 'icon' => 'construction'],
                        ['title' => 'Government & giga-projects', 'text' => 'Awareness campaigns, forums, official ceremonies.', 'icon' => 'government'],
                        ['title' => 'Events & exhibitions', 'text' => 'Live streaming, highlights, event photography.', 'icon' => 'events'],
                    ],
                ]),
                $this->block('process', [
                    'eyebrow' => 'How we work',
                    'heading' => 'Five steps, one standard',
                    'steps' => [
                        ['title' => 'Brief', 'text' => 'Goals, audience, message, deadline.'],
                        ['title' => 'Plan', 'text' => 'Concept, bilingual script, shot list, permits.'],
                        ['title' => 'Produce', 'text' => 'Film, photo, drone or animation crews, all in-house.'],
                        ['title' => 'Edit', 'text' => 'Edit, colour, sound mix, graphics, two revision rounds.'],
                        ['title' => 'Deliver', 'text' => 'Every format you need, Arabic and English.'],
                    ],
                ]),
                $this->block('why', [
                    'image' => $this->demoImage('why-camera.webp'),
                    'image_alt' => 'Cinema camera on set',
                    'eyebrow' => 'Why PPC',
                    'heading' => 'A Saudi production company with 1977 behind it',
                    'points' => [
                        ['title' => 'Saudi-licensed', 'text' => 'Local contracts, VAT invoices, payment in SAR.'],
                        ['title' => 'Crews in the Kingdom', 'text' => 'Riyadh, Jeddah, Medinah and the Eastern Province.'],
                        ['title' => 'Arabic and English', 'text' => 'Scripts and voice written for Saudi audiences.'],
                        ['title' => 'One production partner', 'text' => 'Film, photo, animation, drone and audio under one roof.'],
                    ],
                    'closing' => 'A Studio 52 Group company. Experience. Creativity. Innovation.',
                ]),
                $this->block('testimonial', [
                    'quote' => 'Professional and reliable media partner.',
                    'author' => 'Daniel Kevin, Senior Electrical Engineer, SEW-Eurodrive. Studio 52 Group client',
                    'image' => $this->demoImage('testimonial-bg.webp'),
                    'primary_label' => 'Get a quote in 24 hours',
                    'primary_url' => '/contact',
                    'secondary_label' => 'Talk to a producer',
                    'secondary_url' => '/contact',
                ]),
                $this->block('blog', [
                    'eyebrow' => 'Guides',
                    'heading' => 'From the blog',
                    'link_label' => 'All guides',
                ]),
                $this->block('contact_cta', [
                    'image' => $this->demoImage('portfolio-skyline.webp'),
                    'image_alt' => 'Photographer on location in Riyadh',
                    'eyebrow' => 'Film · Photo · Animation · Drone · Audio',
                    'heading' => 'Get a quote within 24 hours',
                    'heading_ar' => 'احصل على عرض سعر خلال ٢٤ ساعة',
                    'primary_label' => 'Send your brief',
                    'primary_url' => '/contact',
                ]),
            ],
        ]);

        Page::updateOrCreate(['slug' => 'contact'], [
            'title' => 'Contact Us',
            'meta_title' => 'Contact Us | Professional Photography Company',
            'meta_description' => 'Tell us what you need filmed, photographed or animated. A producer replies with scope, timeline and price within one working day.',
            'robots' => 'index,follow',
            'is_published' => true,
            'content' => [
                $this->block('contact_form', [
                    'eyebrow' => 'Contact us',
                    'heading' => 'Get a quote within 24 hours',
                    'text' => 'Tell us what you need filmed, photographed or animated. A producer replies with scope, timeline and price within one working day.',
                    'heading_ar' => 'احصل على عرض سعر خلال ٢٤ ساعة',
                ]),
            ],
        ]);
    }

    /**
     * One Builder item, in the same shape Filament saves: ['type' => ..., 'data' => [...]].
     *
     * @param  array<string, mixed>  $data
     * @return array{type: string, data: array<string, mixed>}
     */
    private function block(string $type, array $data): array
    {
        return ['type' => $type, 'data' => $data];
    }

    /**
     * @return array<string, mixed>
     */
    private function card(string $title, string $text, ?string $image): array
    {
        return [
            'title' => $title,
            'text' => $text,
            'image' => $image ? $this->demoImage($image) : null,
            'url' => null,
            'badge' => null,
            'sub_images' => [],
        ];
    }
}
