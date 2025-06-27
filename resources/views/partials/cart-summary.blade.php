@php
   $cart = $cart ?? session('cart', []);
    $paymentMethod = $paymentMethod ?? 'cod';
    $paymentEditable = $paymentEditable ?? false;
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $taxRate = $paymentMethod === 'bank' ? 0.04 : 0.17;
    $tax = round($subtotal * $taxRate, 2);
    $shipping = 0.00;
    $total = $subtotal + $tax + $shipping;
@endphp

<div class="flex justify-between mb-4">
    <p>Subtotal</p>
    <p id="cart-subtotal">${{ number_format($subtotal, 2) }}</p>
</div>
@if($paymentEditable)
<div class="mb-4">
    <label class="font-semibold block mb-2">Payment Method</label>
    <div class="flex gap-3">
        <label class="relative flex items-center cursor-pointer border rounded-md px-3 py-2 transition-all duration-150 text-sm
            @if($paymentMethod === 'cod') border-[#ff0042] bg-[#fff0f5] shadow @else border-gray-300 bg-white @endif
            hover:border-[#ff0042] hover:bg-[#fff0f5]">
            <input type="radio" name="payment_method" id="cod" value="cod"
                class="peer absolute opacity-0 w-0 h-0"
                {{ $paymentMethod === 'cod' ? 'checked' : '' }}>
            <span class="flex items-center">
                <span class="text-xl mr-1">💵</span>
                <span class="font-medium">Cash on Delivery</span>
            </span>
            <span class="ml-2 text-xs text-gray-500 hidden md:inline">(Pay when you receive)</span>
            <span class="absolute right-2 top-2 w-3 h-3 rounded-full border border-[#ff0042] bg-white peer-checked:bg-[#ff0042]"></span>
        </label>
        <label class="relative flex items-center cursor-pointer border rounded-md px-3 py-2 transition-all duration-150 text-sm
            @if($paymentMethod === 'bank') border-[#ff0042] bg-[#fff0f5] shadow @else border-gray-300 bg-white @endif
            hover:border-[#ff0042] hover:bg-[#fff0f5]">
            <input type="radio" name="payment_method" id="bank" value="bank"
                class="peer absolute opacity-0 w-0 h-0"
                {{ $paymentMethod === 'bank' ? 'checked' : '' }}>
            <span class="flex items-center">
                <span class="text-xl mr-1">💳</span>
                <span class="font-medium">Stripe</span>
            </span>
            <span class="ml-2 text-xs text-gray-500 hidden md:inline">(Credit card payment, 4% tax)</span>
            <span class="absolute right-2 top-2 w-3 h-3 rounded-full border border-[#ff0042] bg-white peer-checked:bg-[#ff0042]"></span>
        </label>
    </div>
</div>
@endif
<div class="flex justify-between mb-4">
    <p>Taxes ({{ $taxRate * 100 }}%)</p>
    <p id="cart-tax">${{ number_format($tax, 2) }}</p>
</div>
<div class="flex justify-between mb-4 pb-4 border-b border-gray-line">
    <p>Shipping</p>
    <p id="cart-shipping">${{ number_format($shipping, 2) }}</p>
</div>
<div class="flex justify-between mb-2">
    <p class="font-semibold">Total</p>
    <p class="font-semibold" id="cart-total">${{ number_format($total, 2) }}</p>
</div>
