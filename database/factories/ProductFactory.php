<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

        $category = $this->faker->randomElement(Product::CATEGORIES);
        $brandId = Brand::inRandomOrder()->value('id') ?? Brand::factory()->create()->id;
        $palette = ['Black', 'White', 'Olive', 'Stone', 'Sand', 'Navy', 'Blush', 'Burgundy', 'Emerald', 'Mustard'];
        $colorChoices = collect($this->faker->randomElements($palette, $this->faker->numberBetween(1, 3)))->unique()->values();
        $primaryColor = $colorChoices->first();
        $averageRating = $this->faker->randomFloat(2, 3.5, 5);
        $salePrice = $this->faker->optional(0.6)->randomFloat(2, 39, 249);
        $basePrice = $this->faker->randomFloat(2, max(49, $salePrice ? $salePrice + 5 : 49), 299);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'sku' => 'GLMR-' . strtoupper(Str::random(6)),
            'category' => $category,
            'brand_id' => $brandId,
            'summary' => $this->faker->sentence(12),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $basePrice,
            'sale_price' => $salePrice && $salePrice < $basePrice ? $salePrice : null,
            'average_rating' => round($averageRating, 2),
            'reviews_count' => $this->faker->numberBetween(3, 240),
            'primary_color' => $primaryColor,
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
                'colors' => $colorChoices->all(),
            ],
        ];
    }
}
