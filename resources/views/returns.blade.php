@extends('layouts.app2')

@section('title', 'Returns & Exchanges')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6 text-center">Returns & Exchanges</h1>
        <div class="bg-gray-50 p-8 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Our Return Policy</h2>
            <p class="mb-4">We want you to be completely satisfied with your purchase. If you are not happy with your order, you may return most items within 30 days of delivery for a full refund or exchange.</p>
            <ul class="list-disc pl-6 mb-4 text-gray-700">
                <li>Items must be unused and in their original packaging.</li>
                <li>Some items (e.g., final sale, personalized) may not be eligible for return.</li>
                <li>Return shipping costs are the responsibility of the customer unless the item is defective.</li>
            </ul>
            <h2 class="text-xl font-semibold mb-4">How to Start a Return</h2>
            <ol class="list-decimal pl-6 mb-4 text-gray-700">
                <li>Contact our support team via the Support page or email <strong>returns@company.com</strong>.</li>
                <li>Provide your order number and reason for return.</li>
                <li>We will provide instructions and a return shipping address.</li>
            </ol>
            <p class="text-gray-600">If you have any questions, please <a href="{{ route('contact') }}" class="text-[#ff0042] underline">contact us</a>.</p>
        </div>
    </div>
</section>
@endsection 