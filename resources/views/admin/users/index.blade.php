@extends('admin.layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="px-4 py-2 bg-slate-900 rounded-xl border shadow-black/20 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 w-64">
        <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">Search</button>
    </form>
</div>

<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-800/50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Orders</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Joined</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <span class="font-semibold text-sm">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4 text-sm font-semibold">{{ $user->orders_count }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-1 bg-brand-50 text-brand-600 rounded-lg text-xs font-semibold hover:bg-brand-100 transition-colors">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $users->withQueryString()->links() }}</div>
@endsection
