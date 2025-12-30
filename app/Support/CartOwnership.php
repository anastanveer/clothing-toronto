<?php

namespace App\Support;

use App\Models\CartItem;
use App\Models\User;

class CartOwnership
{
    public static function migrateSessionCart(?string $sessionId, ?User $user): void
    {
        if (! $sessionId || ! $user?->id) {
            return;
        }

        CartItem::query()
            ->where('session_id', $sessionId)
            ->update([
                'session_id' => null,
                'user_id' => $user->id,
            ]);
    }
}
