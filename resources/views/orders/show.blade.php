@extends('layouts.app2')

@section('content')
<div class="container mx-auto py-8 max-w-4xl px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Order Header -->
        <div class="bg-gradient-to-r from-[#ff0042] to-[#ff6b8b] px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <h1 class="text-2xl font-bold text-white">Order #{{ $order->id }}</h1>
                <div class="mt-2 sm:mt-0">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <p class="text-white/90 mt-1 text-sm">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <!-- Order Summary -->
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Order Summary</h2>
                <a href="{{ route('user.orders.invoice', $order) }}" target="_blank"
                   class="flex items-center gap-2 bg-white hover:bg-gray-50 text-[#ff0042] border border-[#ff0042] font-medium px-4 py-2 rounded-md shadow-sm transition-all duration-200 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Invoice
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p class="font-medium">${{ number_format($order->subtotal, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Shipping</p>
                    <p class="font-medium">${{ number_format($order->shipping_cost, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tax</p>
                    <p class="font-medium">${{ number_format($order->tax, 2) }}</p>
                </div>
                <div class="col-span-2 pt-2 border-t border-gray-200">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-bold text-[#ff0042]">${{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-start border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                    <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-md overflow-hidden">
                        @if($item->product && $item->product->media->first())
                            <img src="{{ asset('storage/' . $item->product->media->first()->path) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-16 w-16 flex items-center justify-center text-gray-400 bg-gray-100 rounded">
                                <span>No image</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-base font-medium text-gray-800">{{ $item->product_name }}</h3>
                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="ml-4 text-right">
                        <p class="text-base font-medium text-gray-800">${{ number_format($item->price, 2) }}</p>
                        @if($item->discount > 0)
                        <p class="text-xs text-green-600">Saved ${{ number_format($item->discount, 2) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('customer.orders.index') }}" class="flex items-center text-[#ff0042] hover:text-[#cc0035] font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>

            @if($order->status === 'pending')
            <form method="POST" action="{{ route('user.orders.cancel', $order) }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to cancel this order?')"
                    class="w-full sm:w-auto bg-white hover:bg-gray-100 text-red-600 border border-red-300 font-medium px-6 py-2 rounded-md shadow-sm transition">
                    Cancel Order
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
