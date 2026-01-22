<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\DashboardInventoryPdfController;

// Redirect root URL to /admin
Route::redirect('/', '/admin');

// Your existing route
Route::get('/items', [ItemsController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard-inventory.pdf', [DashboardInventoryPdfController::class, 'download'])
        ->name('dashboard.inventory.pdf');
});
