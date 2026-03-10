@extends('layouts.app')
@section('title', 'Page Expired')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-20">
    <div class="text-center animate-on-scroll">
        <div class="text-8xl md:text-9xl font-black gradient-text-gold mb-6 inline-block">419</div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Page Expired</h1>
        <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">Your session has expired due to inactivity. Please refresh and try again.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-brand-600 to-neon-cyan text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-brand-500/30 transition-all btn-glow">
                Login Again
            </a>
        </div>
    </div>
</div>
@endsection
