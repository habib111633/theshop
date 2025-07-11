<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -------------------
// AUTHENTICATION & USER MANAGEMENT
// -------------------
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) { return $request->user(); });
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
    Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
});

// -------------------
// PRODUCTS
// -------------------
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show']);
Route::get('/products/shop', [\App\Http\Controllers\ProductController::class, 'shop']);
Route::get('/products/filter', [\App\Http\Controllers\ProductController::class, 'ajaxFilter']);
Route::get('/products/{product}/public-detail', [\App\Http\Controllers\ProductController::class, 'publicDetail']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);
    // Media endpoints if implemented
    // Route::post('/products/{product}/media', [\App\Http\Controllers\ProductController::class, 'addMedia']);
    // Route::delete('/products/{product}/media/{media}', [\App\Http\Controllers\ProductController::class, 'removeMedia']);
});

// -------------------
// CATEGORIES
// -------------------
Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index']);
Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show']);
Route::get('/categories/{category}/products', [\App\Http\Controllers\CategoryController::class, 'products']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store']);
    Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy']);
});

// -------------------
// CART
// -------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add']);
    Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove']);
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear']);
});

// -------------------
// CHECKOUT & ORDERS
// -------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process']);
    Route::get('/orders', [\App\Http\Controllers\UserOrderController::class, 'index']);
    Route::get('/orders/{order}', [\App\Http\Controllers\UserOrderController::class, 'show']);
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\UserOrderController::class, 'cancel']);
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\UserOrderController::class, 'invoice']);
});
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/orders', [\App\Http\Controllers\OrderController::class, 'index']);
    Route::get('/admin/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::put('/admin/orders/{order}', [\App\Http\Controllers\OrderController::class, 'update']);
    Route::delete('/admin/orders/{order}', [\App\Http\Controllers\OrderController::class, 'destroy']);
});

// -------------------
// CONVERSATIONS & MESSAGES
// -------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/conversations', [\App\Http\Controllers\ConversationController::class, 'index']);
    Route::post('/conversations', [\App\Http\Controllers\ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}', [\App\Http\Controllers\ConversationController::class, 'show']);
    // If you have a MessageController:
    // Route::get('/conversations/{conversation}/messages', [\App\Http\Controllers\MessageController::class, 'index']);
    // Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\MessageController::class, 'store']);
});

// -------------------
// ROLES & PERMISSIONS (ADMIN)
// -------------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index']);
    Route::post('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store']);
    Route::put('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update']);
    Route::delete('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy']);
    // Permissions
    // Route::get('/permissions', [\App\Http\Controllers\PermissionController::class, 'index']);
});

// -------------------
// PROFILE
// -------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit']);
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy']);
});

// -------------------
// EXPORT (ADMIN)
// -------------------
Route::middleware(['auth:sanctum', 'role:admin'])->get('/products/export', [\App\Http\Controllers\Export\ProductExportController::class, 'export']);

// -------------------
// STRIPE WEBHOOK
// -------------------
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

// -------------------
// DASHBOARD (ADMIN)
// -------------------
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
