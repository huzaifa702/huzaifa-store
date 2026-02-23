<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $cart = Cart::where('user_id', auth()->id())->with('items.product.primaryImage')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = auth()->user();

        return view('checkout.index', compact('cart', 'user'));
    }

    public function process(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_zip' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->product->display_price * $item->quantity;
        }

        $tax = round($subtotal * 0.05, 2);
        $shipping = $subtotal >= 100 ? 0 : 9.99;
        $total = $subtotal + $tax + $shipping;

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shipping,
            'total' => $total,
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_zip' => $request->shipping_zip,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->display_price,
                'total' => $item->product->display_price * $item->quantity,
            ]);

            // Reduce stock
            $item->product->decrement('stock', $item->quantity);
        }

        Payment::create([
            'order_id' => $order->id,
            'method' => $request->payment_method,
            'amount' => $total,
            'status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
        ]);

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        ActivityLogService::log('order_placed', "Order {$order->order_number} placed", auth()->user(), $order);

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }
}
