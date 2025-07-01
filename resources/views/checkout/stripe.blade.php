@extends('layouts.app2')

@section('title', 'Stripe Payment')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold mb-8">Complete Your Payment</h1>
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Test Mode Notice -->
            @if(config('services.stripe.key') && str_contains(config('services.stripe.key'), 'pk_test'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                <strong>Test Mode:</strong> You're using Stripe test mode. Use test card numbers like 4242 4242 4242 4242.
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Order Summary -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        @foreach($cart as $item)
                            <div class="flex justify-between">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                        <hr class="my-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (5%):</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="payment-form" class="space-y-4">
                    @csrf
                    
                    <!-- Billing Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Billing Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="billing_name" name="billing_name" 
                                       value="{{ session('checkout_data.billing_name', '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="billing_email" name="billing_email" 
                                       value="{{ session('checkout_data.billing_email', '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" id="billing_address" name="billing_address" 
                                   value="{{ session('checkout_data.billing_address', '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="billing_city" name="billing_city" 
                                       value="{{ session('checkout_data.billing_city', '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" id="billing_state" name="billing_state" 
                                       value="{{ session('checkout_data.billing_state', '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="billing_zip" class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                                <input type="text" id="billing_zip" name="billing_zip" 
                                       value="{{ session('checkout_data.billing_zip', '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" id="billing_phone" name="billing_phone" 
                                   value="{{ session('checkout_data.billing_phone', '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Payment Method</h3>
                        
                        <div id="card-element" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <!-- Stripe Elements will be inserted here -->
                        </div>
                        
                        <div id="card-errors" class="text-red-600 text-sm hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-200">
                        <span id="button-text">Pay ${{ number_format($total, 2) }}</span>
                        <div id="spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </form>

                <!-- Debug Information (remove in production) -->
                @if(config('app.debug'))
                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <h4 class="font-semibold text-sm mb-2">Debug Info:</h4>
                    <div class="text-xs space-y-1">
                        <div><strong>Stripe Key:</strong> {{ substr(config('services.stripe.key'), 0, 10) }}...</div>
                        <div><strong>Cart Items:</strong> {{ count($cart) }}</div>
                        <div><strong>Total:</strong> ${{ number_format($total, 2) }}</div>
                        <div><strong>Payment Intent:</strong> {{ $paymentIntent ?? 'Not created' }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });
    
    // Mount card element
    cardElement.mount('#card-element');
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const cardErrors = document.getElementById('card-errors');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Disable submit button
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        spinner.classList.remove('hidden');
        
        // Clear previous errors
        cardErrors.classList.add('hidden');
        cardErrors.textContent = '';
        
        try {
            // Create payment method
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: document.getElementById('billing_name').value,
                    email: document.getElementById('billing_email').value,
                    address: {
                        line1: document.getElementById('billing_address').value,
                        city: document.getElementById('billing_city').value,
                        state: document.getElementById('billing_state').value,
                        postal_code: document.getElementById('billing_zip').value,
                    },
                    phone: document.getElementById('billing_phone').value,
                },
            });
            
            if (error) {
                throw new Error(error.message);
            }
            
            // Send payment method to server
            const response = await fetch('{{ route("checkout.stripe.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    payment_method: paymentMethod.id,
                    billing_name: document.getElementById('billing_name').value,
                    billing_email: document.getElementById('billing_email').value,
                    billing_city: document.getElementById('billing_city').value,
                    billing_state: document.getElementById('billing_state').value,
                    billing_zip: document.getElementById('billing_zip').value,
                    billing_phone: document.getElementById('billing_phone').value,
                }),
            });
            
            // Check if response is OK
            if (!response.ok) {
                // Get the response text to see what we're actually getting
                const responseText = await response.text();
                console.error('Server response:', responseText);
                
                // Try to parse as JSON, but handle HTML responses
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    // If it's not JSON, it's probably an HTML error page
                    throw new Error(`Server error (${response.status}): ${response.statusText}. Please try again or contact support.`);
                }
                
                if (result.error) {
                    throw new Error(result.error);
                } else {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
            }
            
            // Try to parse JSON response
            let result;
            try {
                result = await response.json();
            } catch (parseError) {
                // If JSON parsing fails, get the raw response for debugging
                const responseText = await response.text();
                console.error('Failed to parse JSON response:', responseText);
                throw new Error('Invalid server response. Please try again.');
            }
            
            if (result.requires_action) {
                // Handle 3D Secure authentication
                const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(result.payment_intent_client_secret);
                
                if (confirmError) {
                    console.error('3D Secure confirmation error:', confirmError);
                    throw new Error(confirmError.message);
                }
                
                // Check if payment was successful after 3D Secure
                if (paymentIntent && paymentIntent.status === 'succeeded') {
                    console.log('3D Secure payment successful');
                    window.location.href = result.redirect;
                } else {
                    throw new Error('3D Secure authentication failed. Please try again.');
                }
            } else if (result.success) {
                // Payment successful
                console.log('Payment successful');
                window.location.href = result.redirect;
            } else {
                throw new Error(result.error || 'Payment failed');
            }
            
        } catch (error) {
            console.error('Payment error:', error);
            
            // Show error message
            cardErrors.textContent = error.message;
            cardErrors.classList.remove('hidden');
            
            // Re-enable submit button
            submitButton.disabled = false;
            buttonText.style.display = 'block';
            spinner.classList.add('hidden');
        }
    });
    
    // Handle card element errors
    cardElement.addEventListener('change', function(event) {
        if (event.error) {
            cardErrors.textContent = event.error.message;
            cardErrors.classList.remove('hidden');
        } else {
            cardErrors.classList.add('hidden');
        }
    });
});
</script>
@endsection 