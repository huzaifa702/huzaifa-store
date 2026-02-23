@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
        <p class="text-gray-500 mt-2">Sign in to your account</p>
    </div>

    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8 shadow-xl shadow-black/30">
        @if($errors->any())
            <div class="mb-4 bg-accent-500/10 border border-accent-500/20 text-accent-400 px-4 py-3 rounded-xl text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition placeholder-gray-600"
                    placeholder="you@example.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition placeholder-gray-600"
                    placeholder="••••••••">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded bg-dark-800 border-dark-700 text-brand-500 focus:ring-brand-500">
                <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-xl font-bold hover:shadow-2xl hover:shadow-brand-500/30 transition-all transform hover:scale-[1.02]">Sign In</button>
        </form>
    </div>

    <p class="text-center text-gray-400 text-sm mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-brand-400 font-semibold hover:text-brand-300">Register</a>
    </p>
</div>
@endsection
