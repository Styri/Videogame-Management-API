<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\UserGameController;
use App\Http\Controllers\Game\GameReviewController;
use Illuminate\Support\Facades\Route;

// Auth routes (don't need auth middleware)
Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

// Protected routes (need auth middleware)
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('logout', LogoutController::class);

    // Games
    Route::apiResource('games', GameController::class);

    //GameReviews
    Route::get('games/{game}/reviews', [GameReviewController::class, 'index']);
    Route::post('games/{game}/reviews', [GameReviewController::class, 'store']);

    // User's personal games
    Route::get('my-games', [UserGameController::class, 'index']);
});
