@extends('layouts.app2')

@section('title', 'home')

@section('content')
<!-- Slider -->
<section id="product-slider">
    <div class="main-slider swiper-container">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <img src="{{ asset('images/main-slider/5.jpg') }}" alt="Product 1">
                <div class="swiper-slide-content">
                    <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Women</h2>
                    <p class="mb-4 text-white md:text-2xl">Experience the best in sportswear with <br>our latest
                        collection.</p>
                    <a href="{{ route('shop') }}"
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                        now</a>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <img src="{{ asset('images/main-slider/2.png') }}" alt="Product 2">
                <div class="swiper-slide-content">
                    <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Men</h2>
                    <p class="mb-4 text-white md:text-2xl">Discover the latest trends in Men`s <br>sportswear and
                        casual fashion.</p>
                    <a href="{{ route('shop') }}"
                        class="bg-white hover:bg-transparent text-black hover:text-white font-semibold px-4 py-2 rounded-full inline-block border border-transparent hover:border-white">Shop
                        now</a>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <img src="{{ asset('images/main-slider/4.jpg') }}" alt="Product 3">
                <div class="swiper-slide-content">
                    <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">Accessories</h2>
                    <p class="mb-4 text-white md:text-2xl">Elevate your style with our latest <br>sportswear
                        collection.</p>
                    <a href="{{ route('shop') }}"
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                        now</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Slider Pagination -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <style>
    .swiper-slide img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        display: block;
        position: relative;
        filter: brightness(60%);

    }

    .slider-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        /* Adjust opacity as needed */
        z-index: 1;
        border-radius: inherit;
    }

    .swiper-slide-content {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 2;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 100%;
        padding: 0 2rem;
    }
    </style>
    <script>
    // ...existing code...
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.main-slider', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            effect: 'slide',
        });
    });
    // ...existing code...
    </script>
</section>

@if($categories->count() > 0)
<!-- Dynamic Category Products Section -->
@foreach($categories as $category)
<section id="category-{{ $category->id }}-products" class="py-10">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold">{{ $category->name }} Products</h2>
            <a href="{{ route('shop') }}" class="text-[#ff0042] hover:underline font-semibold">View All →</a>
        </div>
        <div class="flex flex-wrap -mx-4">
            @foreach($category->products as $product)
            <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                <div class="bg-white p-3 rounded-lg shadow-lg">
                    @if($product->media->first())
                        <img src="{{ asset('storage/' . $product->media->first()->path) }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-cover mb-4 rounded-lg">
                    @else
                        <img src="{{ asset('images/products/1.jpg') }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-cover mb-4 rounded-lg">
                    @endif
                    <a href="{{ route('product.detail', $product) }}" class="text-lg font-semibold mb-2 block hover:text-[#ff0042]">{{ $product->name }}</a>
                    <p class="my-2 text-gray-600">{{ $category->name }}</p>
                    <div class="flex items-center mb-4">
                        <span class="text-lg font-bold text-[#ff0042]">{{ $product->formatted_price }}</span>
                    </div>
                    <button onclick="addToCart({{ $product->id }})"
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px] w-full text-center block">
                        Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach
@endif

<!-- Banner section -->
<section id="banner" class="relative my-16">
    <div class="container mx-auto px-4 py-20 rounded-lg relative bg-cover bg-center"
        style="background-image: url('{{ asset('images/banner1.jpg')}}');">
        <div class="absolute inset-0 bg-black opacity-40 rounded-lg"></div>
        <div class="relative flex flex-col items-center justify-center h-full text-center text-white py-20">
            <h2 class="text-4xl font-bold mb-4">Welcome to Our Shop</h2>
            <div class="flex space-x-4">
                <a href="{{ route('shop') }}"
                    class="bg-[#ff0042] border border-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] font-semibold px-4 py-2 rounded-full inline-block">Shop
                    Now</a>
                <a href="{{ route('shop') }}"
                    class="bg-[#ff0042] border border-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] font-semibold px-4 py-2 rounded-full inline-block">New
                    Arrivals</a>
                <a href="{{ route('shop') }}"
                    class="bg-[#ff0042] border border-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] font-semibold px-4 py-2 rounded-full inline-block">Sale</a>
            </div>
        </div>
    </div>
</section>

<!-- Subscribe section -->
<section id="subscribe" class="py-6 lg:py-24 bg-white border-t border-gray-line">
    <div class="container mx-auto">
        <div class="flex flex-col items-center rounded-lg p-4 sm:p-0 ">
            <div class="mb-8">
                <h2 class="text-center text-xl font-bold sm:text-2xl lg:text-left lg:text-3xl">Join our
                    newsletter
                    and <span class="text-[#ff0042]">get $50 discount</span> for your first order
                </h2>
            </div>
            <div class="flex flex-col items-center w-96 ">
                <form class="flex w-full gap-2">
                    <input placeholder="Enter your email address"
                        class="w-full flex-1 rounded-full px-3 py-2 border border-gray-300 text-gray-700 placeholder-gray-500 focus:outline-none focus:border-[#ff0042] focus:ring-2 focus:ring-[#ff0042]" />
                    <button
                        class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            }
            alert('Product added to cart successfully!');
        } else {
            alert('Failed to add product to cart: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding to cart');
    });
}
</script>
@endsection
