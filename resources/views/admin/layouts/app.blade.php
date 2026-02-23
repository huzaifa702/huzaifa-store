<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — Admin Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81', 950: '#1e1b4b' },
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 3px; }

        /* Admin Responsive */
        @media (max-width: 768px) {
            .grid-cols-2, .lg\:grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
            .grid-cols-3, .lg\:grid-cols-3, .md\:grid-cols-3 { grid-template-columns: 1fr !important; }
            table { display: block; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; }
            h1, h2, .text-2xl, .text-3xl { font-size: 1.25rem !important; }
            .p-6 { padding: 1rem !important; }
            canvas { max-height: 200px !important; }
        }
        @media (max-width: 480px) {
            .grid-cols-2, .lg\:grid-cols-4 { grid-template-columns: 1fr !important; }
            input, select, textarea { font-size: 16px !important; }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-950 text-gray-100 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 h-full w-64 bg-slate-900 border-r border-slate-800 z-50 transition-transform duration-300 lg:translate-x-0">
        <div class="p-6 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <span class="text-white font-black text-lg">H</span>
                </div>
                <div>
                    <span class="text-lg font-bold text-white block">Admin Panel</span>
                    <span class="text-xs text-gray-500">Huzaifa Store</span>
                </div>
            </a>
        </div>
        <nav class="p-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 180px);">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                    ['route' => 'admin.products.index', 'icon' => '📦', 'label' => 'Products'],
                    ['route' => 'admin.categories.index', 'icon' => '📂', 'label' => 'Categories'],
                    ['route' => 'admin.orders.index', 'icon' => '🛒', 'label' => 'Orders'],
                    ['route' => 'admin.users.index', 'icon' => '👥', 'label' => 'Users'],
                    ['route' => 'admin.reports.index', 'icon' => '📈', 'label' => 'Reports'],
                    ['route' => 'admin.backups.index', 'icon' => '💾', 'label' => 'Backups'],
                ];
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs($item['route'].'*') ? 'bg-brand-600/20 text-brand-400 shadow-sm' : 'text-gray-400 hover:bg-slate-800 hover:text-gray-200' }}">
                    <span class="text-lg">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-800">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 transition-all">
                    <span class="text-lg">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Top Bar -->
        <header class="bg-slate-900/80 backdrop-blur-lg border-b border-slate-800 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 lg:px-6 h-16">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold text-white">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 text-sm text-gray-400 hover:text-brand-400 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View Store
                    </a>
                    <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-brand-500/20">
                        A
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                     class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                     class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
