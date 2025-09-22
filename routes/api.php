
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;



// Your existing route
Route::get('/get_item_details/{id}', [ApiController::class, 'getSerialDetails']);



