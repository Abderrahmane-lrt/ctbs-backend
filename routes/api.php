<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Routes

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show']);

Route::get('/showtimes', [ShowtimeController::class, 'index']);
Route::get('/showtimes/{showtime}', [ShowtimeController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Protected Route (Role Admin)
    Route::middleware('role:admin')->group(function () {
        // Routes Movies
        Route::post('/movies/create', [MovieController::class, 'store']);
        Route::put('/movies/{movie}', [MovieController::class, 'update']);
        Route::delete('/movies/{movie}', [MovieController::class, 'destroy']);

        // Routes Showtimes
        Route::post('/showtimes', [ShowtimeController::class, 'store']);
        Route::put('/showtimes/{showtime}', [ShowtimeController::class, 'update']);
        Route::delete('/showtimes/{showtime}', [ShowtimeController::class, 'destroy']);
    });

    Route::middleware('role:agent,admin')->group(function () {
        Route::post('/tickets/validate/{code}', [TicketController::class, 'validateTicket']);
    });

    Route::get('/my-tickets', [TicketController::class, 'myTickets']);

    Route::post('/tickets/buy', [TicketController::class, 'buyTicket']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
