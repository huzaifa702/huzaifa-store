<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (!auth()->check() || $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product.primaryImage', 'payment');

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        if (!auth()->check() || $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
