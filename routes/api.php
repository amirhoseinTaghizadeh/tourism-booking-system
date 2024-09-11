<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BookingController;


Route::prefix('/v1')->group(function (){
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('bookings', BookingController::class);
});

