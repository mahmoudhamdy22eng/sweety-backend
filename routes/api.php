<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\adminController;

Route::group(['middleware' => ['api']], function () {
    // Authorization Routes
    Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize'])->middleware(['web', 'auth']);
    Route::post('/oauth/authorize', [AuthorizationController::class, 'approve'])->middleware(['web', 'auth']);
    Route::delete('/oauth/authorize', [AuthorizationController::class, 'deny'])->middleware(['web', 'auth']);

    // Token Routes
    Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
    Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);

    // Personal Access Token Routes
    Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store'])->middleware('auth:api');
    Route::get('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'index'])->middleware('auth:api');
    Route::delete('/oauth/personal-access-tokens/{tokenId}', [PersonalAccessTokenController::class, 'destroy'])->middleware('auth:api');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/products', [ProductController::class, 'index']);
// Route::post('/products', [ProductController::class, 'store']);
// Route::put('/products/{id}', [ProductController::class, 'update']);
// Route::get('/products/{id}', [ProductController::class, 'show']);


// Route::post('/register', [RegisterController::class, 'register']);
// Route::post('/login', [LoginController::class, 'login']);
// Route::middleware('/auth:api')->post('logout', [LoginController::class, 'logout']);


// Route::apiResource('/productcategories', ProductCategoryController::class);


Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [UserController::class, 'update']);
    
    Route::post('logout', [LoginController::class, 'logout']);

    

    Route::apiResource('admins', adminController::class);
    Route::post('admins/restore/{id}', [AdminController::class, 'restore']);
});
    // Public routes
    Route::put('/users/{id}', [UserController::class, 'update']); // Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user
    Route::put('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
    Route::post('/users', [UserController::class, 'store']); // Add user
    Route::get('/users', [UserController::class, 'index']); // Get all users
    Route::get('/users/{id}', [UserController::class, 'show']); // Get specific user



Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware('auth:api')->group(function () {
});

Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'getCartItems']);
Route::patch('/cart/update', [CartController::class, 'updateQuantity']);
Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart']);
Route::delete('/cart/clear', [CartController::class, 'clearCart']);

Route::post('/checkout/order', [CheckoutController::class, 'createOrder']);
Route::get('/checkout/shipping', [CheckoutController::class, 'getShippingInfo']);
Route::post('/checkout/shipping', [CheckoutController::class, 'updateShippingInfo']);
Route::get('/checkout/delivery-method', [CheckoutController::class, 'getDeliveryMethod']);
Route::post('/checkout/delivery-method', [CheckoutController::class, 'updateDeliveryMethod']);
Route::get('/checkout/payment-method', [CheckoutController::class, 'getPaymentMethod']);
Route::post('/checkout/payment-method', [CheckoutController::class, 'updatePaymentMethod']);


// Ensure API routes are protected
// Route::middleware('auth:api')->group(function () {
//     Route::apiResource('/products', ProductController::class);
//     Route::apiResource('/productcategories', ProductCategoryController::class);
// });
Route::get('/products', [ProductController::class, 'index']);
Route::get('/productcategories', [ProductCategoryController::class, 'index']);
Route::middleware('auth:api')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    // Route::put('/updatesweet/{id}', [ProductController::class, 'update']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
});



// Protect admin routes with both auth and admin middleware
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/manage-products', [ProductController::class, 'index']);
    Route::post('/newproduct', [ProductController::class, 'store']);
    Route::put('/updateproduct/{id}', [ProductController::class, 'update']);
    Route::delete('/deleteproduct/{id}', [ProductController::class, 'destroy']);
    // Add other admin routes here
});


