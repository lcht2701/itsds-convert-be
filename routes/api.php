<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CompanyAddressController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyMemberController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServicesContractController;
use App\Http\Controllers\Api\TicketSolutionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    //Specific Route
    Route::get('/category/select', [CategoryController::class, 'getSelectList']);
    Route::get('/service/select', [ServiceController::class, 'getSelectList']);
    Route::get('/user/owner/select', [UserController::class, 'getOwnerList']);
    Route::get('/company/{company}/member/select', [CompanyMemberController::class, 'getSelectList']);

    Route::get('/user/profile', [UserController::class, 'showProfile']);
    Route::match(['put', 'patch'], '/user/profile', [UserController::class, 'updateProfile']);

    Route::match(['put', 'patch'], '/ticket-solution/{ticketSolution}/approve', [TicketSolutionController::class, 'approve']);
    Route::match(['put', 'patch'], '/ticket-solution/{ticketSolution}/reject', [TicketSolutionController::class, 'reject']);

    Route::get('/ticket-solution/{ticketSolution}/react', [ReactionController::class, 'get']);
    Route::post('/ticket-solution/{ticketSolution}/react/like', [ReactionController::class, 'like']);
    Route::post('/ticket-solution/{ticketSolution}/react/dislike', [ReactionController::class, 'dislike']);

    Route::get('/company/all', [CompanyController::class, 'all']);

    Route::get('contract/{contract}/service/available', [ServicesContractController::class, 'getAvailableServices']);

    //General Route
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('service', ServiceController::class);
    Route::apiResource('ticket-solution', TicketSolutionController::class);
    Route::apiResource('ticket-solution/{ticketSolution}/comment', CommentController::class);
    Route::apiResource('company', CompanyController::class);
    Route::apiResource('company/{company}/address', CompanyAddressController::class);
    Route::apiResource('company/{company}/member', CompanyMemberController::class);
    Route::apiResource('contract', ContractController::class);
    Route::apiResource('contract/{contract}/service', ServicesContractController::class);
});
