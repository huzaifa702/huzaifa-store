<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error — Huzaifa Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-text-rose {
            background: linear-gradient(135deg, #f43f5e, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-slate-950 text-gray-100 antialiased min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <div class="text-8xl md:text-9xl font-black gradient-text-rose mb-6 inline-block">500</div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Internal Server Error</h1>
        <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">Oops! Something went wrong on our servers. We're looking into it.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-slate-800 hover:bg-slate-700 text-white border border-slate-600 rounded-xl font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </button>
            <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition-all">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>
