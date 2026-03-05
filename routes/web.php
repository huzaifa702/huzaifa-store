<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\WishlistController;

// Database setup route (protected by secret key)
Route::get('/setup-database/{key}', function ($key) {
    if ($key !== 'huzaifa2026secret') {
        abort(404);
    }
    $results = [];

    // Step 1: Run migrations
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $results[] = '✅ Migrations completed';
    } catch (\Exception $e) {
        $results[] = '❌ Migration error: ' . $e->getMessage();
    }

    // Step 2: Force create/update admin account
    try {
        $admin = \App\Models\Admin::where('email', 'mhuzaifa2503a@aptechorangi.com')->first();
        if ($admin) {
            $admin->update(['password' => \Illuminate\Support\Facades\Hash::make('M.HUZAIFA5566')]);
            $results[] = '✅ Admin password RESET (account existed)';
        } else {
            \App\Models\Admin::create([
                'name' => 'M. Huzaifa',
                'email' => 'mhuzaifa2503a@aptechorangi.com',
                'password' => \Illuminate\Support\Facades\Hash::make('M.HUZAIFA5566'),
                'role' => 'super_admin',
            ]);
            $results[] = '✅ Admin account CREATED';
        }
    } catch (\Exception $e) {
        $results[] = '❌ Admin setup error: ' . $e->getMessage();
    }

    // Step 3: Seed products (if empty)
    try {
        $productCount = \App\Models\Product::count();
        if ($productCount === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            $results[] = '✅ Database seeded with products (' . \App\Models\Product::count() . ' products)';
        } else {
            $results[] = '✅ Products already exist (' . $productCount . ' products)';
        }
    } catch (\Exception $e) {
        $results[] = '❌ Seeder error: ' . $e->getMessage();
    }

    // Step 4: Show stats
    $results[] = '';
    $results[] = '📊 Database Stats:';
    try {
        $results[] = '   Admin accounts: ' . \App\Models\Admin::count();
        $results[] = '   Users: ' . \App\Models\User::count();
        $results[] = '   Categories: ' . \App\Models\Category::count();
        $results[] = '   Products: ' . \App\Models\Product::count();
    } catch (\Exception $e) {
        $results[] = '   Stats error: ' . $e->getMessage();
    }

    $results[] = '';
    $results[] = '🔐 Admin Login: /admin/login';
    $results[] = '   Email: mhuzaifa2503a@aptechorangi.com';
    $results[] = '   Password: M.HUZAIFA5566';

    return '<pre style="background:#1e293b;color:#e2e8f0;padding:2rem;font-family:monospace;font-size:14px;line-height:1.8;">' . implode("\n", $results) . '</pre>';
});

// Debug diagnostic — shows actual error messages (protected by secret)
Route::get('/debug-admin/{key}', function ($key) {
    if ($key !== 'huzaifa2026secret') abort(404);

    $results = [];

    // Test 1: Can we query categories?
    try {
        $cats = \App\Models\Category::withCount('products')->orderBy('sort_order')->get();
        $results[] = '✅ Categories query OK — ' . $cats->count() . ' categories';
    } catch (\Exception $e) {
        $results[] = '❌ Categories FAIL: ' . $e->getMessage();
    }

    // Test 2: Can we render the categories view?
    try {
        $categories = \App\Models\Category::withCount('products')->orderBy('sort_order')->paginate(15);
        $html = view('admin.categories.index', compact('categories'))->render();
        $results[] = '✅ Categories view renders OK (' . strlen($html) . ' bytes)';
    } catch (\Exception $e) {
        $results[] = '❌ Categories VIEW FAIL: ' . $e->getMessage() . ' — File: ' . $e->getFile() . ':' . $e->getLine();
    }

    // Test 3: Can we render the category create view?
    try {
        $html = view('admin.categories.create')->render();
        $results[] = '✅ Category create view renders OK';
    } catch (\Exception $e) {
        $results[] = '❌ Category create VIEW FAIL: ' . $e->getMessage() . ' — File: ' . $e->getFile() . ':' . $e->getLine();
    }

    // Test 4: Activity log table exists?
    try {
        $count = \App\Models\ActivityLog::count();
        $results[] = '✅ activity_logs table OK — ' . $count . ' entries';
    } catch (\Exception $e) {
        $results[] = '❌ activity_logs FAIL: ' . $e->getMessage();
    }

    // Test 5: Dashboard
    try {
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $results[] = '✅ Dashboard queries OK — ' . $totalProducts . ' products, ' . $totalCategories . ' categories';
    } catch (\Exception $e) {
        $results[] = '❌ Dashboard FAIL: ' . $e->getMessage();
    }

    // Test 6: Admin layout view
    try {
        $categories = \App\Models\Category::withCount('products')->orderBy('sort_order')->paginate(15);
        $html = view('admin.layouts.app', ['__env' => app(\Illuminate\View\Factory::class)])->render();
        $results[] = '✅ Admin layout renders OK';
    } catch (\Exception $e) {
        $results[] = '❌ Admin layout FAIL: ' . $e->getMessage() . ' — File: ' . $e->getFile() . ':' . $e->getLine();
    }

    // Test 7: Check if sessions work
    try {
        session(['test_key' => 'test_value']);
        $val = session('test_key');
        $results[] = '✅ Sessions OK — test value: ' . $val;
    } catch (\Exception $e) {
        $results[] = '❌ Sessions FAIL: ' . $e->getMessage();
    }

    // Test 8: Check all migration tables exist
    $tables = ['admins', 'users', 'categories', 'products', 'product_images', 'orders', 'order_items', 'activity_logs', 'reviews', 'wishlists'];
    foreach ($tables as $table) {
        try {
            $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
            $results[] = ($exists ? '✅' : '❌') . " Table '$table': " . ($exists ? 'EXISTS' : 'MISSING');
        } catch (\Exception $e) {
            $results[] = "❌ Table check '$table' FAIL: " . $e->getMessage();
        }
    }

    return '<pre style="background:#1e293b;color:#e2e8f0;padding:2rem;font-family:monospace;font-size:14px;line-height:1.8;">' . implode("\n", $results) . '</pre>';
});

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Chatbot / AI Agent
Route::get('/chatbot', [ChatbotController::class, 'page'])->name('chatbot.page');
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/image-search', [ChatbotController::class, 'imageSearch'])->name('chatbot.image');
Route::post('/chatbot/tts', [ChatbotController::class, 'synthesizeSpeech'])->name('chatbot.tts');
Route::post('/chatbot/email', [ChatbotController::class, 'sendEmail'])->name('chatbot.email');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Categories
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

// Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
