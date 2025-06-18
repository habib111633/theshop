@extends('layouts.app2')

@section('title', 'shop')

@section('content')

<!-- Shop -->
<section id="shop">
    <div class="container mx-auto">
        <!-- Top Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center py-4">
            <div class="flex items-center space-x-4">
                <button
                    class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">Show
                    On
                    Sale</button>
                <button
                    class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">List
                    View</button>
                <button
                    class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">Grid
                    View</button>
            </div>
            <div class="flex mt-5 md:mt-0 space-x-4">
                <div class="relative">
                    <select
                        class="block appearance-none w-full bg-white border  hover:border-[#ff0042] px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option>Sort by Latest</option>
                        <option>Sort by Popularity</option>
                        <option>Sort by A-Z</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center px-2">
                        <img id="arrow-down" class="h-4 w-4" src="{{ asset('images/filter-down-arrow.svg') }}"
                            alt="filter arrow">
                        <img id="arrow-up" class="h-4 w-4 hidden" src="{{ asset('images/filter-up-arrow.svg') }}"
                            alt="filter arrow">
                    </div>
                </div>
            </div>
        </div>
        <!-- Filter Toggle Button for Mobile -->
        <div class="block md:hidden text-center mb-4">
            <button id="products-toggle-filters"
                class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">Show
                Filters</button>
        </div>
        <div class="flex flex-col md:flex-row">
            <!-- Filters -->
            <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                <!-- Category Filter -->
                <!-- Category Filter -->
                <form id="category-filter-form">
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Category</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox category-checkbox"
                                    name="categories[]" value="{{ $category->id }}">
                                <span class="ml-2">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </form>

            </div>
            <!-- Products List -->
            <div class="w-full md:w-3/4 p-4">
                <!-- Products grid -->
                <div id="products-grid">
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
                                <span
                                    class="text-lg font-bold text-[#ff0042]">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm  ml-2">{{ number_format($product->stock, 0) }} units</span>
                            </div>
                            <button
                                class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Add
                                to Cart</button>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    <nav aria-label="Page navigation">
                        <ul class="inline-flex space-x-2">
                            <li>
                                <a href="#"
                                    class="bg-[#ff0042] text-white w-10 h-10 flex items-center justify-center rounded-full">1</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-[#ff0042] hover:text-white">2</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-[#ff0042] hover:text-white">3</a>
                            </li>
                            <li>
                                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop category description -->
<section id="shop-category-description" class="py-8">
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Shirts Category</h2>
            <p class="mb-4">
                Discover our wide range of shirts, perfect for any occasion. Whether you're looking for something
                casual
                or formal, we have the perfect shirt for you. Our collection includes a variety of styles, colors,
                and
                sizes to suit everyone's taste.
            </p>
            <p>
                Browse through our selection and find your new favorite shirt today. All our shirts are made from
                high-quality materials and are designed to provide both comfort and style. Shop now and elevate your
                wardrobe with our premium shirts.
            </p>
        </div>
    </div>
</section>
<script>
document.querySelectorAll('.category-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        let form = document.getElementById('category-filter-form');
        let formData = new FormData(form);

        fetch("{{ route('shop.ajax') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('products-grid').innerHTML = html;
            });
    });
});
</script>
@endsection
