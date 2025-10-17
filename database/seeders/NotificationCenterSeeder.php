<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Coupon;
use App\Models\Product;

class NotificationCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCoupons();
        $this->markFeatureProducts();
        $this->refreshBlogSummaries();
    }

    protected function seedCoupons(): void
    {
        $coupons = [
            [
                'code' => 'FLASH30',
                'title' => 'Flash 30% Capsule',
                'description' => 'Tonight only — 30% off our night market edit. Auto applies at checkout.',
                'type' => 'percent',
                'value' => 30,
                'max_discount' => 90,
                'min_spend' => 110,
                'is_active' => true,
                'starts_at' => now()->subHours(2),
                'expires_at' => now()->addDay(),
                'requires_assignment' => false,
                'priority' => 120,
            ],
            [
                'code' => 'FALLPREVIEW',
                'title' => 'Early Access 25%',
                'description' => 'Reserve your fall capsule with 25% off before the public drop.',
                'type' => 'percent',
                'value' => 25,
                'max_discount' => 75,
                'min_spend' => 150,
                'is_active' => true,
                'starts_at' => now()->addDays(1),
                'expires_at' => now()->addWeeks(2),
                'requires_assignment' => true,
                'priority' => 95,
            ],
            [
                'code' => 'LOUNGESETS',
                'title' => 'Bundle & Save $40',
                'description' => 'Stack a $40 credit when you pair tops and bottoms from the lounge edit.',
                'type' => 'fixed',
                'value' => 40,
                'max_discount' => 40,
                'min_spend' => 180,
                'is_active' => true,
                'starts_at' => now()->subDays(2),
                'expires_at' => now()->addMonth(),
                'requires_assignment' => false,
                'priority' => 80,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(['code' => $data['code']], $data);
        }
    }

    protected function markFeatureProducts(): void
    {
        Product::query()
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(6)
            ->update(['is_featured' => true]);
    }

    protected function refreshBlogSummaries(): void
    {
        BlogPost::query()
            ->latest('published_at')
            ->take(3)
            ->each(function (BlogPost $post) {
                if (! $post->excerpt) {
                    $post->update([
                        'excerpt' => substr(strip_tags($post->body ?? ''), 0, 140) ?: 'Fresh from the Glamer studio — styling notes and drop previews.',
                    ]);
                }
            });
    }
}
