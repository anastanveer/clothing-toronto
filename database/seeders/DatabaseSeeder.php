<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@glamer.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Anas12345!@#$%'),
                'is_admin' => true,
                'role' => 'full_admin',
            ]
        );

        $productAdmin = User::updateOrCreate(
            ['email' => 'product-admin@glamer.local'],
            [
                'name' => 'Product Admin',
                'password' => Hash::make('Product123!'),
                'is_admin' => true,
                'role' => 'product_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@glamer.local'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('Customer123!'),
                'is_admin' => false,
                'role' => 'customer',
            ]
        );

        $brands = [
            'Khanabadosh',
            'Velvet Lane',
            'Aurora Loom',
            'Nomad Threadworks',
            'Urban Qalb',
            'Sahar Atelier',
            'Northlight Collective',
            'Elysian Fabricators',
            'Circa Atelier',
            'Amber & Ashe',
        ];

        $faker = fake();

        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Brand::truncate();
        WishlistItem::truncate();
        CartItem::truncate();
        ProductLike::truncate();
        OrderItem::truncate();
        Order::truncate();
        Schema::enableForeignKeyConstraints();

        $brandIds = collect();

        foreach ($brands as $name) {
            $brand = Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'tagline' => $faker->optional()->catchPhrase(),
                'summary' => $faker->sentence(12),
                'description' => $faker->paragraphs(3, true),
                'is_published' => true,
            ]);

            $brandIds->push($brand->id);
        }

        foreach ($brandIds as $brandId) {
            foreach (Product::CATEGORIES as $category) {
                Product::factory()
                    ->count(6)
                    ->create([
                        'brand_id' => $brandId,
                        'category' => $category,
                    ]);
            }
        }

        BlogPost::factory()
            ->count(8)
            ->create([
                'author_id' => $admin->id,
            ]);

    }
}
