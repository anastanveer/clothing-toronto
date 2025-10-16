<?php

namespace App\Support;

use App\Models\CartItem;

class Loyalty
{
    public const POINTS_PER_DOLLAR = 10;

    /**
     * Convert a monetary amount into loyalty points.
     */
    public static function pointsForAmount(float $amount, int $pointsPerDollar = self::POINTS_PER_DOLLAR): int
    {
        return (int) round($amount * $pointsPerDollar);
    }

    /**
     * Get the base stage blueprint for the loyalty roadmap.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function stageBlueprint(): array
    {
        return [
            [
                'key' => 'spark',
                'title' => 'Stage 1: Spark Seeker',
                'threshold' => 150,
                'badge' => 'Spark Seeker Pin',
                'headline' => 'Ignite your wardrobe streak with a welcome surprise.',
                'reward' => 'Stylist shimmer pack tucked into your next delivery.',
                'bonus' => 'Double points on the next purchase after you unlock.',
            ],
            [
                'key' => 'ember',
                'title' => 'Stage 2: Ember Enthusiast',
                'threshold' => 350,
                'badge' => 'Ember Glow Patch',
                'headline' => 'Keep the momentum and build your signature look.',
                'reward' => 'Priority access to upcoming drops plus a $30 refill credit.',
                'bonus' => 'Unlock scheduled styling texts for big events.',
            ],
            [
                'key' => 'flare',
                'title' => 'Stage 3: Flare Icon',
                'threshold' => 600,
                'badge' => 'Flare Icon Crest',
                'headline' => 'You are leading the style board. Time for a badge drop.',
                'reward' => 'Limited edition tote + 3 outfit mood boards delivered digitally.',
                'bonus' => 'Weekend express shipping on the next two orders.',
            ],
            [
                'key' => 'nova',
                'title' => 'Stage 4: Nova Laureate',
                'threshold' => 900,
                'badge' => 'Nova Laureate Medal',
                'headline' => 'Stage two unlocked. Claim the celebratory perks.',
                'reward' => 'Private 1:1 digital styling session with our lead curator.',
                'bonus' => 'Mystery capsule mailed with your next qualifying order.',
            ],
            [
                'key' => 'legend',
                'title' => 'Stage 5: Legend Circle',
                'threshold' => 1250,
                'badge' => 'Legend Circle Laurel',
                'headline' => 'Finish the circuit and write your own reward.',
                'reward' => 'Custom couture wish granted up to $150 value or donation in your name.',
                'bonus' => 'Invitation to quarterly trunk show with exclusive lotteries.',
            ],
        ];
    }

    /**
     * Build the loyalty roadmap with progression metadata.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function buildStages(float $totalSpent): array
    {
        $stages = collect(self::stageBlueprint());
        $unlocked = true;

        return $stages->map(function (array $stage) use ($totalSpent, &$unlocked) {
            $rawProgress = $stage['threshold'] > 0
                ? min(100, (int) round(($totalSpent / $stage['threshold']) * 100))
                : 0;

            $stage['unlocked'] = $unlocked;
            $stage['achieved'] = $unlocked && $totalSpent >= $stage['threshold'];
            $stage['state'] = $stage['achieved'] ? 'achieved' : ($stage['unlocked'] ? 'current' : 'locked');
            $stage['progress'] = $stage['state'] === 'locked' ? 0 : $rawProgress;
            $stage['ghost_progress'] = $rawProgress;
            $stage['remaining'] = max(0, $stage['threshold'] - $totalSpent);

            if (! $stage['achieved']) {
                $unlocked = false;
            }

            return $stage;
        })->all();
    }

    /**
     * Derive a summary payload for views and UI hints.
     *
     * @param  float  $totalSpent Running lifetime spend in store currency.
     * @param  float  $cartValue  Current cart value for pending points.
     */
    public static function summarize(float $totalSpent, float $cartValue = 0): array
    {
        $stages = collect(self::buildStages($totalSpent));

        $completedStages = $stages->where('state', 'achieved')->count();
        $activeStage = $stages->where('state', 'achieved')->last();
        $currentStage = $stages->first(fn ($stage) => $stage['state'] === 'current');
        $nextStage = $currentStage ?? $stages->first(fn ($stage) => $stage['state'] === 'locked');

        return [
            'pointsPerDollar' => self::POINTS_PER_DOLLAR,
            'loyaltyPoints' => self::pointsForAmount($totalSpent),
            'cartPoints' => self::pointsForAmount($cartValue),
            'completedStages' => $completedStages,
            'activeStage' => $activeStage,
            'currentStage' => $currentStage,
            'nextStage' => $nextStage,
            'stages' => $stages->all(),
        ];
    }

    /**
     * Helper to total a cart collection into currency.
     *
     * @param  iterable<CartItem>  $cartItems
     */
    public static function cartValue(iterable $cartItems): float
    {
        $total = 0.0;

        foreach ($cartItems as $item) {
            $total += $item->line_total ?? ($item->quantity * ($item->unit_price ?? 0));
        }

        return (float) $total;
    }
}
