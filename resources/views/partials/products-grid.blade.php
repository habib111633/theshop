<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($products as $product)
    <div class="bg-white p-4 rounded-lg shadow">
        @if ($product->media->first())
        <img src="{{ asset('storage/' . $product->media->first()->path) }}" alt="Product 1"
            class="w-full object-cover mb-4 rounded-lg">
        @endif
        <a href="#" class="text-lg font-semibold mb-2">{{ $product->name }}</a>
        <p class=" my-2">{{ $product->category->name }}</p>
        <div class="flex items-center mb-4">
            <span class="text-lg font-bold text-[#ff0042]">${{ number_format($product->price, 2) }}</span>
            <span class="text-sm  ml-2">{{ number_format($product->stock, 0) }} units</span>
        </div>
        <button
            class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Add
            to Cart</button>
    </div>
    @endforeach
</div>
