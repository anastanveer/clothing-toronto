<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'summary',
        'description',
        'price',
        'sale_price',
        'stock',
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
        'is_featured' => 'boolean',
        'gallery_images' => 'array',
        'options' => 'array',
    ];
}
