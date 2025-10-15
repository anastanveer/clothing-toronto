<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        $category = $this->faker->randomElement(\App\Models\Product::CATEGORIES);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'sku' => strtoupper($this->faker->bothify('GLMR-####')),
            'category' => $category,
            'summary' => $this->faker->sentence(12),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 49, 299),
            'sale_price' => $this->faker->optional(0.6)->randomFloat(2, 39, 249),
            'stock' => $this->faker->numberBetween(5, 120),
            'is_featured' => $this->faker->boolean(30),
            'status' => 'published',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(18),
            'featured_image' => 'assets/img/product-img-' . $this->faker->numberBetween(1, 6) . '.jpg',
            'gallery_images' => [
                'assets/img/product-img-' . $this->faker->numberBetween(1, 6) . '.jpg',
                'assets/img/product-img-' . $this->faker->numberBetween(1, 6) . '.jpg',
            ],
            'options' => [
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Black', 'Cream', 'Olive'],
            ],
        ];
    }
}
