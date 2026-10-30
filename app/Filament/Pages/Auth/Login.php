<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

/**
 * Filament's login page, with the demo admin email pre-filled locally.
 */
class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (app()->isLocal()) {
            $this->form->fill(['email' => config('app.admin.email')]);
        }
    }
}
