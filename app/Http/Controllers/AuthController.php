<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Merge guest cart into user cart
            $this->mergeGuestCart();

            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $this->mergeGuestCart();

        return redirect('/')->with('success', 'Account created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    private function mergeGuestCart(): void
    {
        $sessionId = session()->getId();
        $guestCart = Cart::where('session_id', $sessionId)->with('items')->first();

        if ($guestCart && auth()->check()) {
            $userCart = Cart::firstOrCreate(['user_id' => auth()->id()]);

            foreach ($guestCart->items as $item) {
                $existing = $userCart->items()->where('product_id', $item->product_id)->first();
                if ($existing) {
                    $existing->update(['quantity' => $existing->quantity + $item->quantity]);
                } else {
                    $userCart->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            $guestCart->items()->delete();
            $guestCart->delete();
        }
    }
}
