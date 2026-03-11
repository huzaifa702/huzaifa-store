<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('orders');
        $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    public function destroy(User $user)
    {
        $userName = $user->name;

        // Cancel any pending/processing orders
        $user->orders()->whereIn('status', ['pending', 'processing'])->update(['status' => 'cancelled']);

        // Soft delete the user
        $user->delete();

        ActivityLogService::log(
            'user_deleted',
            "Customer '{$userName}' was deleted",
            null,
            null
        );

        return redirect()->route('admin.users.index')->with('success', "Customer '{$userName}' has been deleted successfully.");
    }
}
