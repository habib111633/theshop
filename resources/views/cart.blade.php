@extends('layouts.app2')

@section('title', 'cart')

@section('content')
<!-- Cart -->
<section id="cart-page" class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-center md:text-left font-semibold">Product</th>
                                    <th class="text-center font-semibold">Price</th>
                                    <th class="text-center font-semibold">Quantity</th>
                                    <th class="text-center md:text-right font-semibold">Total</th>
                                    <th class="text-center font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                @php
                                    $cart = session('cart', []);
                                    $subtotal = 0;
                                    foreach ($cart as $item) {
                                        $subtotal += $item['price'] * $item['quantity'];
                                    }
                                    $tax = round($subtotal * 0.17, 2); // 17% tax
                                    $shipping = 0.00;
                                    $total = $subtotal + $tax + $shipping;
                                @endphp
                                @forelse($cart as $productId => $item)
                                <tr data-product-id="{{ $productId }}" class="pb-4 border-b border-gray-line">
                                    <td class="px-1 py-4">
                                        <div class="flex items-center flex-col sm:flex-row text-center sm:text-left">
                                            @if($item['image'])
                                            <img class="h-16 w-16 md:h-24 md:w-24 sm:mr-8 mb-4 sm:mb-0"
                                                src="{{ asset('storage/' . $item['image']) }}" alt="Product image">
                                            @endif
                                            <p class="text-sm md:text-base md:font-semibold">{{ $item['name'] }}</p>
                                        </div>
                                    </td>
                                    <td class="px-1 py-4 text-center">${{ number_format($item['price'], 2) }}</td>
                                    <td class="px-1 py-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <button
                                                class="cart-decrement border border-[#ff0042]  bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] rounded-full w-10 h-10 flex items-center justify-center">-</button>
                                            <input type="number" class="quantity text-center w-24 mx-2"
                                                value="{{ $item['quantity'] }}" min="1" />
                                            <button
                                                class="cart-increment border border-[#ff0042]  bg-[#ff0042] hover:bg-transparent text-white  hover:text-[#ff0042] rounded-full w-10 h-10 flex items-center justify-center">+</button>
                                        </div>
                                    </td>
                                    <td class="px-1 py-4 text-right">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td class="px-1 py-4 text-center">
                                        <button class="cart-remove text-red-500 hover:underline" title="Remove item">Remove</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Your cart is empty.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
@include('partials.cart-summary', [
    'cart' => session('cart', []),
    'paymentEditable' => false,
    'paymentMethod' => 'cod'
])                    <a href="/checkout"
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042]  font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]  w-full text-center block">Proceed
                        to checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
<script src="/js/cart-manager.js"></script>
