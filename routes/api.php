<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;



Route::post('/receive-mail', [EmailController::class, 'receive']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
