<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user/profile', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    //Specific Route
    Route::get('/user/profile', [UserController::class, 'showProfile']);
    Route::match (['put', 'patch'], '/user/profile/{user}', [UserController::class, 'updateProfile']);

    //General Route
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('user', UserController::class);

});



