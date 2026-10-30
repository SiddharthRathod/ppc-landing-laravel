<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_content_is_stored_as_json_and_read_back_as_an_array(): void
    {
        $blocks = [['type' => 'hero', 'data' => ['title' => 'Your Story.']]];

        $page = Page::factory()->create(['content' => $blocks]);

        $this->assertSame($blocks, $page->fresh()->content);
    }

    public function test_page_published_scope_hides_unpublished_pages(): void
    {
        Page::factory()->create(['slug' => 'home']);
        Page::factory()->create(['slug' => 'hidden', 'is_published' => false]);

        $this->assertSame(['home'], Page::published()->pluck('slug')->all());
    }

    public function test_post_published_scope_hides_drafts_and_scheduled_posts(): void
    {
        $live = Post::factory()->create();
        Post::factory()->draft()->create();
        Post::factory()->create(['published_at' => now()->addWeek()]);

        $this->assertSame([$live->id], Post::published()->pluck('id')->all());
    }

    public function test_pages_posts_and_submissions_are_soft_deleted_and_can_be_restored(): void
    {
        foreach ([Page::factory()->create(), Post::factory()->create(), ContactSubmission::factory()->create()] as $model) {
            $model->delete();

            $this->assertSoftDeleted($model);
            $this->assertNull($model::find($model->id)); // hidden from normal queries

            $model->restore();

            $this->assertNotSoftDeleted($model);
        }
    }

    public function test_site_settings_always_use_a_single_row(): void
    {
        $first = SiteSetting::current();
        $second = SiteSetting::current();

        $this->assertTrue($first->is($second));
        $this->assertSame(1, SiteSetting::count());
    }

    public function test_site_setting_seeder_fills_the_design_values(): void
    {
        $this->seed(SiteSettingSeeder::class);
        $this->seed(SiteSettingSeeder::class); // safe to run again

        $settings = SiteSetting::sole();
        $this->assertSame('966551116121', $settings->whatsapp_number);
        $this->assertSame(['Riyadh', 'Jeddah', 'Medinah', 'Al Khobar'], $settings->cities);
        $this->assertSame('Company', $settings->footer_links[1]['heading']);
    }
}
