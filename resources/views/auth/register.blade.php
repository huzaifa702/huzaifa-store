@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md animate-on-scroll">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-brand-500 to-accent-500 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <span class="text-white text-3xl font-bold">H</span>
            </div>
            <h1 class="text-3xl font-bold gradient-text">Create Account</h1>
            <p class="text-gray-500 mt-2">Join us and start shopping</p>
        </div>

        <div class="bg-dark-900 rounded-3xl shadow-xl p-8">
            <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:bg-dark-900 transition-all text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:bg-dark-900 transition-all text-sm">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:bg-dark-900 transition-all text-sm">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:bg-dark-900 transition-all text-sm">
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-brand-500 to-accent-500 text-white rounded-xl font-bold hover:shadow-2xl hover:shadow-brand-500/30 transition-all transform hover:scale-[1.02]">
                    Create Account 🎉
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-500">
                Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:underline">Sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection
