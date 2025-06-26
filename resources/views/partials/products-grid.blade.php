<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($products as $product)
    <div class="bg-white p-4 rounded-lg shadow">
        @if ($product->media->first())
        <img src="{{ asset('storage/' . $product->media->first()->path) }}" alt="Product 1"
            class="w-full object-cover mb-4 rounded-lg">
        @endif
        <a href="{{ route('product.detail', $product->id) }}" class="text-lg font-semibold mb-2">
            {{ $product->name }}
        </a>
        <p class=" my-2">{{ $product->category->name }}</p>
        <div class="flex items-center mb-4">
            <span class="text-lg font-bold text-[#ff0042]">${{ number_format($product->price, 2) }}</span>
            <span class="text-sm ml-2" id="available-stock-{{ $product->id }}">Loading...</span>
        </div>
        <button
    class="add-to-cart-btn bg-[#ff0042] text-white px-4 py-2 rounded"
    data-product-id="{{ $product->id }}"
    id="add-to-cart-btn-{{ $product->id }}">
    Add to Cart
</button>
    </div>
    @endforeach
</div>
<!-- Pagination -->
<div class="flex justify-center mt-8">
    <nav aria-label="Page navigation" class="pagination">
        {!! $products->withQueryString()->links() !!}
    </nav>
</div>
