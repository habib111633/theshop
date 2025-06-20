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
</div>
