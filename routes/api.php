<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function() {
    Route::prefix('/categories')->group(function() {
        Route::get('/', [HomeController::class, 'categories']);
    });

    Route::prefix('/recipes')->group(function() {
        Route::get('/', [HomeController::class, 'recipes']);
        Route::get('/{slug}', [HomeController::class, 'show']);
    });

    Route::get('/best-recipes', [HomeController::class, 'top']);

    Route::post('/register', [AuthController::class, 'register']);

});