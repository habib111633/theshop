<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order #{{ $order->id }} Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Order Information</h3>
                        <div class="mb-2">
                            <span class="font-semibold">Order ID:</span> #{{ $order->id }}<br>
                            <span class="font-semibold">Order Date:</span> {{ $order->created_at->format('d M Y, H:i') }}<br>
                            <span class="font-semibold">Total:</span> ${{ number_format($order->total, 2) }}<br>
                            <span class="font-semibold">Status:</span>
                            <span class="px-2 py-1 rounded text-xs
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.orders.edit', $order) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit Status</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Customer Information</h3>
                        @if($order->user)
                            <span class="font-semibold">Name:</span> {{ $order->user->name }}<br>
                            <span class="font-semibold">Email:</span> {{ $order->user->email }}
                        @else
                            <span class="font-semibold">Name:</span> Guest ({{ $order->billing_name }})<br>
                            <span class="font-semibold">Email:</span> {{ $order->billing_email }}
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Billing Information</h3>
                        <span class="font-semibold">Address:</span> {{ $order->billing_address }}<br>
                        <span class="font-semibold">City:</span> {{ $order->billing_city }}<br>
                        <span class="font-semibold">State:</span> {{ $order->billing_state }}<br>
                        <span class="font-semibold">Zip:</span> {{ $order->billing_zip }}<br>
                        <span class="font-semibold">Phone:</span> {{ $order->billing_phone }}
                    </div>
                </div>

                @if($order->shipping_address)
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Shipping Information</h3>
                    <span class="font-semibold">Address:</span> {{ $order->shipping_address }}<br>
                    <span class="font-semibold">City:</span> {{ $order->shipping_city ?? '' }}<br>
                    <span class="font-semibold">State:</span> {{ $order->shipping_state ?? '' }}<br>
                    <span class="font-semibold">Zip:</span> {{ $order->shipping_zip }}<br>
                </div>
                @endif

                <h3 class="text-lg font-bold mb-4">Order Items</h3>
                <table class="min-w-full bg-white border rounded mb-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">Product</th>
                            <th class="px-4 py-2 border-b">Quantity</th>
                            <th class="px-4 py-2 border-b">Price</th>
                            <th class="px-4 py-2 border-b">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $item->product_name }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border-b">${{ number_format($item->price, 2) }}</td>
                            <td class="px-4 py-2 border-b">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
