<?php

namespace Tests\Feature\Admin;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_list_page_shows_posts(): void
    {
        $posts = Post::factory()->count(3)->create();

        $this->get('/admin/posts')->assertOk();

        Livewire::test(ListPosts::class)->assertCanSeeTableRecords($posts);
    }

    public function test_list_can_be_filtered_by_status(): void
    {
        $published = Post::factory()->create();
        $draft = Post::factory()->draft()->create();
        $scheduled = Post::factory()->create(['published_at' => now()->addWeek()]);

        Livewire::test(ListPosts::class)
            ->filterTable('status', PostStatus::Draft->value)
            ->assertCanSeeTableRecords([$draft])
            ->assertCanNotSeeTableRecords([$published, $scheduled]);
    }

    public function test_admin_can_create_a_post(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm([
                'title' => 'A corporate video guide for Saudi companies',
                'slug' => 'corporate-video-guide',
                'category' => 'Corporate video',
                'excerpt' => 'What to plan before the shoot.',
                'body' => '<p>Start with the audience.</p>',
                'meta_title' => 'Corporate video guide',
                'published_at' => now(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $post = Post::sole();
        $this->assertSame('corporate-video-guide', $post->slug);
        $this->assertSame('Corporate video guide', $post->meta_title);
        $this->assertSame(PostStatus::Published, $post->status());
    }

    public function test_slug_is_filled_from_the_title(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm(['title' => 'Construction Timelapse for Saudi Projects'])
            ->assertSchemaStateSet(['slug' => 'construction-timelapse-for-saudi-projects']);
    }

    public function test_title_and_slug_are_required_and_slug_must_be_unique(): void
    {
        Post::factory()->create(['slug' => 'taken']);

        Livewire::test(CreatePost::class)
            ->fillForm(['title' => '', 'slug' => ''])
            ->call('create')
            ->assertHasFormErrors(['title' => 'required', 'slug' => 'required']);

        Livewire::test(CreatePost::class)
            ->fillForm(['title' => 'Another', 'slug' => 'taken'])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    public function test_admin_can_edit_a_post_and_keep_its_slug(): void
    {
        $post = Post::factory()->create(['slug' => 'safety-videos']);

        Livewire::test(EditPost::class, ['record' => $post->id])
            ->fillForm(['excerpt' => 'Updated excerpt'])
            ->call('save')
            ->assertHasNoFormErrors(); // unique() ignores the post itself

        $this->assertSame('Updated excerpt', $post->refresh()->excerpt);
    }

    public function test_status_is_worked_out_from_the_publish_date(): void
    {
        $this->assertSame(PostStatus::Draft, Post::factory()->draft()->make()->status());
        $this->assertSame(PostStatus::Scheduled, Post::factory()->make(['published_at' => now()->addDay()])->status());
        $this->assertSame(PostStatus::Published, Post::factory()->make()->status());
    }
}
