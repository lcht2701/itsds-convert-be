<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TicketSolutionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    //Specific Route
    Route::get('/user/profile', [UserController::class, 'showProfile']);
    Route::match (['put', 'patch'], '/user/profile/{user}', [UserController::class, 'updateProfile']);
    Route::match (['put', 'patch'], '/user/profile/{user}', [UserController::class, 'updateProfile']);
    Route::match (['put', 'patch'], '/ticket-solution/{ticketSolution}/approve', [TicketSolutionController::class, 'approve']);
    Route::match (['put', 'patch'], '/ticket-solution/{ticketSolution}/reject', [TicketSolutionController::class, 'reject']);

    //General Route
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('service', ServiceController::class);
    Route::apiResource('ticket-solution', TicketSolutionController::class);

});



