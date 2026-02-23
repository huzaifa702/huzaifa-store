@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Today's Quick Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => "Today's Revenue", 'value' => '$' . number_format($todayRevenue, 2), 'icon' => '📈', 'color' => 'from-emerald-400 to-green-600'],
        ['label' => "Today's Orders", 'value' => $todayOrders, 'icon' => '🛍️', 'color' => 'from-sky-400 to-blue-600'],
        ['label' => 'Pending Orders', 'value' => $pendingOrders, 'icon' => '⏳', 'color' => 'from-amber-400 to-orange-600'],
        ['label' => 'Active Products', 'value' => $activeProducts, 'icon' => '✅', 'color' => 'from-violet-400 to-purple-600'],
    ] as $stat)
    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-white/5 hover:border-white/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br {{ $stat['color'] }} rounded-lg flex items-center justify-center text-lg shadow-lg">{{ $stat['icon'] }}</div>
            <div>
                <p class="text-lg font-bold text-gray-100">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Main Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach([
        ['label' => 'Total Revenue', 'value' => '$' . number_format($totalRevenue, 2), 'icon' => '💰', 'color' => 'from-green-400 to-emerald-500'],
        ['label' => 'Total Orders', 'value' => number_format($totalOrders), 'icon' => '🛒', 'color' => 'from-blue-400 to-indigo-500'],
        ['label' => 'Total Products', 'value' => number_format($totalProducts), 'icon' => '📦', 'color' => 'from-purple-400 to-pink-500'],
        ['label' => 'Total Customers', 'value' => number_format($totalCustomers), 'icon' => '👥', 'color' => 'from-orange-400 to-red-500'],
    ] as $stat)
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-gradient-to-br {{ $stat['color'] }} rounded-xl flex items-center justify-center text-2xl shadow-lg">{{ $stat['icon'] }}</div>
        </div>
        <p class="text-2xl font-bold text-gray-100">{{ $stat['value'] }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <!-- Sales Chart -->
    <div class="lg:col-span-2 bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <h3 class="font-bold text-lg mb-4">📊 Monthly Sales</h3>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    <!-- Category Distribution -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <h3 class="font-bold text-lg mb-4">🏷️ Products by Category</h3>
        <canvas id="categoryChart" height="200"></canvas>
        <div class="mt-4 space-y-2">
            @foreach($categoryDistribution as $cat)
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400">{{ $cat['name'] }}</span>
                <span class="font-semibold text-gray-200">{{ $cat['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Activity -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <h3 class="font-bold text-lg mb-4">📋 Recent Activity</h3>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @foreach($recentActivities as $activity)
            <div class="flex gap-3 items-start">
                <div class="w-8 h-8 bg-brand-600/20 rounded-lg flex items-center justify-center text-sm flex-shrink-0">📋</div>
                <div>
                    <p class="text-sm text-gray-300">{{ $activity->action }}</p>
                    <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg">🛒 Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-brand-600 text-sm font-semibold hover:underline">View All →</a>
        </div>
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors">
                <div>
                    <p class="font-semibold text-sm">{{ $order->order_number }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->name ?? 'Guest' }} · {{ $order->created_at->diffForHumans() }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm">${{ number_format($order->total, 2) }}</p>
                    <span class="text-xs font-bold {{ $order->status_badge }} px-2 py-0.5 rounded-full">{{ ucfirst($order->status) }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Top Products -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <h3 class="font-bold text-lg mb-4">🏆 Top Products</h3>
        <div class="space-y-3">
            @foreach($topProducts as $item)
            <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-xl">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">{{ $loop->iteration }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $item->product_name }}</p>
                    <p class="text-xs text-gray-400">{{ $item->total_sold }} sold</p>
                </div>
                <p class="font-bold text-sm text-brand-600">${{ number_format($item->total_revenue, 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-slate-900 rounded-2xl shadow-black/20 p-6">
        <h3 class="font-bold text-lg mb-4">⚡ Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 p-4 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors border border-transparent hover:border-brand-500/20">
                <span class="text-2xl">📦</span>
                <div>
                    <p class="font-semibold text-sm">Add Product</p>
                    <p class="text-xs text-gray-500">New listing</p>
                </div>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-3 p-4 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors border border-transparent hover:border-brand-500/20">
                <span class="text-2xl">🏷️</span>
                <div>
                    <p class="font-semibold text-sm">Add Category</p>
                    <p class="text-xs text-gray-500">New category</p>
                </div>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-4 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors border border-transparent hover:border-brand-500/20">
                <span class="text-2xl">📋</span>
                <div>
                    <p class="font-semibold text-sm">View Orders</p>
                    <p class="text-xs text-gray-500">Manage orders</p>
                </div>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-4 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition-colors border border-transparent hover:border-brand-500/20">
                <span class="text-2xl">👥</span>
                <div>
                    <p class="font-semibold text-sm">View Users</p>
                    <p class="text-xs text-gray-500">Customer list</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($monthlySales->pluck('revenue')) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } },
                x: { grid: { display: false } }
            }
        }
    });

    // Category Distribution Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryDistribution->pluck('count')) !!},
                backgroundColor: ['#6366f1', '#06b6d4', '#8b5cf6', '#f59e0b', '#10b981', '#ec4899', '#ef4444', '#3b82f6'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 12, font: { size: 11 } } }
            },
            cutout: '65%'
        }
    });
</script>
@endsection
