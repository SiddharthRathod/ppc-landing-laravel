<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_seeder_creates_the_demo_admin(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->seed(AdminUserSeeder::class); // running it twice must not fail or duplicate

        $admin = User::sole();
        $this->assertSame('admin@ppc.test', $admin->email);
        $this->assertTrue(Hash::check('123456789', $admin->password));
    }

    public function test_admin_can_open_the_panel_outside_local(): void
    {
        // Tests run with APP_ENV=testing, so this checks User::canAccessPanel().
        $this->seed(AdminUserSeeder::class);

        $this->actingAs(User::sole())->get('/admin')->assertOk();
    }

    public function test_login_email_is_prefilled_when_local(): void
    {
        $this->app['env'] = 'local';
        Filament::setCurrentPanel('admin');

        Livewire::test(Login::class)->assertSet('data.email', 'admin@ppc.test');
    }

    public function test_login_email_is_empty_outside_local(): void
    {
        Filament::setCurrentPanel('admin');

        Livewire::test(Login::class)->assertSet('data.email', null);
    }
}
