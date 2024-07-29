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
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
// Ensure API routes are protected
Route::middleware('auth:api')->group(function () {
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/productcategories', ProductCategoryController::class);
});


// Protect admin routes with both auth and admin middleware
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/manage-products', [ProductController::class, 'index']);
    Route::post('/newproduct', [ProductController::class, 'store']);
    Route::put('/updateproduct/{id}', [ProductController::class, 'update']);
    Route::delete('/deleteproduct/{id}', [ProductController::class, 'destroy']);
    // Add other admin routes here
});


