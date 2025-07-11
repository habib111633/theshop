
<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\DashboardController;
use App\Models\Category;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::match(['GET', 'POST'], '/shop/filter', [ProductController::class, 'ajaxFilter'])->name('shop.ajax');
Route::get('/product/{product}', [ProductController::class, 'publicDetail'])->name('product.detail');
Route::view('/contact', 'contact')->name('contact');
Route::view('/faq', 'faq')->name('faq');
Route::view('/support', 'support')->name('support');
Route::view('/returns', 'returns')->name('returns');



/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', fn() => view('cart'))->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/preview', [CartController::class, 'preview'])->name('cart.preview');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

/*
|--------------------------------------------------------------------------
| Product Stock/AJAX Routes
|--------------------------------------------------------------------------
*/
Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
Route::get('/product/{id}/available-stock', [ProductController::class, 'availableStock']);

/*
|--------------------------------------------------------------------------
| Checkout, Profile, and Conversations (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/thankyou/{order}', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');
    Route::get('/checkout/stripe', [CheckoutController::class, 'stripe'])->name('checkout.stripe');
    Route::post('/checkout/stripe/confirm', [CheckoutController::class, 'stripeConfirm'])->name('checkout.stripe.confirm');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User's own conversations
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage'])->name('conversations.messages.store');
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
});

/*
|--------------------------------------------------------------------------
| User-to-User Conversation Routes
|--------------------------------------------------------------------------
*/
Route::get('/users/{user}/conversations', [UserController::class, 'conversations'])->name('users.conversations');
Route::get('/users/{user}/conversations/{conversation}', [UserController::class, 'showConversation'])->name('users.conversation.show');
Route::get('/users/{user}/conversations/{conversation}/messages', [UserController::class, 'showMessages'])->name('users.conversation.messages');

/*
|--------------------------------------------------------------------------
| Stripe Webhook Endpoint
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

// Admin routes moved to routes/admin.php
// Customer routes moved to routes/customer.php

require __DIR__ . '/auth.php';

require __DIR__ . '/auth.php';
