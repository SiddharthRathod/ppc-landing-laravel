<?php

namespace Tests\Feature\Admin;

use App\Filament\Pages\SiteSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        $this->seed(SiteSettingSeeder::class);
    }

    public function test_page_loads_with_the_saved_settings(): void
    {
        $this->get('/admin/site-settings')->assertOk();

        Livewire::test(SiteSettings::class)
            ->assertSchemaStateSet([
                'phone' => '+966 55 111 6121',
                'whatsapp_number' => '966551116121',
                'cities' => ['Riyadh', 'Jeddah', 'Medinah', 'Al Khobar'],
            ]);
    }

    public function test_admin_can_save_settings(): void
    {
        Livewire::test(SiteSettings::class)
            ->fillForm([
                'phone' => '+966 50 000 0000',
                'cr_number' => '1010123456',
                'cities' => ['Riyadh', 'Dammam'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $settings = SiteSetting::sole();
        $this->assertSame('+966 50 000 0000', $settings->phone);
        $this->assertSame('1010123456', $settings->cr_number);
        $this->assertSame(['Riyadh', 'Dammam'], $settings->cities);
        $this->assertSame('Company', $settings->footer_links[1]['heading']); // untouched fields are kept
    }

    public function test_whatsapp_number_must_be_digits_only(): void
    {
        Livewire::test(SiteSettings::class)
            ->fillForm(['whatsapp_number' => '+966 55 111'])
            ->call('save')
            ->assertHasFormErrors(['whatsapp_number' => 'regex']);
    }

    public function test_guest_cannot_open_the_page(): void
    {
        auth()->logout();

        $this->get('/admin/site-settings')->assertRedirect('/admin/login');
    }
}
