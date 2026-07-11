<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Protected Route (Role Admin)
    Route::middleware('role:admin')->group(function () {
        //
    });

    Route::middleware('role:agent,admin')->group(function(){
        Route::post('/tickets/validate/{code}', [TicketController::class, 'validateTicket']);
    }); 

    Route::get('/my-tickets', [TicketController::class, 'myTickets']);
    Route::post('/tickets/buy', [TicketController::class, 'buyTicket']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
