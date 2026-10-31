<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PageResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(PageSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    public function test_list_shows_home_and_contact_without_a_create_button(): void
    {
        $this->get('/admin/pages')->assertOk();
        $this->get('/admin/pages/create')->assertNotFound();

        Livewire::test(ListPages::class)
            ->assertCanSeeTableRecords(Page::all())
            ->assertActionDoesNotExist('create');
    }

    public function test_edit_page_loads_with_all_sections(): void
    {
        $home = Page::where('slug', 'home')->first();

        $this->get("/admin/pages/{$home->id}/edit")->assertOk();
    }

    public function test_admin_can_edit_seo_fields(): void
    {
        $home = Page::where('slug', 'home')->first();

        Livewire::test(EditPage::class, ['record' => $home->id])
            ->fillForm([
                'meta_title' => 'New SEO title',
                'meta_description' => 'New SEO description',
                'canonical_url' => 'https://example.sa/',
                'robots' => 'noindex,follow',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $home->refresh();
        $this->assertSame('New SEO title', $home->meta_title);
        $this->assertSame('https://example.sa/', $home->canonical_url);
        $this->assertSame('noindex,follow', $home->robots);
    }

    public function test_saving_keeps_the_sections_and_their_order(): void
    {
        $home = Page::where('slug', 'home')->first();
        $typesBefore = array_column($home->content, 'type');

        Livewire::test(EditPage::class, ['record' => $home->id])
            ->call('save')
            ->assertHasNoFormErrors();

        $home->refresh();
        $this->assertSame($typesBefore, array_column($home->content, 'type'));
        $this->assertSame('Trusted by Saudi clients', $home->block('clients')['heading']);
    }

    public function test_slug_cannot_be_changed(): void
    {
        $home = Page::where('slug', 'home')->first();

        Livewire::test(EditPage::class, ['record' => $home->id])
            ->fillForm(['slug' => 'something-else'])
            ->call('save');

        $this->assertSame('home', $home->refresh()->slug);
    }
}
