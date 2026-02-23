@extends('admin.layouts.app')
@section('title', 'Backups')
@section('page-title', 'Backups & Tools')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-bold">Database Backups</h2>
    <form action="{{ route('admin.backups.create') }}" method="POST">
        @csrf
        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm">💾 Create Backup</button>
    </form>
</div>

@if($backups->count() > 0)
<div class="bg-slate-900 rounded-2xl shadow-black/20 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-800/50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Filename</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Size</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Created By</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($backups as $backup)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 font-semibold text-sm">{{ $backup->filename }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $backup->size }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $backup->created_by ?? 'System' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $backup->created_at->format('M d, Y h:i A') }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.backups.download', $backup) }}" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">Download</a>
                        <form action="{{ route('admin.backups.restore', $backup) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this backup? This will overwrite the current database!')">
                            @csrf
                            <button class="px-3 py-1 bg-yellow-50 text-yellow-600 rounded-lg text-xs font-semibold hover:bg-yellow-100 transition-colors">Restore</button>
                        </form>
                        <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" onsubmit="return confirm('Delete this backup?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-slate-900 rounded-2xl shadow-black/20 p-12 text-center">
    <div class="text-6xl mb-4">💾</div>
    <h3 class="text-xl font-bold text-gray-400">No backups yet</h3>
    <p class="text-gray-400 mt-2">Create your first database backup to get started.</p>
</div>
@endif
@endsection
