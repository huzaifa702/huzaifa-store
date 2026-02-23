<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.primaryImage', 'product.category')
            ->latest()
            ->paginate(12);

        return view('wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['wishlisted' => false, 'message' => 'Removed from wishlist']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['wishlisted' => true, 'message' => 'Added to wishlist']);
    }
}
