<?php

namespace Database\Factories;

use App\Enums\CrmStatus;
use App\Models\ContactSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactSubmission>
 */
class ContactSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company' => fake()->company(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'service' => 'Corporate video',
            'message' => fake()->paragraph(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'crm_status' => CrmStatus::Pending,
        ];
    }
}
