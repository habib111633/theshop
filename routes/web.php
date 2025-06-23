<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
return view('home');

})->name('home');

Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::match(['GET', 'POST'], '/shop/filter', [ProductController::class, 'ajaxFilter'])->name('shop.ajax');




Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

Route::get('/cart', function () {
    return view('cart');
})->name('cart');
Route::get('/product-detail', function () {
    return view('single-product-page');
})->name('product-detail');
Route::get('/product/{product}', [ProductController::class, 'publicDetail'])->name('product.detail');



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

// Admin resources

Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);

    Route::resource('products', ProductController::class);

// Admin-only export routes
Route::prefix('exports')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Export\ProductExportController::class, 'export'])
        ->name('exports.products');
    Route::get('/products/queued', [\App\Http\Controllers\Export\ProductExportController::class, 'exportQueued'])
        ->name('exports.products.queued');
});

Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
Route::get('/admin/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
Route::put('/admin/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

});

Route::get('/users/{user}/conversations', [UserController::class, 'conversations'])->name('users.conversations');
Route::get('/users/{user}/conversations/{conversation}', [UserController::class, 'showConversation'])->name('users.conversation.show');
Route::get('/users/{user}/conversations/{conversation}/messages', [UserController::class, 'showMessages'])->name('users.conversation.messages');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage'])->name('conversations.messages.store');
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');

});

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/preview', [CartController::class, 'preview'])->name('cart.preview');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// Route::get('/send-test-mail', function () {
//     Mail::raw('This is a test email from Laravel using Mailtrap.', function ($message) {
//         $message->to('your@email.com')
//             ->subject('Test Mail from Laravel');
//     });

//     return 'Test mail sent!';
// });

Route::get('/test-job1', function () {
    \App\Jobs\TestJob::dispatch();

    return 'Job has been dispatched! now check your logs to see if it was processed successfully.';
});

require __DIR__ . '/auth.php';
