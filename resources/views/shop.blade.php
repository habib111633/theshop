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
                    class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">List
                    View</button>
                <button
                    class="bg-[#ff0042] hover:bg-transparent  text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] focus:outline-none">Grid
                    View</button>
            </div>
            <div class="flex mt-5 md:mt-0 space-x-4">
                <div class="relative">
                    <select name="sort" id="sort-select"
                        class="block appearance-none w-full bg-white border hover:border-[#ff0042] px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="latest">Sort by Latest</option>
                        <option value="az">Sort by A-Z</option>
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
                    @include('partials.products-grid', ['products' => $products])

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
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeEventListeners();

    // Initial attachment of pagination events
    attachPaginationEvents();

    // After updating products grid, re-attach available stock and add-to-cart logic
    reattachAvailableStockAndCart();
});

function initializeEventListeners() {
    // Category checkboxes
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', () => sendFilterRequest());
    });

    // Sort select
    document.getElementById('sort-select').addEventListener('change', () => sendFilterRequest());

    // Mobile filter toggle
    document.getElementById('products-toggle-filters')?.addEventListener('click', function() {
        const filters = document.getElementById('filters');
        filters.classList.toggle('hidden');
        this.textContent = filters.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
    });

    // Arrow toggle for select dropdown
    document.getElementById('sort-select')?.addEventListener('focus', function() {
        document.getElementById('arrow-down').classList.add('hidden');
        document.getElementById('arrow-up').classList.remove('hidden');
    });

    document.getElementById('sort-select')?.addEventListener('blur', function() {
        document.getElementById('arrow-down').classList.remove('hidden');
        document.getElementById('arrow-up').classList.add('hidden');
    });
}

function sendFilterRequest(url = null) {
    // Show loading indicator (optional)
    const productsGrid = document.getElementById('products-grid');
    productsGrid.innerHTML = '<div class="text-center py-8">Loading products...</div>';

    // Collect all filter parameters
    const params = new URLSearchParams();
    const formData = new FormData(document.getElementById('category-filter-form'));
    const sortValue = document.getElementById('sort-select').value;

    // Add categories to params
    document.querySelectorAll('.category-checkbox:checked').forEach(cb => {
        params.append('categories[]', cb.value);
    });

    // Add sort to params
    params.append('sort', sortValue);

    // Determine the request URL and method
    let requestUrl, requestOptions;

    if (url) {
        // Pagination request - GET with all parameters
        const urlObj = new URL(url);
        const existingParams = new URLSearchParams(urlObj.search);

        // Preserve existing parameters while updating with current filters
        existingParams.set('sort', sortValue);
        existingParams.delete('categories[]'); // Remove old category params

        // Add current category filters
        document.querySelectorAll('.category-checkbox:checked').forEach(cb => {
            existingParams.append('categories[]', cb.value);
        });

        requestUrl = `${url.split('?')[0]}?${existingParams.toString()}`;
        requestOptions = {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        };
    } else {
        // Initial filter request - POST with FormData
        requestUrl = "{{ route('shop.ajax') }}";
        formData.append('sort', sortValue);
        requestOptions = {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'text/html'
            },
            body: formData
        };
    }

    // Make the fetch request
    fetch(requestUrl, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            productsGrid.innerHTML = html;
            reattachAvailableStockAndCart();

            // Scroll to top of products grid (optional)
            productsGrid.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            productsGrid.innerHTML = `
    <div class="text-center py-8 text-red-500">
        Error loading products. Please try again.
    </div>
    `;
        });
}

function attachPaginationEvents() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            sendFilterRequest(this.href);
        });
    });
}

// After updating products grid, re-attach available stock and add-to-cart logic
function reattachAvailableStockAndCart() {
    document.querySelectorAll('[id^="available-stock-"]').forEach(function(span) {
        const productId = span.id.replace('available-stock-', '');
        fetch('/product/' + productId + '/available-stock')
            .then(response => response.json())
            .then(data => {
                let stockElem = document.getElementById('available-stock-' + productId);
                let btn = document.getElementById('add-to-cart-btn-' + productId);
                if (data.available_stock > 0) {
                    stockElem.textContent = data.available_stock + ' in stock';
                    if(btn) { btn.disabled = false; btn.textContent = 'Add to Cart'; }
                } else {
                    stockElem.textContent = 'Out of Stock';
                    if(btn) { btn.disabled = true; btn.textContent = 'Out of Stock'; }
                }
            });
    });
    // Re-initialize cart manager for new products
    if (typeof CartManager !== 'undefined') {
        CartManager.initialize();
    }
}
</script>
<script src="/js/cart-manager.js"></script>
@endsection
