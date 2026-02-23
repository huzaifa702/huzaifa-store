<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.primaryImage');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active || $product->stock < 1) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        $cart = $this->getOrCreateCart();
        $quantity = $request->input('quantity', 1);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }
}
