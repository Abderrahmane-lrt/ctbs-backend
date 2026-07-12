<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app_name'        => 'Cinema Ticket Booking System API (CTBS)',
        'status'          => 'Healthy',
        'environment'     => App::environment(),
        'api_version'     => 'v1.0.0',
        'php_version'     => phpversion(),
        'laravel_version' => App::version(),
        'message'         => 'Welcome to the CTBS Backend API. Please refer to the documentation or use frontend clients to interact with endpoints.'
    ], 200);
});