<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$query = App\Models\Product::where('is_active', true)->with(['category', 'primaryImage', 'approvedReviews']);
$query->orderBy('created_at', 'desc');
$products = $query->paginate(12);

echo "Total: " . $products->total() . "\n";
echo "Count: " . $products->count() . "\n";
foreach($products as $p) {
    echo "- " . $p->name . "\n";
}
