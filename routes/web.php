<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;


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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
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

Route::prefix('exports')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Export\ProductExportController::class, 'export'])
        ->name('exports.products');

    Route::get('/products/queued', [\App\Http\Controllers\Export\ProductExportController::class, 'exportQueued'])
        ->name('exports.products.queued');
});


require __DIR__ . '/auth.php';