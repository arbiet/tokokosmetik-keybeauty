<!DOCTYPE html>
<html>
<head>
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 0; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        .header h1 { margin: 0; font-size: 24px; }
        .details { margin-bottom: 20px; }
        .details p { margin: 5px 0; }
        .details .left, .details .right { display: inline-block; vertical-align: top; width: 48%; }
        .details .right { text-align: right; }
        .details .left p, .details .right p { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .total { text-align: right; margin-bottom: 20px; }
        .total p { font-size: 16px; margin: 5px 0; }
        .footer { text-align: center; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('storage/images/logo.jpg') }}" alt="Company Logo">
            <h1>INVOICE</h1>
        </div>
        <div class="details">
            <div class="left">
                <p><strong>From:</strong></p>
                <p>Toko Kosmetik Keybeauty</p>
                <p>Jl. Example No.123, Nganjuk</p>
                <p>Jawa Timur, Indonesia</p>
                <p>Phone: 081234567890</p>
            </div>
            <div class="right">
                <p><strong>Invoice No:</strong> {{ $order->id }}</p>
                <p><strong>Order Date:</strong> {{ $order->order_date }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Total:</strong> Rp. {{ number_format($order->final_total, 2) }}</p>
            </div>
        </div>
        <div class="details">
            <div class="left">
                <p><strong>To:</strong></p>
                <p>{{ $order->user->name }}</p>
                <p>{{ $order->destination_location }}</p>
                <p>{{ $order->user->email }}</p>
            </div>
            <div class="right"></div>
        </div>
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp. {{ number_format($item->price, 2) }}</td>
                    <td>Rp. {{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total">
            <p><strong>Subtotal:</strong> Rp. {{ number_format($order->total, 2) }}</p>
            <p><strong>Discount:</strong> Rp. {{ number_format($order->discount, 2) }}</p>
            <p><strong>Shipping Cost:</strong> Rp. {{ number_format($order->shipping_cost, 2) }}</p>
            <p><strong>Total:</strong> Rp. {{ number_format($order->final_total, 2) }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>If you have any questions, feel free to contact us at support@keybeauty.com or call us at 081234567890</p>
        </div>
    </div>
</body>
</html>
