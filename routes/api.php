<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);
Route ::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products',[ProductController::class, 'store']);
Route ::put('/products/{id}', [ProductController::class, 'update']);
Route ::delete('/products/{id}',[ProductController::class,'destroy']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// route for user profile (authenticated users only)
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});
// route with role middeware (admin)
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin-test', function (Request $request) {
    return response()->json([
        'message' => 'Welcome Admin'
    ]);
});

// route with role middleware (user)
Route::middleware(['auth:sanctum', 'role:user'])->get('/user-test', function (Request $request) {
    return response()->json([
        'message' => 'Welcome User'
    ]);
});

// route with role middleware (multiple roles)
Route::middleware(['auth:sanctum', 'role:admin,manager'])->get('/staff-test', function (Request $request) {
    return response()->json([
        'message' => 'Welcome Staff'
    ]);
});

// route to get all users (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->get('/users', [UserController::class, 'index']);

// route to get user by id (admin only)
// Route::middleware(['auth:sanctum', 'role:admin'])->get('/users/{id}', [UserController::class, 'show']);
Route::middleware(['auth:sanctum','role:admin,manager'])->get('/users/{id} ',[UserController::class, 'show']);

// route to update user by id (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->put('/users/{id}', [UserController::class, 'update']);

// route to delete user by id  (admin only)
Route::middleware(['auth:sanctum', 'role:admin'])->delete('/users/{id}', [UserController::class, 'destroy']);