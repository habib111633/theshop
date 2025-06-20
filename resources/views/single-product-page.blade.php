@extends('layouts.app2')

@section('title', 'Poduct detail')

@section('content')

<!-- Breadcrumbs -->
<section id="breadcrumbs" class="pt-6 bg-gray-50">
    <div class="container mx-auto px-4">
        <ol class="list-reset flex">
            <li><a href="{{ route('home') }}" class="font-semibold hover:text-[#ff0042]">Home</a></li>
            <li><span class="mx-2">&gt;</span></li>
            <li><a href="{{ route('shop') }}" class="font-semibold hover:text-[#ff0042]">Shop</a></li>
            <li><span class="mx-2">&gt;</span></li>
            <li><a href="#" class="font-semibold hover:text-[#ff0042]">T-shirts</a></li>
            <li><span class="mx-2">&gt;</span></li>
            <li>Preppy T-shirt</li>
        </ol>
    </div>
</section>

<!-- Product info -->
<section id="product-info">
    <div class="container mx-auto px-4">
        <div class="py-6">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Image Section -->
                <div class="w-full lg:w-1/2">
                    <div class="grid gap-4">
                        <!-- Big Image -->



                        <div id="main-image-container">
                            <img id="main-image"
                                class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px]"
                                src="{{ asset('storage/' . $product->media->first()->path) }}" alt="Main Product Image" />


                        </div>
                        <!-- Small Images -->
                        <div class="grid grid-cols-5 gap-4">
                            <div>
                                <img onclick="changeImage(this)" data-full="{{ asset('images/single-product/1.jpg') }}"
                                    src="{{ asset('images/single-product/1.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 1" />
                            </div>
                            <div>
                                <img onclick="changeImage(this)" data-full="{{ asset('images/single-product/2.jpg') }}"
                                    src="{{ asset('images/single-product/2.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 2" />
                            </div>
                            <div>
                                <img onclick="changeImage(this)" data-full="{{ asset('images/single-product/3.jpg') }}"
                                    src="{{ asset('images/single-product/3.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 3" />
                            </div>
                            <div>
                                <img onclick="changeImage(this)" data-full="{{ asset('images/single-product/4.jpg') }}"
                                    src="{{ asset('images/single-product/4.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 4" />
                            </div>
                            <div>
                                <img onclick="changeImage(this)" data-full="{{ asset('images/single-product/5.jpg') }}"
                                    src="{{ asset('images/single-product/5.jpg') }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image 5" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Details Section -->
                <div class="w-full lg:w-1/2 flex flex-col justify-between">
                    <div class="pb-8 border-b border-gray-line">
                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                        <div class="flex items-center mb-8">
                            <span>★★★★★</span>
                            <span class="ml-2">(0 Reviews)</span>
                            <a href="#" class="ml-4 text-primary font-semibold">Write a review</a>
                        </div>
                        <div class="mb-4 pb-4 border-b border-gray-line">
                            <p class="mb-2">Brand:<strong><a href="#" class="hover:text-primary"> Nike</a></strong>
                            </p>
                            <p class="mb-2">Product code:<strong> 00123</strong></p>
                            <p class="mb-2">Availability:<strong> {{ number_format($product->stock, 0) }}
                                    in stock</strong></p>
                        </div>
                        <div class="text-2xl font-semibold mb-8">${{ number_format($product->price, 2) }}</div>
                        <div class="flex items-center mb-8">
                            <button id="decrease"
                                class="bg-[#ff0042] hover:bg-transparent border border-transparent hover:border-[#ff0042] text-white hover:text-[#ff0042] font-semibold w-10 h-10 rounded-full flex items-center justify-center focus:outline-none">-</button>
                            <input id="quantity" type="number" value="1" class="w-16 py-2 text-center focus:outline-none"
                                >
                            <button id="increase"
                                class="bg-[#ff0042] hover:bg-transparent border border-transparent hover:border-[#ff0042] text-white hover:text-[#ff0042] font-semibold  w-10 h-10 rounded-full focus:outline-none">+</button>
                        </div>
                        <button
                            class="bg-[#ff0042] border border-transparent hover:bg-transparent hover:border-[#ff0042] text-white hover:text-[#ff0042] font-semibold py-2 px-4 rounded-full add-to-cart-btn"
                            data-product-id="{{ $product->id }}">Add to Cart</button>
                    </div>
                    <!-- Social sharing -->
                    <div class="flex space-x-4 my-6">
                        <a href="#" class="w-4 h-4 flex items-center justify-center">
                            <img src="{{ asset('images/social_icons/facebook.svg') }}" alt="Facebook"
                                class="w-4 h-4 transition-transform transform hover:scale-110">
                        </a>
                        <a href="#" class="w-4 h-4 flex items-center justify-center">
                            <img src="{{ asset('images/social_icons/instagram.svg') }}" alt="Instagram"
                                class="w-4 h-4 transition-transform transform hover:scale-110">
                        </a>
                        <a href="#" class="w-4 h-4 flex items-center justify-center">
                            <img src="{{ asset('images/social_icons/pinterest.svg') }}" alt="Pinterest"
                                class="w-4 h-4 transition-transform transform hover:scale-110">
                        </a>
                        <a href="#" class="w-4 h-4 flex items-center justify-center">
                            <img src="{{ asset('images/social_icons/twitter.svg') }}" alt="Twitter"
                                class="w-4 h-4 transition-transform transform hover:scale-110">
                        </a>
                        <a href="#" class="w-4 h-4 flex items-center justify-center">
                            <img src="{{ asset('images/social_icons/viber.svg') }}" alt="Viber"
                                class="w-4 h-4 transition-transform transform hover:scale-110">
                        </a>
                    </div>
                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const quantityInput = document.getElementById('quantity');
    const priceElement = document.querySelector('.text-2xl.font-semibold.mb-8');
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const unitPrice = {{ $product->price }};
    const maxStock = {{ (int) $product->stock }};
    let currentQuantity = 1;

    function updateQuantity(qty) {
        qty = parseInt(qty) || 1;
        qty = Math.max(1, qty);
        qty = Math.min(maxStock, qty);
        currentQuantity = qty;
        quantityInput.value = qty;
        const totalPrice = (unitPrice * qty).toFixed(2);
        priceElement.textContent = `$${totalPrice}`;
        decreaseBtn.disabled = qty <= 1;
        increaseBtn.disabled = qty >= maxStock;
        decreaseBtn.classList.toggle('opacity-50', qty <= 1);
        increaseBtn.classList.toggle('opacity-50', qty >= maxStock);
    }

    decreaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(currentQuantity - 1);
    });

    increaseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        updateQuantity(currentQuantity + 1);
    });

    quantityInput.addEventListener('input', function(e) {
        updateQuantity(e.target.value);
    });

    quantityInput.addEventListener('change', function(e) {
        if (e.target.value === '' || isNaN(e.target.value)) {
            e.target.value = 1;
            updateQuantity(1);
        }
    });

    // Add to Cart AJAX (if needed)
    addToCartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = this.getAttribute('data-product-id');
        const quantity = currentQuantity;
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ product_id: productId, quantity }),
        })
        .then(response => {
            if (!response.ok) {
                // Log HTTP errors
                console.error('HTTP error:', response.status, response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Log backend response
            console.log('Cart add response:', data);
            document.querySelectorAll('.cart-count').forEach(function(el) {
                el.textContent = data.count || 0;
            });
            if (document.querySelector('.cart-dropdown')) {
                document.querySelector('.cart-dropdown').innerHTML = data.preview;
            }
            // Optionally show error from backend
            if (data.error) {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            // Log JS/fetch errors
            console.error('Fetch error:', error);
            alert('A network or server error occurred. See console for details.');
        });
    });

    // Initialize
    updateQuantity(1);
});
</script>

<style>
    input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
</style>
@endsection
