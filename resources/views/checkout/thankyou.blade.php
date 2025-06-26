@extends('layouts.app2')

@section('title', 'Order Confirmation')

@section('content')
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You For Your Order!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Your order #{{ $order->id }} has been placed successfully.
                We've sent a confirmation to {{ $order->billing_email }}.
            </p>

            <div class="bg-gray-50 p-6 rounded-lg mb-8 text-left">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-gray-600">Order Number:</p>
                        <p class="font-medium">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Date:</p>
                        <p class="font-medium">{{ $order->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total:</p>
                        <p class="font-medium">${{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Payment Method:</p>
                        <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('home') }}"
                   class="bg-[#ff0042] text-white px-6 py-3 rounded-full font-medium hover:bg-opacity-90 transition">
                    Continue Shopping
                </a>
                <a href="{{ route('user.orders.show', $order->id) }}"
                   class="bg-white border border-[#ff0042] text-[#ff0042] px-6 py-3 rounded-full font-medium hover:bg-gray-50 transition">
                    View Order Details
                </a> 
            </div>
        </div>
    </div>
</section>
@endsection
