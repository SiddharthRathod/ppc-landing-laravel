<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the demo admin. updateOrCreate: running the seeder again
     * resets the password instead of failing on the unique email.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => config('app.admin.email')],
            [
                'name' => 'Admin',
                'password' => config('app.admin.password'), // hashed by the User model's 'hashed' cast
                'email_verified_at' => now(),
            ],
        );
    }
}
