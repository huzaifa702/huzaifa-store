<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_id' => $admin->id, 'admin_name' => $admin->name, 'admin_email' => $admin->email]);

            try {
                ActivityLogService::log('admin_login', 'Admin logged in', $admin);
            } catch (\Throwable $e) {
                // Log silently — don't block login
            }

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        try {
            ActivityLogService::log('admin_logout', 'Admin logged out');
        } catch (\Throwable $e) {
            // Log silently — don't block logout
        }

        session()->forget(['admin_id', 'admin_name', 'admin_email']);

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
