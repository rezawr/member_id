<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1/')->name('v1.')->group(function() {
    Route::controller(App\Http\Controllers\Api\V1\AuthController::class)->group(function() {
        Route::post('login', 'login')->name('login');
        Route::post('register', 'register')->name('register');
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::apiResources([
            'awards' => App\Http\Controllers\Api\V1\AwardController::class,
        ]);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
