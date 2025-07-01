<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #0F1111; padding: 10px;">
    <!-- Header -->
    <div style="padding: 15px 0; border-bottom: 1px solid #DDD; text-align: center;">
        <img src="{{ asset('images/template-logo.png') }}" alt="{{ $appName }}" style="height: 40px;">
    </div>

    <!-- Greeting -->
    <h1 style="font-size: 20px; font-weight: normal; margin: 20px 0 10px;">Hello, {{ $order->billing_name ?? 'Customer' }}</h1>
    <p style="margin: 0 0 20px; line-height: 1.5;">Thank you for your order. We'll send a confirmation when your items ship.</p>

    <!-- Order Summary Card -->
    <div style="border: 1px solid #DDD; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
        <h2 style="font-size: 16px; margin: 0 0 10px; font-weight: bold;">Order #{{ $order->id }}</h2>
        <p style="margin: 0 0 5px; color: #565959;">Placed on {{ $order->created_at->format('F j, Y') }}</p>
        <p style="margin: 0; color: #565959;">Total: <span style="font-weight: bold; color: #0F1111;">${{ number_format($order->total, 2) }}</span></p>
    </div>

    <!-- Delivery Estimate -->
    <div style="background: #F0F2F2; border-radius: 4px; padding: 15px; margin-bottom: 20px;">
        <h2 style="font-size: 16px; margin: 0 0 10px; font-weight: bold;">Estimated Delivery</h2>
        <p style="margin: 0; color: #0F1111;">{{ now()->addDays(3)->format('F j, Y') }}</p>
    </div>

    <!-- Order Items -->
    <h2 style="font-size: 16px; margin: 0 0 10px; font-weight: bold;">Order Details</h2>
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

    <!-- Order Total -->
    <div style="margin-top: 15px; text-align: right; font-size: 14px;">
        <p style="margin: 5px 0;">Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
        <p style="margin: 5px 0;">Shipping: ${{ number_format($order->shipping, 2) }}</p>
        <p style="margin: 5px 0;">Tax: ${{ number_format($order->tax, 2) }}</p>
        <p style="margin: 10px 0; font-weight: bold; font-size: 17px;">Order Total: ${{ number_format($order->total, 2) }}</p>
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ url('/orders/' . $order->id) }}" style="background: #FFA41C; color: #0F1111; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">Track Your Order</a>
    </div>

    <!-- Footer -->
    <div style="border-top: 1px solid #DDD; padding-top: 15px; color: #565959; font-size: 12px;">
        <p style="margin: 5px 0;">Need help? Email us at support@example.com</p>
        <p style="margin: 5px 0;">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
    </div>
</body>
</html>