<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>New Order Alert - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #0F1111; padding: 10px;">
    {{-- Header --}}
    <div style="padding: 15px 0; border-bottom: 1px solid #DDD; text-align: center;">
        <img src="{{ asset('images/template-logo.png') }}" alt="{{ config('app.name') }}" style="height: 40px;">
       <br> <span style="color: #565959; font-size: 14px; margin-left: 10px;">New Order Alert</span>
    </div>

    {{-- Order Summary --}}
    <h1 style="font-size: 18px; margin: 20px 0 10px; font-weight: bold;">New Order #{{ $order->id }}</h1>
    <div style="border: 1px solid #DDD; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
        <p style="margin: 0 0 5px;"><strong>Placed:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p style="margin: 0 0 5px;"><strong>Status:</strong> <span style="color: #B12704;">Pending</span></p>
        <p style="margin: 0;"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
    </div>

    {{-- Customer Info --}}
    <h2 style="font-size: 16px; margin: 0 0 10px; font-weight: bold;">Customer Information</h2>
    <div style="background: #F0F2F2; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
        <p style="margin: 0 0 5px;"><strong>Name:</strong> {{ $order->billing_name }}</p>
        <p style="margin: 0 0 5px;"><strong>Email:</strong> <a href="mailto:{{ $order->billing_email }}" style="color: #0066C0; text-decoration: none;">{{ $order->billing_email }}</a></p>
        @if($order->customer_phone)
        <p style="margin: 0 0 5px;"><strong>Phone:</strong> <a href="tel:{{ $order->customer_phone }}" style="color: #0066C0; text-decoration: none;">{{ $order->customer_phone }}</a></p>
        @endif
        @if($order->billing_address)
        <p style="margin: 0;"><strong>Billing Address:</strong><br>{{ $order->billing_address }}</p>
        @endif
    </div>

    {{-- Order Items --}}
    <h2 style="font-size: 16px; margin: 0 0 10px; font-weight: bold;">Order Items</h2>
    @if($orderItems && $orderItems->count() > 0)
    @foreach($orderItems as $item)
    <div style="display: flex; padding: 10px 0; border-bottom: 1px solid #EEE; align-items: center;">
        <div style="flex: 1;">{{ $item->product_name }}</div>
        <div style="width: 80px; text-align: right;">${{ number_format($item->price, 2) }}</div>
        <div style="width: 40px; text-align: center;">x{{ $item->quantity }}</div>
    </div>
    @endforeach
    @else
        <div style="padding: 10px 0; color: #565959; font-style: italic;">
            Order items are being processed...
        </div>
    @endif

    {{-- Order Total --}}
    <div style="margin-top: 15px; text-align: right; font-size: 14px;">
        <p style="margin: 5px 0;">Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
        <p style="margin: 5px 0;">Shipping: ${{ number_format($order->shipping, 2) }}</p>
        <p style="margin: 5px 0;">Tax: ${{ number_format($order->tax, 2) }}</p>
        <p style="margin: 10px 0; font-weight: bold; font-size: 17px;">Total: ${{ number_format($order->total, 2) }}</p>
    </div>

    {{-- Admin CTA --}}
    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ route('admin.orders.show', $order->id) }}" style="background: #FFA41C; color: #0F1111; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">Process Order</a>
    </div>

    {{-- Footer --}}
    <div style="border-top: 1px solid #DDD; padding-top: 15px; color: #565959; font-size: 12px;">
        <p style="margin: 5px 0;">This is an automated notification. Do not reply.</p>
    </div>
</body>
</html>