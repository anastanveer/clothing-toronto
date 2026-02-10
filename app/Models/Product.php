<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CATEGORIES = [
        'men',
        'women',
        'kids',
    ];

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category',
        'brand_id',
        'summary',
        'description',
        'price',
        'sale_price',
        'average_rating',
        'reviews_count',
        'stock',
        'primary_color',
        'is_featured',
        'status',
        'meta_title',
        'meta_description',
        'featured_image',
        'gallery_images',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'is_featured' => 'boolean',
        'gallery_images' => 'array',
        'options' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProductLike::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
