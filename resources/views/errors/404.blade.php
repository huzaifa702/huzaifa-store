@extends('layouts.app')
@section('title', 'Page Not Found')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-20">
    <div class="text-center animate-on-scroll">
        <div class="text-8xl md:text-9xl font-black gradient-text mb-6 inline-block">404</div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Page Not Found</h1>
        <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-brand-600 to-neon-cyan text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-brand-500/30 transition-all btn-glow transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </a>
    </div>
</div>
@endsection
