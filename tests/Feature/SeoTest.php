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

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed([SiteSettingSeeder::class, PageSeeder::class]);
    }

    public function test_home_page_has_open_graph_and_twitter_tags(): void
    {
        Page::where('slug', 'home')->update(['og_image' => 'seo/share.jpg']);

        $this->get(route('home'))
            ->assertSee('<meta property="og:type" content="website">', escape: false)
            ->assertSee('<meta property="og:title" content="Professional Photography Company | Media Production in Saudi Arabia">', escape: false)
            ->assertSee('<meta property="og:url" content="'.route('home').'">', escape: false)
            ->assertSee('<meta property="og:image" content="'.Storage::disk('public')->url('seo/share.jpg').'">', escape: false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', escape: false);
    }

    public function test_hero_photo_is_the_sharing_image_when_none_is_set(): void
    {
        $this->get(route('home'))
            ->assertSee('<meta property="og:image" content="'.Storage::disk('public')->url('demo/hero.webp').'">', escape: false);
    }

    public function test_empty_description_is_left_out(): void
    {
        Page::where('slug', 'home')->update(['meta_description' => null]);

        $this->get(route('home'))->assertDontSee('name="description"', escape: false);
    }

    public function test_blog_post_is_an_article(): void
    {
        $post = Post::factory()->create();

        $this->get(route('blog.show', $post))
            ->assertSee('<meta property="og:type" content="article">', escape: false);
    }

    public function test_home_page_has_organization_json_ld_from_site_settings(): void
    {
        SiteSetting::current()->update([
            'social_links' => [['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/company/ppc']],
        ]);

        $html = $this->get(route('home'))->getContent();
        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $match);
        $schema = json_decode($match[1] ?? '', true);

        $this->assertSame('Organization', $schema['@type']);
        $this->assertSame('Professional Photography Company', $schema['name']);
        $this->assertSame('+966 55 111 6121', $schema['telephone']);
        $this->assertSame(['https://www.linkedin.com/company/ppc'], $schema['sameAs']);
    }

    public function test_organization_json_ld_is_only_on_the_home_page(): void
    {
        // Other pages have a BreadcrumbList (see BreadcrumbTest), but not the Organization.
        $this->get(route('contact'))->assertDontSee('"@type":"Organization"', escape: false);
    }

    public function test_json_ld_cannot_be_broken_out_of_by_admin_text(): void
    {
        // A value that tries to close the <script> tag and start a new one.
        SiteSetting::current()->update(['phone' => '</script><script>alert(1)</script>']);

        $this->get(route('home'))
            ->assertDontSee('</script><script>alert(1)', escape: false)
            ->assertSee('</script>', escape: false); // JSON_HEX_TAG encoded it
    }

    public function test_admin_canonical_url_overrides_the_default(): void
    {
        Page::where('slug', 'home')->update(['canonical_url' => 'https://professionalphotography.sa/']);

        $this->get(route('home'))
            ->assertSee('<link rel="canonical" href="https://professionalphotography.sa/">', escape: false)
            ->assertSee('<meta property="og:url" content="https://professionalphotography.sa/">', escape: false);
    }

    public function test_blog_post_shares_its_card_image_when_no_sharing_image_is_set(): void
    {
        $post = Post::factory()->create(['image' => 'posts/card.jpg', 'og_image' => null]);

        $this->get(route('blog.show', $post))
            ->assertSee('<meta property="og:image" content="'.Storage::disk('public')->url('posts/card.jpg').'">', escape: false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', escape: false);
    }

    public function test_sitemap_leaves_out_unpublished_and_deleted_content(): void
    {
        Page::where('slug', 'contact')->update(['is_published' => false]);
        $deleted = Post::factory()->create(['slug' => 'deleted-post']);
        $deleted->delete();

        $this->get('/sitemap.xml')
            ->assertDontSee('<loc>'.route('contact').'</loc>', escape: false)
            ->assertDontSee('deleted-post');
    }

    public function test_sitemap_has_no_blog_index_when_there_are_no_posts(): void
    {
        $this->get('/sitemap.xml')
            ->assertSee('<loc>'.route('home').'</loc>', escape: false)
            ->assertDontSee('<loc>'.route('blog.index').'</loc>', escape: false);
    }

    public function test_sitemap_lists_public_pages_and_published_posts_only(): void
    {
        $live = Post::factory()->create(['slug' => 'live-post']);
        Post::factory()->draft()->create(['slug' => 'draft-post']);
        Post::factory()->create(['slug' => 'future-post', 'published_at' => now()->addWeek()]);
        Page::where('slug', 'contact')->update(['robots' => 'noindex,follow']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<loc>'.route('home').'</loc>', escape: false)
            ->assertSee('<loc>'.route('blog.index').'</loc>', escape: false)
            ->assertSee('<loc>'.route('blog.show', $live).'</loc>', escape: false)
            ->assertDontSee('draft-post')
            ->assertDontSee('future-post')
            ->assertDontSee('<loc>'.route('contact').'</loc>', escape: false); // noindex

        // Valid XML that a crawler can read.
        $this->assertNotFalse(simplexml_load_string($response->getContent()));
    }

    public function test_robots_txt_blocks_everything_outside_production(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee("User-agent: *\nDisallow: /", escape: false)
            ->assertDontSee('Sitemap:');
    }

    public function test_robots_txt_in_production_points_to_the_sitemap(): void
    {
        $this->app['env'] = 'production';

        $this->get('/robots.txt')
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.route('sitemap'));
    }
}
