<?php

namespace App\Http\Controllers\Concerns;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait InteractsWithCart
{
    protected function cartOwnerColumn(Request $request): string
    {
        return $request->user() ? 'user_id' : 'session_id';
    }

    protected function cartOwnerValue(Request $request): mixed
    {
        return $request->user()?->id ?? $request->session()->getId();
    }

    protected function scopedCartItems(Request $request, array $with = []): Builder
    {
        $query = CartItem::query();

        if ($with) {
            $query->with($with);
        }

        return $query->where($this->cartOwnerColumn($request), $this->cartOwnerValue($request));
    }

    protected function assignCartItemOwner(Request $request, CartItem $cartItem): void
    {
        if ($request->user()) {
            $cartItem->user_id = $request->user()->id;
            $cartItem->session_id = null;
        } else {
            $cartItem->user_id = null;
            $cartItem->session_id = $request->session()->getId();
        }
    }

    protected function ensureCartItemOwner(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->{$this->cartOwnerColumn($request)} !== $this->cartOwnerValue($request)) {
            abort(403, 'You cannot modify this bag item.');
        }
    }
}
