<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = App\Models\Category::withCount('products')->get();
foreach($categories as $c) {
    echo "Category: " . $c->name . " -> " . $c->products_count . " products\n";
}
