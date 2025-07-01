@extends('layouts.app2')

@section('title', 'Checkout')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Checkout -->
<section id="checkout-page" class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-8">Checkout</h1>
      <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Billing and Shipping Details -->
            <div class="md:w-2/3 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Billing Details</h2>
                    <div class="mb-4">
                        <label for="billing-name" class="mb-4">Full Name</label>
                        <input type="text" id="billing-name" name="billing_name"
                            class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="billing-email" class="mb-4">Email</label>
                        <input type="email" id="billing-email" name="billing_email"
                            class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="billing-address" class="mb-4">Address</label>
                        <input type="text" id="billing-address" name="billing_address"
                            class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="billing-city" class="mb-4">City</label>
                        <input type="text" id="billing-city" name="billing_city"
                            class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div class="mb-4 flex gap-4">
                        <div class="w-1/2">
                            <label for="billing-state" class="mb-4">State</label>
                            <input type="text" id="billing-state" name="billing_state"
                                class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                        <div class="w-1/2">
                            <label for="billing-zip" class="mb-4">ZIP Code</label>
                            <input type="text" id="billing-zip" name="billing_zip"
                                class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="billing-phone" class="mb-4">Phone Number</label>
                        <input type="tel" id="billing-phone" name="billing_phone"
                            class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                            required>
                    </div>
                    <div class="mb-4">
                        <label>
                            <input type="checkbox" id="different-address" name="different_address" class="mr-2">
                            Ship to a different address?
                        </label>
                    </div>
                    <div id="shipping-fields" style="display:none;">
                        <div class="mb-4">
                            <label for="shipping-address">Shipping Address</label>
                            <input type="text" id="shipping-address" name="shipping_address" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" >
                        </div>
                        <div class="mb-4">
                            <label for="shipping-city">Shipping City</label>
                            <input type="text" id="shipping-city" name="shipping_city" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" >
                        </div>
                        <div class="mb-4">
                            <label for="shipping-state">Shipping State</label>
                            <input type="text" id="shipping-state" name="shipping_state" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" >
                        </div>
                        <div class="mb-4">
                            <label for="shipping-zip">Shipping ZIP</label>
                            <input type="text" id="shipping-zip" name="shipping_zip" class="w-full px-3 mt-2 py-2 border focus:border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-primary" >
                        </div>
                    </div>
            </div>
            <!-- Order Summary -->
            <div class="md:w-1/3 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
@include('partials.cart-summary', [
    'cart' => session('cart', []),
    'paymentEditable' => true,
    'paymentMethod' => old('payment_method', 'cod')
])                <button type="submit"
                    class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042]  font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]  w-full text-center block">Proceed
                    to Payment</button>
            </div>
        </div>
      </form>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method summary update (already present)
    if (document.querySelector('input[name="payment_method"]:not([disabled])')) {
        document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const subtotal = parseFloat(document.getElementById('cart-subtotal').textContent.replace('$', '')) || 0;
                let taxRate = this.value === 'bank' ? 0.05 : 0.17;
                const tax = +(subtotal * taxRate).toFixed(2);
                const shipping = 0.00;
                const total = +(subtotal + tax + shipping).toFixed(2);

                document.getElementById('cart-tax').textContent = '$' + tax.toFixed(2);
                document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
                document.querySelector('#cart-tax').previousElementSibling.textContent = `Taxes (${taxRate * 100}%)`;
            });
        });
    }

    // Checkout form validation and submission
    const checkoutForm = document.querySelector('#checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Simple validation example
            let valid = true;
            checkoutForm.querySelectorAll('input[required]').forEach(function(input) {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
        });
    }

    // Toggle shipping fields visibility
    document.getElementById('different-address').addEventListener('change', function() {
        document.getElementById('shipping-fields').style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endsection
