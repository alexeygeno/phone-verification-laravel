<?php

use Illuminate\Support\Facades\Route;

use AlexGeno\PhoneVerificationLaravel\Http\Controllers\PhoneVerificationController;

Route::post('/phone-verification/initiate', [PhoneVerificationController::class, 'initiate']);
Route::post('/phone-verification/complete', [PhoneVerificationController::class, 'complete']);
