@extends('layouts.app2')

@section('title', 'FAQ')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-3xl font-bold mb-8 text-center">Frequently Asked Questions</h1>
        <div class="space-y-6">
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">How do I place an order?</h2>
                <p>Browse our shop, add items to your cart, and proceed to checkout. Follow the on-screen instructions to complete your purchase.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">What payment methods do you accept?</h2>
                <p>We accept major credit cards, PayPal, and Stripe.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">How can I track my order?</h2>
                <p>After your order is shipped, you will receive a tracking link via email. You can also view your orders in your account dashboard.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">What is your return policy?</h2>
                <p>We offer a 30-day return policy on most items. Please visit our Returns page for more details.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">How do I contact support?</h2>
                <p>You can reach our support team via the Contact or Support pages for any assistance.</p>
            </div>
        </div>
    </div>
</section>
@endsection 