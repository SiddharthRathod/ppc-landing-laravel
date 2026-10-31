<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use Database\Seeders\PageSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public'); // seeders copy demo images; keep them out of the real storage folder
        $this->seed([SiteSettingSeeder::class, PageSeeder::class]);
    }

    public function test_home_page_renders_every_seeded_section(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([
                'Your Story.',                                  // hero
                '5,000+',                                       // hero stats
                'Trusted by Saudi clients',                     // clients
                'One production partner.',                      // services
                'Frames from the field',                        // portfolio
                'Built for the sectors shaping the Kingdom',    // industries
                'Five steps, one standard',                     // process
                'A Saudi production company with 1977 behind it', // why
                'Professional and reliable media partner.',     // testimonial
                'Get a quote within 24 hours',                  // contact_cta
            ]);
    }

    public function test_contact_page_renders_the_form(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Get a quote within 24 hours')
            ->assertSee('name="website"', escape: false); // honeypot field is in the form
    }

    public function test_sections_follow_the_order_set_in_the_admin(): void
    {
        $home = Page::where('slug', 'home')->first();
        $home->update(['content' => array_reverse($home->content)]);

        $this->get(route('home'))->assertSeeInOrder([
            'Get a quote within 24 hours',
            'Your Story.',
        ]);
    }

    public function test_edited_block_content_is_shown(): void
    {
        $home = Page::where('slug', 'home')->first();
        $content = $home->content;
        $content[0]['data']['heading'] = "New headline\nSecond line";
        $home->update(['content' => $content]);

        $this->get(route('home'))
            ->assertSee('New headline<br />'."\n".'Second line', escape: false);
    }

    public function test_heading_html_from_the_admin_is_escaped(): void
    {
        $home = Page::where('slug', 'home')->first();
        $content = $home->content;
        $content[0]['data']['heading'] = '<script>alert(1)</script>';
        $home->update(['content' => $content]);

        $this->get(route('home'))
            ->assertDontSee('<script>alert(1)</script>', escape: false)
            ->assertSee('&lt;script&gt;', escape: false);
    }

    public function test_unpublished_page_returns_404(): void
    {
        Page::where('slug', 'contact')->update(['is_published' => false]);

        $this->get(route('contact'))->assertNotFound();
    }

    public function test_page_seo_fields_are_in_the_head(): void
    {
        Page::where('slug', 'home')->update([
            'meta_title' => 'Custom home title',
            'meta_description' => 'Custom home description',
            'robots' => 'noindex,follow',
        ]);

        $this->get(route('home'))
            ->assertSee('<title>Custom home title</title>', escape: false)
            ->assertSee('content="Custom home description"', escape: false)
            ->assertSee('<meta name="robots" content="noindex,follow">', escape: false)
            ->assertSee('<link rel="canonical" href="'.route('home').'">', escape: false);
    }

    public function test_hero_video_is_rendered_when_uploaded(): void
    {
        $home = Page::where('slug', 'home')->first();
        $content = $home->content;
        $content[0]['data']['video'] = 'pages/videos/reel.mp4';
        $home->update(['content' => $content]);

        $this->get(route('home'))
            ->assertSee('<video autoplay muted loop playsinline', escape: false)
            ->assertSee('/storage/pages/videos/reel.mp4');
    }

    public function test_header_footer_and_whatsapp_use_the_site_settings(): void
    {
        SiteSetting::current()->update([
            'phone' => '+966 50 123 4567',
            'whatsapp_number' => '966501234567',
            'cr_number' => '1010999999',
            'cities' => ['Riyadh', 'Tabuk'],
        ]);

        $this->get(route('home'))
            ->assertSee('href="tel:+966501234567"', escape: false)
            ->assertSee('+966 50 123 4567')
            ->assertSee('https://wa.me/966501234567')
            ->assertSee('CR 1010999999')
            ->assertSee('Tabuk')
            ->assertSee('/storage/demo/logo.png');
    }

    public function test_whatsapp_button_is_hidden_without_a_number(): void
    {
        SiteSetting::current()->update(['whatsapp_number' => null]);

        $this->get(route('home'))->assertDontSee('wa.me/');
    }

    public function test_home_page_shows_the_three_latest_published_posts(): void
    {
        foreach (range(1, 4) as $daysAgo) {
            Post::factory()->create(['title' => "Guide {$daysAgo}", 'published_at' => now()->subDays($daysAgo)]);
        }
        Post::factory()->draft()->create(['title' => 'Draft guide']);

        $this->get(route('home'))
            ->assertSee('From the blog')
            ->assertSeeInOrder(['Guide 1', 'Guide 2', 'Guide 3'])
            ->assertDontSee('Guide 4')
            ->assertDontSee('Draft guide');
    }
}
