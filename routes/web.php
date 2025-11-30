<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\TontineController;
use App\Http\Controllers\TontineSubscriptionController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routes authentifiées
Route::middleware(['auth'])->group(function () {

    // Suppliers routes
    Route::resource('suppliers', SupplierController::class);

    // Perfumes routes
    Route::resource('perfumes', PerfumeController::class);

    // Tontines routes
    Route::resource('tontines', TontineController::class);
    Route::post('tontines/{tontine}/activate', [TontineController::class, 'activate'])
        ->name('tontines.activate')
        ->middleware('admin');
    Route::post('tontines/{tontine}/cancel', [TontineController::class, 'cancel'])
        ->name('tontines.cancel')
        ->middleware('admin');
    Route::post('tontines/{tontine}/complete', [TontineController::class, 'complete'])
        ->name('tontines.complete')
        ->middleware('admin');

    // Tontine Subscriptions routes
    Route::get('subscriptions', [TontineSubscriptionController::class, 'index'])
        ->name('subscriptions.index');
    Route::get('tontines/{tontine}/subscribe', [TontineSubscriptionController::class, 'create'])
        ->name('subscriptions.create');
    Route::post('tontines/{tontine}/subscribe', [TontineSubscriptionController::class, 'store'])
        ->name('subscriptions.store');
    Route::get('subscriptions/{subscription}', [TontineSubscriptionController::class, 'show'])
        ->name('subscriptions.show');
    Route::post('subscriptions/{subscription}/cancel', [TontineSubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');

    // Cart routes
    Route::post('tontines/{tontine}/cart/add', [TontineSubscriptionController::class, 'addToCart'])
        ->name('subscriptions.cart.add');
    Route::patch('tontines/{tontine}/cart/{perfumeId}', [TontineSubscriptionController::class, 'updateCartItem'])
        ->name('subscriptions.cart.update');
    Route::delete('tontines/{tontine}/cart/{perfumeId}', [TontineSubscriptionController::class, 'removeFromCart'])
        ->name('subscriptions.cart.remove');
    Route::delete('tontines/{tontine}/cart', [TontineSubscriptionController::class, 'clearCart'])
        ->name('subscriptions.cart.clear');

    // Payments routes
    Route::get('payments', [PaymentController::class, 'index'])
        ->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])
        ->name('payments.show');
    Route::get('payments/{payment}/pay', [PaymentController::class, 'pay'])
        ->name('payments.pay');
    Route::post('payments/{payment}/pay', [PaymentController::class, 'processPay'])
        ->name('payments.process');

    // Multiple payments route
    Route::get('subscriptions/{subscription}/pay-multiple', [PaymentController::class, 'payMultiple'])
        ->name('payments.pay-multiple');
    Route::post('subscriptions/{subscription}/pay-multiple', [PaymentController::class, 'processPayMultiple'])
        ->name('payments.process-multiple');

    // Admin only payment routes
    Route::middleware('admin')->group(function () {
        Route::post('payments/{payment}/confirm', [PaymentController::class, 'adminConfirm'])
            ->name('payments.confirm');
        Route::post('payments/{payment}/cancel', [PaymentController::class, 'cancel'])
            ->name('payments.cancel');
        Route::post('payments/mark-late', [PaymentController::class, 'markAsLate'])
            ->name('payments.mark-late');
    });
});

require __DIR__.'/auth.php';
