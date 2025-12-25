<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PerfumeController;
use App\Http\Controllers\API\TontineController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (Authentication)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Perfumes routes
    Route::get('/perfumes', [PerfumeController::class, 'index']);
    Route::get('/perfumes/{id}', [PerfumeController::class, 'show']);

    // Tontines routes
    Route::get('/tontines', [TontineController::class, 'index']);
    Route::get('/tontines/{id}', [TontineController::class, 'show']);

    // Cart routes (associated with a tontine)
    Route::post('/tontines/{tontineId}/cart/add', [SubscriptionController::class, 'addToCart']);
    Route::put('/tontines/{tontineId}/cart/{perfumeId}', [SubscriptionController::class, 'updateCartItem']);
    Route::delete('/tontines/{tontineId}/cart/{perfumeId}', [SubscriptionController::class, 'removeFromCart']);
    Route::get('/tontines/{tontineId}/cart', [SubscriptionController::class, 'getCart']);
    Route::delete('/tontines/{tontineId}/cart', [SubscriptionController::class, 'clearCart']);

    // Subscription routes
    Route::post('/tontines/{tontineId}/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
    Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments/{id}/pay', [PaymentController::class, 'pay']);
    Route::get('/subscriptions/{subscriptionId}/payments', [PaymentController::class, 'getPaymentsBySubscription']);

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
