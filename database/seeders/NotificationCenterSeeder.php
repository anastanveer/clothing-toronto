<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnnouncementCard;
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
        $this->seedAnnouncements();
        $this->seedCoupons();
        $this->markFeatureProducts();
        $this->refreshBlogSummaries();
    }

    protected function seedAnnouncements(): void
    {
        $cards = [
            [
                'title' => 'Midnight Capsule Preview',
                'subtitle' => 'Silk tailoring drops Friday',
                'category' => 'product',
                'body' => 'Secure early access to velvet suiting, luminous saris, and midnight crystal accessories.',
                'cta_label' => 'Preview the capsule',
                'cta_url' => '/shop?collection=midnight-capsule',
                'is_featured' => true,
                'is_published' => true,
                'starts_at' => now()->subHours(3),
                'ends_at' => now()->addDays(3),
                'meta' => [
                    'icon' => 'flaticon-shopping-bag',
                    'tone' => 'launch',
                ],
            ],
            [
                'title' => 'Stage three perks unlocked',
                'subtitle' => 'Runway Circle invitations are live',
                'category' => 'loyalty',
                'body' => 'Members who cleared stage three get private fittings plus a $60 styling credit.',
                'cta_label' => 'View loyalty roadmap',
                'cta_url' => '/account/dashboard#loyalty',
                'is_featured' => false,
                'is_published' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addWeeks(2),
                'meta' => [
                    'icon' => 'flaticon-star',
                    'tone' => 'loyalty',
                ],
            ],
            [
                'title' => 'Styling lab: Velvet Lane',
                'subtitle' => 'New blog coverage on silhouette layering',
                'category' => 'editorial',
                'body' => 'Peek behind the shoot with layering formulas, accessory stacks, and care tips.',
                'cta_label' => 'Read the article',
                'cta_url' => '/blog',
                'is_featured' => false,
                'is_published' => true,
                'starts_at' => now()->subHours(6),
                'meta' => [
                    'icon' => 'flaticon-blogging',
                    'tone' => 'editorial',
                ],
            ],
            [
                'title' => 'Archive sale queue',
                'subtitle' => 'You’re waitlisted for the archive drop',
                'category' => 'general',
                'body' => 'We’ll notify you when the Archive Atelier opens — limited entries across Canada.',
                'cta_label' => 'Manage alerts',
                'cta_url' => '/account/dashboard#alerts',
                'is_featured' => false,
                'is_published' => false,
                'starts_at' => now()->addDays(2),
                'ends_at' => now()->addWeeks(1),
                'meta' => [
                    'icon' => 'flaticon-warning',
                    'tone' => 'waitlist',
                ],
            ],
        ];

        foreach ($cards as $data) {
            AnnouncementCard::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
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
