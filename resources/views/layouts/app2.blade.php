<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="icon" href="{{ asset('images/favicon.png') }}" />
    <title>@yield('title', 'Home Page')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Assets -->
    <!-- Swiper CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <!-- ...your other CSS files... -->

    <!-- Swiper JS CDN (place before your custom JS that initializes Swiper) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">

    <!-- Vite for JS/CSS compilation (if using) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/script.js') }}"></script>
</head>

<body>
    <!-- Header -->
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <!-- Left section: Logo -->
            <a href="index.html" class="flex items-center">
                <div>
                    <img src="{{ asset('images/template-white-logo.png') }}" alt="Logo" class="h-14 w-auto mr-4">
                </div>
            </a>

            <!-- Hamburger menu (for mobile) -->
            <div class="flex lg:hidden">
                <button id="hamburger" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Center section: Menu -->
            <nav class="hidden lg:flex md:flex-grow justify-center">
                <ul class="flex justify-center space-x-4 text-white">
                    <li><a href="{{ route('home') }}" class="hover:text-[#ff0042] font-semibold">Home</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-[#ff0042] font-semibold">Shop</a></li>

                    <li class="relative group">
                        <button class="hover:text-[#ff0042] font-semibold focus:outline-none">Help
                            <svg class="w-3 h-3 ml-1 transition-transform group-hover:rotate-180 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <!-- Dropdown -->
                        <ul class="absolute left-0 mt-0 w-40 bg-white text-gray-800 rounded shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition z-50">
                            <li><a href="{{ route('faq') }}" class="block px-4 py-2 hover:bg-gray-100 transition">FAQ</a></li>
                            <li><a href="{{ route('support') }}" class="block px-4 py-2 hover:bg-gray-100 transition">Support</a></li>
                            <li><a href="{{ route('returns') }}" class="block px-4 py-2 hover:bg-gray-100 transition">Returns</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#ff0042] font-semibold">Contact</a></li>
                    <li><a href="{{ route('checkout') }}" class="hover:text-[#ff0042] font-semibold">Checkout</a></li>
                </ul>
            </nav>

            <!-- Right section: Buttons (for desktop) -->
            <div class="hidden lg:flex items-center space-x-4 relative">
  @guest
                <!-- Register/Login Buttons for guests -->
                <a href="{{ route('register') }}"
                    class="bg-[#ff0042] border border-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] font-semibold px-4 py-2 rounded-full">Register</a>
                <a href="{{ route('login') }}"
                    class="bg-[#ff0042] border border-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] font-semibold px-4 py-2 rounded-full">Login</a>
            @else
               <!-- User Profile Dropdown -->
<div class="relative dropdown-container">
    <!-- Dropdown Button -->
    <button class="dropdown-btn flex items-center space-x-2 text-white font-semibold hover:text-[#ff0042] focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-8 w-8 text-white rounded-full bg-gray-600 p-1"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>
        <span>{{ auth()->user()->name }}</span>
        <svg class="dropdown-chevron w-4 h-4 transition-transform duration-200"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-100">
        <div class="py-1 text-center">
            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                Profile
            </a>

            @if(auth()->check() && auth()->user()->hasRole('customer'))
                <a href="{{ route('customer.orders.index') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                    My Orders
                </a>
                <a href="{{ route('conversations.index') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                     Messages
                </a>
            @endif
            @can('view dashboard')
         <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            Dashboard
        </a>

        @endcan
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .dropdown-btn:focus + .dropdown-menu,
    .dropdown-menu:hover {
        display: block;
    }
    .dropdown-menu {
        display: none;
    }
    .dropdown-container.open .dropdown-menu {
        display: block;
    }
    .dropdown-container.open .dropdown-chevron {
        transform: rotate(180deg);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown-container');

    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Close when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.remove('open');
            }
        });
    });
});
</script>
            @endguest

                <div class="relative group cart-wrapper">
                    <a href="{{ route('cart') }}">
                        <img src="{{ asset('images/cart-shopping.svg') }}" alt="Cart"
                            class="h-6 w-6 group-hover:scale-120">
                        <span class="cart-count absolute -top-2 -right-2 bg-[#ff0042] text-white text-xs font-bold rounded-full px-2 py-0.5">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>
                    </a>
                    <!-- Cart dropdown -->
                    <div
                        class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded cart-dropdown hidden group-hover:block">
                        @include('partials.cart-preview', ['cart' => session('cart', [])])
                    </div>
                </div>
                <!-- <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-[#ff0042] group">
                    <img src="{{ asset('images/search-icon.svg') }}" alt="Search"
                        class="h-6 w-6 transition-transform transform group-hover:scale-120">
                </a> -->
                <!-- Search field -->
                <div id="search-field"
                    class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                    <input type="text" class="w-full p-2 border border-gray-300 rounded"
                        placeholder="Search for products...">
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <nav id="mobile-menu-placeholder" class="mobile-menu hidden flex-col items-center space-y-8 lg:hidden">
        <ul class="w-full">
            <li><a href="{{ route('home') }}" class="hover:text-[#ff0042] font-bold block py-2">Home</a></li>
            <li><a href="{{ route('shop') }}" class="hover:text-[#ff0042] font-bold block py-2">Shop</a></li>
            @if(auth()->check() && auth()->user()->hasRole('customer'))
                <li><a href="{{ route('customer.orders.index') }}" class="hover:text-[#ff0042] font-bold block py-2">My Orders</a></li>
                <li><a href="{{ route('conversations.index') }}" class="hover:text-[#ff0042] font-bold block py-2">Messages</a></li>
            @endif
            <li><a href="{{ route('checkout') }}" class="hover:text-[#ff0042] font-bold block py-2">Checkout</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-[#ff0042] font-bold block py-2">Contact</a></li>
            <li><a href="{{ route('faq') }}" class="hover:text-[#ff0042] font-bold block py-2">FAQ</a></li>
            <li><a href="{{ route('support') }}" class="hover:text-[#ff0042] font-bold block py-2">Support</a></li>
            <li><a href="{{ route('returns') }}" class="hover:text-[#ff0042] font-bold block py-2">Returns</a></li>
        </ul>
        <div class="flex flex-col mt-6 space-y-2 items-center">
            <a href="{{ route('register') }}"
                class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Register</a>
            <a href="{{ route('login') }}"
                class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Login</a>
            <a href="register.html"
                class="bg-[#ff0042] hover:bg-transparent text-white hover:text-[#ff0042] border border-[#ff0042] font-semibold px-4 py-2 rounded-full flex items-center justify-center min-w-[110px]">Cart
                -&nbsp;<span>5</span>&nbsp;items</a>
        </div>
        <!-- Search field -->
        <div class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
            <input type="text" class="w-full p-2 border border-gray-300 rounded" placeholder="Search for products...">
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="border-t border-gray-line">
        <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-wrap -mx-4">
                <!-- Menu 1 -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Shop</h3>
                    <ul>
                        <li><a href="/shop.html" class="hover:text-[#ff0042]">Shop</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-[#ff0042]">Women</a></li>
                        <li><a href="/shop.html" class="hover:text-[#ff0042]">Men</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-[#ff0042]">Shoes</a></li>
                        <li><a href="/single-product-page.html" class="hover:text-[#ff0042]">Accessories</a></li>
                    </ul>
                </div>
                <!-- Menu 2 -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Pages</h3>
                    <ul>
                        <li><a href="{{ route('shop') }}" class="hover:text-[#ff0042]">Shop</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-[#ff0042]">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-[#ff0042]">Contact</a></li>
                        <li><a href="/checkout.html" class="hover:text-[#ff0042]">Checkout</a></li>
                        <li><a href="/404.html" class="hover:text-[#ff0042]">404</a></li>
                    </ul>
                </div>
                <!-- Menu 3 -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Account</h3>
                    <ul>
                        <li><a href="/cart.html" class="hover:text-[#ff0042]">Cart</a></li>
                        <li><a href="{{ route('register')}}" class="hover:text-[#ff0042]">Registration</a></li>
                        <li><a href="{{ route('login')}}" class="hover:text-[#ff0042]">Login</a></li>
                    </ul>
                </div>
                <!-- Social Media -->
                <div class="w-full sm:w-1/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <ul>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('images/social_icons/facebook.svg') }}" alt="Facebook"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-[#ff0042]">Facebook</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('images/social_icons/twitter.svg') }}" alt="Twitter"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-[#ff0042]">Twitter</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('images/social_icons/instagram.svg') }}" alt="Instagram"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-[#ff0042]">Instagram</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('images/social_icons/pinterest.svg') }}" alt="Pinterest"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-[#ff0042]">Pinterest</a>
                        </li>
                        <li class="flex items-center mb-2">
                            <img src="{{ asset('images/social_icons/youtube.svg') }}" alt="YouTube"
                                class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                            <a href="#" class="hover:text-[#ff0042]">YouTube</a>
                        </li>
                    </ul>
                </div>
                <!-- Contact Information -->
                <div class="w-full sm:w-2/6 px-4 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <p><img src="{{ asset('images/template-logo.png') }}" alt="Logo" class="h-[60px] mb-4"></p>
                    <p>123 Street Name, Paris, France</p>
                    <p class="text-xl font-bold my-4">Phone: (123) 456-7890</p>
                    <a href="mailto:info@company.com" class="underline">Email: info@company.com</a>
                </div>
            </div>
        </div>

        <!-- Bottom part -->
        <div class="py-6 border-t border-gray-line">
            <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
                <!-- Copyright and Links -->
                <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
                    <p class="mb-2 font-bold">&copy; 2024 Your Company. All rights reserved.</p>
                    <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
                        <li><a href="#" class="hover:text-[#ff0042]">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-[#ff0042]">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-[#ff0042]">FAQ</a></li>
                    </ul>
                    <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to
                        your shop
                        and what you offer.</p>
                </div>
                <!-- Payment Icons -->
                <div class="w-full lg:w-1/4 text-center lg:text-right">
                    <img src="{{ asset('images/social_icons/paypal.svg')}}" alt="PayPal" class="inline-block h-8 mr-2">
                    <img src="{{ asset('images/social_icons/stripe.svg')}}" alt="Stripe" class="inline-block h-8 mr-2">
                    <img src="{{ asset('images/social_icons/visa.svg')}}" alt="Visa" class="inline-block h-8">
                </div>
            </div>
        </div>
    </footer>

    <script>
   document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        let productId = this.getAttribute("data-product-id");
        let quantity = this.getAttribute("data-quantity") || 1;

        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="animate-spin">↻</span> Adding...';

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            // Update cart preview HTML
            if (data.preview) {
                document.querySelector(".cart-dropdown").innerHTML = data.preview;
                document.querySelector(".cart-wrapper").classList.add("open");
                setTimeout(() => {
                    document.querySelector(".cart-wrapper").classList.remove("open");
                }, 3000);
            }
            // Update cart count badge
            if (data.count) {
                document.querySelector(".cart-count").textContent = data.count;
            }
         })
        .catch(error => {
            alert('A network or server error occurred. See console for details.');
            console.error("Error:", error);
        })
        .finally(() => {
            this.disabled = false;
            this.textContent = "Add to Cart";
        });
    });
});
    </script>
    </script>
    <script src="node_modules/swiper/swiper-bundle.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="{{ asset('js/script.js') }}"></script>

</body>

</html>
