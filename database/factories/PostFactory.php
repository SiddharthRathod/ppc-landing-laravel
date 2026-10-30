<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => 'Corporate video',
            'excerpt' => fake()->sentence(15),
            'body' => '<p>'.fake()->paragraph().'</p>',
            'published_at' => now()->subDay(),
        ];
    }

    /**
     * A post without a publish date.
     */
    public function draft(): static
    {
        return $this->state(['published_at' => null]);
    }
}
