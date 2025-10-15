<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'author_id' => 1,
            'title' => $title,
            'slug' => str($title)->slug(),
            'excerpt' => $this->faker->sentence(20),
            'content' => collect(range(1, 4))->map(fn () => '<p>'.$this->faker->paragraph(5).'</p>')->implode("\n"),
            'status' => 'published',
            'published_at' => now()->subDays($this->faker->numberBetween(1, 60)),
            'featured_image' => 'assets/img/blog-' . $this->faker->numberBetween(1, 3) . '.jpg',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(20),
            'tags' => $this->faker->words(3),
        ];
    }
}
