<?php

use AlexGeno\PhoneVerificationLaravel\Http\Controllers\PhoneVerificationController;
use Illuminate\Support\Facades\Route;

Route::post('/phone-verification/initiate', [PhoneVerificationController::class, 'initiate']);
Route::post('/phone-verification/complete', [PhoneVerificationController::class, 'complete']);
