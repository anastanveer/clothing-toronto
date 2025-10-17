<?php

namespace Database\Seeders;

use App\Models\AnnouncementCard;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Coupon;
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
        Coupon::truncate();
        AnnouncementCard::truncate();
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

        Coupon::insert([
            [
                'code' => 'GLAMER25',
                'title' => 'Glamer Collective 25%',
                'description' => 'Limited run perk for the Glamer Collective insiders.',
                'type' => 'percent',
                'value' => 25,
                'max_discount' => 75,
                'min_spend' => 150,
                'is_active' => true,
                'starts_at' => now()->subWeek(),
                'expires_at' => now()->addMonth(),
                'requires_assignment' => false,
                'max_assignments' => null,
                'priority' => 5,
                'audience_tag' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LEGEND50',
                'title' => 'Legend Circle $50 Bonus',
                'description' => 'Rewarding our repeat shoppers with a $50 wardrobe bonus.',
                'type' => 'fixed',
                'value' => 50,
                'max_discount' => null,
                'min_spend' => 250,
                'is_active' => true,
                'starts_at' => now()->subDays(3),
                'expires_at' => now()->addDays(45),
                'requires_assignment' => false,
                'max_assignments' => null,
                'priority' => 4,
                'audience_tag' => 'loyalty',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WELCOME30',
                'title' => 'First 30% Cloud Drop',
                'description' => 'Unlock your first styling haul with 30% off. Limited to the first 50 new insiders.',
                'type' => 'percent',
                'value' => 30,
                'max_discount' => 150,
                'min_spend' => 120,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(2),
                'requires_assignment' => true,
                'max_assignments' => 50,
                'priority' => 100,
                'audience_tag' => 'welcome',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOYALTY20',
                'title' => 'Stage Two – 20% Pending',
                'description' => 'Set a reminder — 20% off your next curation once the welcome drop wraps.',
                'type' => 'percent',
                'value' => 20,
                'max_discount' => 120,
                'min_spend' => 150,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(3),
                'requires_assignment' => true,
                'max_assignments' => null,
                'priority' => 90,
                'audience_tag' => 'welcome',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RESTOCK10',
                'title' => 'Restock Reward – $10 + 10%',
                'description' => 'Stack a $10 credit with 10% off when your next capsule ships.',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 60,
                'min_spend' => 100,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
                'requires_assignment' => true,
                'max_assignments' => null,
                'priority' => 80,
                'audience_tag' => 'welcome',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GIFT15',
                'title' => 'VIP Gift 15%',
                'description' => 'Surprise drop — 15% off select collaborations once unlocked.',
                'type' => 'percent',
                'value' => 15,
                'max_discount' => 90,
                'min_spend' => 110,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
                'requires_assignment' => true,
                'max_assignments' => null,
                'priority' => 70,
                'audience_tag' => 'welcome',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->call([
            NotificationCenterSeeder::class,
        ]);

    }
}
