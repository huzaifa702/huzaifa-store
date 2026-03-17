<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::with(['category', 'primaryImage', 'approvedReviews'])->orderBy('created_at', 'desc')->paginate(12);
$categories = App\Models\Category::all();

$html = view('products.index', compact('products', 'categories'))->render();
file_put_contents('test_view_output.html', $html);
