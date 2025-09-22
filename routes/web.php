<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemsController;

// Redirect root URL to /admin
Route::redirect('/', '/admin');

// Your existing route
Route::get('/items', [ItemsController::class, 'index']);

