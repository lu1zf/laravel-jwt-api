<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Middleware\API\v1\ProtectedRouteAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (){
    Route::post('login', [AuthController::class, 'login']);

    /*
     * Protected routes
     * */
    Route::middleware([ProtectedRouteAuth::class])->group(function(){
        Route::post('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
