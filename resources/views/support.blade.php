@extends('layouts.app2')

@section('title', 'Support')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-3xl font-bold mb-6 text-center">Support</h1>
        <div class="bg-gray-50 p-8 rounded-lg shadow-md mb-8">
            <form method="POST" action="#">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Your Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="issue" class="block text-gray-700 font-semibold mb-2">Issue</label>
                    <textarea id="issue" name="issue" rows="5" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
                </div>
                <button type="submit" class="bg-[#ff0042] text-white px-6 py-2 rounded-full font-semibold hover:bg-opacity-90 transition">Submit Ticket</button>
            </form>
        </div>
        <div class="text-center text-gray-600">
            <p>For urgent issues, email us at <strong>support@company.com</strong> or call <strong>(123) 456-7890</strong>.</p>
        </div>
    </div>
</section>
@endsection 