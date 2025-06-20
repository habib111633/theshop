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
                                @php $cart = session('cart', []); @endphp
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
                        <div class="px-1 flex flex-col lg:flex-row justify-between items-center mt-10">
                            <div class="flex items-center">
                                <input type="text" placeholder="Coupon code"
                                    class="border border-gray-300 rounded-l-full py-2 px-4 focus:outline-none">
                                <button
                                    class="bg-[#ff0042] text-white border border-[#ff0042] hover:bg-transparent hover:text-[#ff0042] rounded-r-full py-2 px-4">Apply
                                    Coupon</button>
                            </div>
                            <div class="mt-4 lg:mt-0 flex space-x-2">
                                <button
                                    class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042]  font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] ">Empty
                                    Cart</button>
                                <button
                                    class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042]  font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] ">Update
                                    Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-4">
                        <p>Subtotal</p>
                        <p>$19.99</p>
                    </div>
                    <div class="flex justify-between mb-4">
                        <p>Taxes</p>
                        <p>$1.99</p>
                    </div>
                    <div class="flex justify-between mb-4 pb-4 border-b border-gray-line">
                        <p>Shipping</p>
                        <p>$0.00</p>
                    </div>
                    <div class="flex justify-between mb-2">
                        <p class="font-semibold">Total</p>
                        <p class="font-semibold">$21.98</p>
                    </div>
                    <a href="/checkout"
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042]  font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]  w-full text-center block">Proceed
                        to checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for remove button
    document.getElementById('cart-items').addEventListener('click', function(e) {
        if (e.target.classList.contains('cart-remove')) {
            const row = e.target.closest('tr');
            const productId = row.dataset.productId;
            if (confirm('Are you sure you want to remove this item from the cart?')) {
                fetch("{{ route('cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(res => res.json())
                .then(data => {
                    row.remove();
                    // Update cart preview in header
                    if (document.querySelector('.cart-dropdown')) {
                        document.querySelector('.cart-dropdown').innerHTML = data.preview;
                    }
                    // Update cart count badge if present
                    document.querySelectorAll('.cart-count').forEach(function(el) {
                        el.textContent = data.count || 0;
                    });
                    // If cart is empty, show empty message in table
                    if (document.querySelectorAll('#cart-items tr').length === 0) {
                        document.getElementById('cart-items').innerHTML = `<tr><td colspan="5" class="text-center">Your cart is empty.</td></tr>`;
                    }
                });
            }
        }
    });

    // Quantity increment/decrement
    document.querySelectorAll('.cart-increment, .cart-decrement').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const input = row.querySelector('.quantity');
            let qty = parseInt(input.value) || 1;
            if (this.classList.contains('cart-increment')) {
                qty++;
            } else if (qty > 1) {
                qty--;
            }
            input.value = qty;
            updateCart(row.dataset.productId, qty, row);
        });
    });

    // Quantity manual change
    document.querySelectorAll('.quantity').forEach(function(input) {
        input.addEventListener('change', function() {
            let qty = parseInt(this.value) || 1;
            if (qty < 1) qty = 1;
            this.value = qty;
            const row = this.closest('tr');
            updateCart(row.dataset.productId, qty, row);
        });
    });

    function updateCart(productId, quantity, row) {
        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            // Update row total
            if (row) {
                const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace('$', ''));
                row.querySelector('td:nth-child(4)').textContent = '$' + (price * quantity).toFixed(2);
            }
            // Update cart preview in header
            if (document.querySelector('.cart-dropdown')) {
                document.querySelector('.cart-dropdown').innerHTML = data.preview;
            }
            // Update cart count badge if present
            document.querySelectorAll('.cart-count').forEach(function(el) {
                el.textContent = data.count || 0;
            });
        });
    }
});
</script>

@endsection
