<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Huzaifa Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            animation: particleFloat 8s ease-in-out infinite;
        }
        @keyframes particleFloat {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
            50% { transform: translateY(-30px) translateX(15px); opacity: 0.7; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #020617 0%, #0f172a 30%, #1e3a8a 70%, #0f172a 100%);">
    <!-- Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        @for($i = 0; $i < 15; $i++)
        <div class="particle" style="left: {{ rand(5, 95) }}%; top: {{ rand(5, 95) }}%; animation-delay: {{ $i * 0.5 }}s;"></div>
        @endfor
        <div class="absolute top-20 left-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-56 h-56 bg-blue-400/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl shadow-blue-500/30">
                <span class="text-white font-bold text-3xl">H</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
            <p class="text-gray-500 text-sm mt-1">Sign in to manage your store</p>
        </div>

        <!-- Login Form -->
        <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl p-8 shadow-2xl shadow-black/50 border border-slate-800/50">
            @if($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-600"
                        placeholder="mhuzaifa2503a@aptechorangi.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-600"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-bold hover:shadow-2xl hover:shadow-blue-500/30 transition-all transform hover:scale-[1.02]">
                    Sign In
                </button>
            </form>
        </div>
        <p class="text-center text-gray-400 text-sm mt-6">&copy; {{ date('Y') }} Huzaifa Store Admin</p>
    </div>
</body>
</html>
