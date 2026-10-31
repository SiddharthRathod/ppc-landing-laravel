<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\PageSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BreadcrumbTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed([SiteSettingSeeder::class, PageSeeder::class]);
    }

    /**
     * The BreadcrumbList JSON-LD of a response, decoded (null if there is none).
     *
     * @return array<string, mixed>|null
     */
    private function breadcrumbSchema(TestResponse $response): ?array
    {
        preg_match_all('#<script type="application/ld\+json">(.+?)</script>#s', $response->getContent(), $matches);

        return collect($matches[1])
            ->map(fn (string $json) => json_decode($json, true))
            ->firstWhere('@type', 'BreadcrumbList');
    }

    public function test_home_page_has_no_breadcrumbs(): void
    {
        $response = $this->get(route('home'));

        $response->assertDontSee('aria-label="Breadcrumb"', escape: false);
        $this->assertNull($this->breadcrumbSchema($response));
    }

    public function test_contact_page_trail(): void
    {
        $this->get(route('contact'))
            ->assertSee('aria-label="Breadcrumb"', escape: false)
            ->assertSeeInOrder(['>Home</a>', 'aria-current="page"', 'Contact Us'], escape: false);
    }

    public function test_blog_post_trail_is_home_blog_post(): void
    {
        $post = Post::factory()->create(['title' => 'Drone filming guide']);

        $response = $this->get(route('blog.show', $post));

        $response->assertSeeInOrder([
            '<a href="'.route('home').'"',
            '<a href="'.route('blog.index').'"',
            '<span aria-current="page"',
            'Drone filming guide',
        ], escape: false);
    }

    public function test_current_page_is_not_a_link(): void
    {
        $post = Post::factory()->create();

        $this->get(route('blog.show', $post))
            ->assertDontSee('<a href="'.route('blog.show', $post).'"', escape: false);
    }

    public function test_json_ld_breadcrumb_list_matches_the_trail(): void
    {
        $post = Post::factory()->create(['title' => 'Safety induction videos']);

        $schema = $this->breadcrumbSchema($this->get(route('blog.show', $post)));

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame([1, 2, 3], array_column($schema['itemListElement'], 'position'));
        $this->assertSame(['Home', 'Blog', 'Safety induction videos'], array_column($schema['itemListElement'], 'name'));
        $this->assertSame(
            [route('home'), route('blog.index'), route('blog.show', $post)],
            array_column($schema['itemListElement'], 'item'),
        );
    }

    public function test_last_breadcrumb_url_is_the_canonical_url(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('blog.show', $post));
        $items = $this->breadcrumbSchema($response)['itemListElement'];

        $response->assertSee('<link rel="canonical" href="'.end($items)['item'].'">', escape: false);
    }

    public function test_urls_are_absolute_and_never_repeated(): void
    {
        $post = Post::factory()->create();

        $urls = array_column($this->breadcrumbSchema($this->get(route('blog.show', $post)))['itemListElement'], 'item');

        $this->assertSame($urls, array_values(array_unique($urls)));
        foreach ($urls as $url) {
            $this->assertStringStartsWith('http', $url);
        }
    }

    public function test_not_found_pages_have_no_breadcrumbs(): void
    {
        $this->get('/blog/does-not-exist')
            ->assertNotFound()
            ->assertDontSee('aria-label="Breadcrumb"', escape: false)
            ->assertDontSee('BreadcrumbList');

        $this->get('/no-such-page')->assertNotFound()->assertDontSee('BreadcrumbList');
    }

    public function test_breadcrumb_text_is_escaped(): void
    {
        $post = Post::factory()->create(['title' => '<script>alert(1)</script>']);

        $this->get(route('blog.show', $post))
            ->assertDontSee('<script>alert(1)</script>', escape: false);
    }
}
