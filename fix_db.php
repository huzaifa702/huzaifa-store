<?php

use App\Models\Product;
use Illuminate\Support\Str;

// H-04: Fix Diamond Ring Truncated Description
$diamondRing = Product::where('slug', 'diamond-ring')->first();
if ($diamondRing) {
    if (str_ends_with($diamondRing->description, 'worl')) {
        $diamondRing->description = "An exquisite diamond ring that signifies timeless elegance. Perfect for special moments and engagements. Handcrafted by master jewelers, it features a brilliant-cut center stone surrounded by a halo of smaller diamonds, creating a dazzling display of light. This ring is not just a piece of jewelry, but a symbol of enduring love with cultural significance around the world.";
        $diamondRing->save();
    }
}

// L-01: Rename 'Trendy Fashion' product to differentiation from category
$trendyProduct = Product::where('slug', 'trendy-fashion')->first();
if ($trendyProduct) {
    $trendyProduct->name = 'Trendy Fashion Jacket';
    $trendyProduct->slug = Str::slug('Trendy Fashion Jacket');
    $trendyProduct->save();
}

// L-02: Add missing SKU to golden necklace
$necklace = Product::where('slug', 'elegant-golden-necklace')->first();
if ($necklace) {
    if (empty($necklace->sku)) {
        $necklace->sku = 'JW-GLD-NKLC-001';
        $necklace->save();
    }
}

// L-06: Title Case the "diamond ring"
if ($diamondRing) {
    $diamondRing->name = 'Diamond Ring';
    $diamondRing->save();
}

// H-01: Ensure all products are active
Product::where('is_active', false)->update(['is_active' => true]);

echo "All DB fixes applied successfully.\n";
