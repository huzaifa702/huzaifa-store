@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 animate-on-scroll">My Profile</h1>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="animate-on-scroll">
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 text-center">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-brand-500 to-accent-500 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                <p class="text-gray-400 text-xs mt-1">Member since {{ $user->created_at->format('M Y') }}</p>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="bg-dark-800/50 rounded-xl p-3">
                        <p class="text-2xl font-bold text-brand-600">{{ $user->orders->count() }}</p>
                        <p class="text-xs text-gray-500">Orders</p>
                    </div>
                    <div class="bg-dark-800/50 rounded-xl p-3">
                        <p class="text-2xl font-bold text-brand-600">{{ $user->reviews->count() }}</p>
                        <p class="text-xs text-gray-500">Reviews</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile -->
        <div class="lg:col-span-2 animate-on-scroll">
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6">
                <h3 class="font-bold text-lg mb-4">Edit Profile</h3>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $user->state) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">ZIP Code</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h4 class="font-semibold text-gray-300">Change Password</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Current Password</label>
                            <input type="password" name="current_password" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div></div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
                            <input type="password" name="new_password" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 bg-dark-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 text-sm">
                        </div>
                    </div>

                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">Save Changes</button>
                </form>
            </div>

            <!-- Recent Orders -->
            @if($recentOrders->count() > 0)
            <div class="bg-dark-900 rounded-2xl shadow-black/20 p-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg">Recent Orders</h3>
                    <a href="{{ route('orders.index') }}" class="text-brand-600 text-sm font-semibold hover:underline">View All →</a>
                </div>
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-3 bg-dark-800/50 rounded-xl hover:bg-dark-800 transition-colors">
                        <div>
                            <p class="font-semibold text-sm">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                            <span class="font-bold text-sm">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
