<div class="space-y-4">
    @forelse($cart as $id => $item)
    <div class="flex items-center justify-between pb-4 border-b border-gray-line">
        <div class="flex items-center">
            @if(!empty($item['image']))
            <img src="{{ asset('storage/' . $item['image']) }}" alt="Product"
                class="h-12 w-12 object-cover rounded mr-2">
            @else
            <div class="h-12 w-12 bg-gray-200 rounded mr-2"></div>
            @endif
            <div>
                <p class="font-semibold">{{ $item['name'] ?? 'Unknown Product' }}</p>
                <p class="text-sm">Qty: {{ $item['quantity'] ?? 1 }}</p>
            </div>
        </div>
        <p class="font-semibold">${{ isset($item['price']) ? number_format($item['price'], 2) : '0.00' }}</p>
    </div>
    @empty
    <p class="text-center text-gray-500">Your cart is empty.</p>
    @endforelse

    @if(!empty($cart))
        <div class="pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <span class="font-semibold text-gray-600">Subtotal:</span>
                <span class="font-bold text-lg">${{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)), 2) }}</span>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('cart') }}" class="w-full text-center px-4 py-2 border border-[#ff0042] text-[#ff0042] hover:bg-gray-50 font-semibold rounded transition">View Cart</a>
                <a href="{{ route('checkout') }}" class="w-full text-center px-4 py-2 bg-[#ff0042] border border-[#ff0042] text-white hover:bg-[#cc0035] font-semibold rounded transition">Checkout</a>
            </div>
        </div>
    @endif
</div>
