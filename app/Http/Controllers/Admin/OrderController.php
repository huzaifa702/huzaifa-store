<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product.primaryImage', 'user', 'payment');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered' && $order->payment) {
            $order->payment->update(['status' => 'completed']);
        }

        try {
            ActivityLogService::log(
                'order_status_changed',
                "Order {$order->order_number} status changed from {$oldStatus} to {$request->status}",
                null,
                $order
            );
        } catch (\Throwable $e) {
            // Don't block status update
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;

        // Restore stock if the order wasn't already cancelled
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        // Delete order items and payment
        $order->items()->delete();
        if ($order->payment) {
            $order->payment->delete();
        }

        // Soft delete the order
        $order->delete();

        try {
            ActivityLogService::log(
                'order_deleted',
                "Order {$orderNumber} was deleted",
                null,
                null
            );
        } catch (\Throwable $e) {
            // Don't block deletion
        }

        return redirect()->route('admin.orders.index')->with('success', "Order {$orderNumber} has been deleted successfully.");
    }
}
