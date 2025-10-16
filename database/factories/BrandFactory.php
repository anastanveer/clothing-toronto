<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'tagline' => $this->faker->optional()->catchPhrase(),
            'summary' => $this->faker->sentence(12),
            'description' => $this->faker->paragraphs(3, true),
            'hero_image' => null,
            'is_published' => true,
            'meta' => null,
        ];
    }
}
