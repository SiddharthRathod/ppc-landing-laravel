<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\SiteSetting;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Your Story.');
    }

    public function test_contact_page_renders(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Get a quote within 24 hours');
    }

    public function test_header_footer_and_whatsapp_use_the_site_settings(): void
    {
        $this->seed(SiteSettingSeeder::class);
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
            ->assertSee('/storage/demo/logo.png'); // seeded logo, copied to the public disk
    }

    public function test_whatsapp_button_is_hidden_without_a_number(): void
    {
        $this->get(route('home'))->assertDontSee('wa.me/');
    }

    public function test_home_page_shows_the_three_latest_published_posts(): void
    {
        foreach (range(1, 4) as $daysAgo) {
            Post::factory()->create(['title' => "Guide {$daysAgo}", 'published_at' => now()->subDays($daysAgo)]);
        }
        Post::factory()->draft()->create(['title' => 'Draft guide']);

        $this->get(route('home'))
            ->assertSeeInOrder(['Guide 1', 'Guide 2', 'Guide 3'])
            ->assertDontSee('Guide 4')
            ->assertDontSee('Draft guide');
    }
}
