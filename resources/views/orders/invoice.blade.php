<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Invoice {{ $order->order_number }}</title>
<style>
body{font-family:Arial,sans-serif;font-size:14px;color:#333;margin:40px}
.header{display:flex;justify-content:space-between;border-bottom:3px solid #6366f1;padding-bottom:20px;margin-bottom:30px}
.logo{font-size:24px;font-weight:bold;color:#6366f1}
table{width:100%;border-collapse:collapse;margin:20px 0}
th{background:#6366f1;color:white;padding:10px;text-align:left}
td{padding:10px;border-bottom:1px solid #eee}
.total-row td{font-weight:bold;border-top:2px solid #6366f1}
.footer{margin-top:40px;text-align:center;color:#999;font-size:12px;border-top:1px solid #eee;padding-top:20px}
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="logo">Huzaifa Store</div>
        <p>Invoice</p>
    </div>
    <div style="text-align:right">
        <p><strong>{{ $order->order_number }}</strong></p>
        <p>{{ $order->created_at->format('M d, Y') }}</p>
        <p>Status: {{ ucfirst($order->status) }}</p>
    </div>
</div>

<div style="display:flex;justify-content:space-between;margin-bottom:30px">
    <div>
        <h3>Billed To:</h3>
        <p>{{ $order->user->name }}<br>{{ $order->user->email }}</p>
    </div>
    <div>
        <h3>Ship To:</h3>
        <p>{{ $order->shipping_name }}<br>{{ $order->shipping_address }}<br>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
    </div>
</div>

<table>
    <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
    <tbody>
        @foreach($order->items as $index => $item)
        <tr><td>{{ $index + 1 }}</td><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>${{ number_format($item->price, 2) }}</td><td>${{ number_format($item->total, 2) }}</td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="4" style="text-align:right">Subtotal</td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
        <tr><td colspan="4" style="text-align:right">Shipping</td><td>${{ number_format($order->shipping_cost, 2) }}</td></tr>
        <tr><td colspan="4" style="text-align:right">Tax</td><td>${{ number_format($order->tax, 2) }}</td></tr>
        <tr class="total-row"><td colspan="4" style="text-align:right">Total</td><td>${{ number_format($order->total, 2) }}</td></tr>
    </tfoot>
</table>

<p><strong>Payment Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</p>

<div class="footer">
    <p>Thank you for shopping with Huzaifa Store!</p>
    <p>support@huzaifastore.com | +92 300 1234567</p>
</div>
</body>
</html>
