<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Services\AiAgentService;
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
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s.\'-]+$/'],
            'email' => ['required', 'email:rfc,dns', 'unique:users', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+?\d{7,15}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.regex' => 'Name must contain only letters, spaces, dots, hyphens, and apostrophes.',
            'email.email' => 'Please enter a valid email address with a real domain.',
            'phone.regex' => 'Phone number must be 7-15 digits, optionally starting with +.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $this->mergeGuestCart();

        // Auto-send welcome email with deals to new user
        try {
            $aiService = app(AiAgentService::class);
            $aiService->sendMarketingEmail(
                $user->email,
                '👋 Welcome to Huzaifa Store! Here Are Your Exclusive Deals',
                $this->buildWelcomeEmailHtml($user->name),
                $user->id
            );
        } catch (\Exception $e) {
            // Don't fail registration if email fails
        }

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

        if ($guestCart && Auth::check()) {
            $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

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

    /**
     * Build welcome email HTML for new user registration.
     */
    private function buildWelcomeEmailHtml(string $name): string
    {
        $storeUrl = config('app.url');
        $featuredProducts = \App\Models\Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('primaryImage')
            ->take(3)
            ->get();

        $productsHtml = '';
        foreach ($featuredProducts as $p) {
            $price = $p->is_on_sale ? '$' . number_format($p->sale_price, 2) : '$' . number_format($p->price, 2);
            $productsHtml .= "<tr><td style='padding:12px;border-bottom:1px solid #eee;'>"
                . "<strong>{$p->name}</strong><br>"
                . "<span style='color:#6366f1;font-weight:bold;'>{$price}</span>"
                . "</td></tr>";
        }

        return <<<HTML
        <div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;background:#f9fafb;padding:24px;border-radius:12px;">
            <div style="text-align:center;padding:20px 0;">
                <h1 style="color:#1e293b;margin:0;">🛍️ Huzaifa Store</h1>
            </div>
            <div style="background:white;padding:24px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <h2 style="color:#6366f1;">Welcome to Huzaifa Store, {$name}! 🎉</h2>
                <p>Thank you for joining our community! We're thrilled to have you.</p>
                <p>Here are some of our <strong>top products</strong> just for you:</p>
                <table style="width:100%;border-collapse:collapse;margin-top:16px;">
                    {$productsHtml}
                </table>
                <div style="text-align:center;margin-top:24px;">
                    <a href="{$storeUrl}" style="display:inline-block;padding:12px 32px;background:#6366f1;color:white;text-decoration:none;border-radius:8px;font-weight:bold;">Shop Now →</a>
                </div>
                <p style="color:#64748b;font-size:13px;margin-top:16px;">🔥 As a new member, enjoy free shipping on your first order over $50!</p>
            </div>
            <p style="text-align:center;color:#94a3b8;font-size:12px;margin-top:16px;">
                © 2026 Huzaifa Store · mhuzaifa2503a@aptechorangi.com<br>
                <a href="#" style="color:#94a3b8;">Unsubscribe</a>
            </p>
        </div>
        HTML;
    }
}
