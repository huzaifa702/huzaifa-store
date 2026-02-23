<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'price', 'sale_price', 'sku', 'stock', 'is_active', 'is_featured',
        'weight', 'meta_title', 'meta_description', 'views_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $image = $this->primaryImage;
        if ($image && $image->image_path !== 'placeholder') {
            if (str_starts_with($image->image_path, 'http')) {
                return $image->image_path;
            }
            return asset('storage/' . $image->image_path);
        }
        return 'https://picsum.photos/seed/' . urlencode($this->slug) . '/400/400';
    }
}
