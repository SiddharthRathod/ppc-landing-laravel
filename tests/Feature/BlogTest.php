<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_lists_only_published_posts(): void
    {
        Post::factory()->create(['title' => 'Live guide']);
        Post::factory()->draft()->create(['title' => 'Draft guide']);
        Post::factory()->create(['title' => 'Future guide', 'published_at' => now()->addWeek()]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Live guide')
            ->assertDontSee('Draft guide')
            ->assertDontSee('Future guide');
    }

    public function test_published_post_is_shown_by_its_slug(): void
    {
        $post = Post::factory()->create([
            'slug' => 'drone-guide',
            'title' => 'Drone filming guide',
            'body' => '<h2>Permits</h2><p>GACA approval first.</p>',
        ]);

        $this->get('/blog/drone-guide')
            ->assertOk()
            ->assertSee('Drone filming guide')
            ->assertSee('<h2>Permits</h2>', escape: false);
    }

    public function test_drafts_scheduled_and_deleted_posts_return_404(): void
    {
        $draft = Post::factory()->draft()->create();
        $scheduled = Post::factory()->create(['published_at' => now()->addDay()]);
        $deleted = Post::factory()->create();
        $deleted->delete();

        $this->get(route('blog.show', $draft))->assertNotFound();
        $this->get(route('blog.show', $scheduled))->assertNotFound();
        $this->get('/blog/'.$deleted->slug)->assertNotFound();
    }

    public function test_post_body_is_sanitized(): void
    {
        $post = Post::factory()->create(['body' => '<p>Safe</p><script>alert(1)</script>']);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('<p>Safe</p>', escape: false)
            ->assertDontSee('<script>alert(1)</script>', escape: false);
    }

    public function test_post_page_uses_the_admin_seo_fields(): void
    {
        $post = Post::factory()->create([
            'meta_title' => 'Custom SEO title',
            'meta_description' => 'Custom SEO description',
        ]);

        $this->get(route('blog.show', $post))
            ->assertSee('<title>Custom SEO title</title>', escape: false)
            ->assertSee('content="Custom SEO description"', escape: false)
            ->assertSee('<link rel="canonical" href="'.route('blog.show', $post).'">', escape: false);
    }

    public function test_seo_falls_back_to_title_and_excerpt(): void
    {
        $post = Post::factory()->create(['title' => 'Timelapse guide', 'excerpt' => 'Short summary']);

        $this->get(route('blog.show', $post))
            ->assertSee('<title>Timelapse guide | Professional Photography Company</title>', escape: false)
            ->assertSee('content="Short summary"', escape: false);
    }
}
