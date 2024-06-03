<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CompanyAddressController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyMemberController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServicesContractController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketSolutionController;
use App\Http\Controllers\Api\TicketTaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    //Category Select List
    Route::get('/category/select', [CategoryController::class, 'getSelectList']);
    //Service Select List
    Route::get('/service/select', [ServiceController::class, 'getSelectList']);
    //Owner Select List
    Route::get('/user/owner/select', [UserController::class, 'getOwnerList']);
    //Requester Select List
    Route::get('/user/requester/select', [UserController::class, 'getRequesterList']);
    //Company Select List for specific Company
    Route::get('/company/{company}/member/select', [CompanyMemberController::class, 'getSelectList']);

    //View User Profile
    Route::get('/user/profile', [UserController::class, 'showProfile']);
    //Update User Profile
    Route::match(['put', 'patch'], '/user/profile', [UserController::class, 'updateProfile']);

    //Approve Ticket Solution By Manager
    Route::match(['put', 'patch'], '/ticket-solution/{ticketSolution}/approve', [TicketSolutionController::class, 'approve']);
    //Reject Ticket Solution By Manager
    Route::match(['put', 'patch'], '/ticket-solution/{ticketSolution}/reject', [TicketSolutionController::class, 'reject']);

    //Get Services Select List for Customer for requesting ticket
    Route::get('/ticket/available-service/{user}', [TicketController::class, 'getAvailableServices']);
    //Create Ticket by Customer
    Route::post('/ticket/customer', [TicketController::class, 'storeByCustomer']);
    //Update Ticket by Customer
    Route::match(['put', 'patch'], '/ticket/{ticket}/customer', [TicketController::class, 'updateByCustomer']);
    //Update Ticket Status
    Route::match(['put', 'patch'], '/ticket/{ticket}/update-status', [TicketController::class, 'updateStatus']);
    //Cancel Ticket
    Route::match(['put', 'patch'], '/ticket/{ticket}/customer-cancel', [TicketController::class, 'cancelTicket']);

    //Update Ticket Task status
    Route::match(['put', 'patch'], '/ticket/{ticket}/ticket-task/{ticketTask}/update-status', [TicketTaskController::class, 'updateStatus']);

    Route::get('/ticket/{ticket}/assign/technicians', [AssignmentController::class, 'getTechnicians']);
    Route::get('/ticket/{ticket}/assign', [AssignmentController::class, 'show']);
    Route::post('/ticket/{ticket}/assign', [AssignmentController::class, 'store']);
    Route::delete('/ticket/{ticket}/assign/{assign}', [AssignmentController::class, 'destroy']);

    Route::get('/ticket-solution/{ticketSolution}/react', [ReactionController::class, 'get']);
    Route::post('/ticket-solution/{ticketSolution}/react/like', [ReactionController::class, 'like']);
    Route::post('/ticket-solution/{ticketSolution}/react/dislike', [ReactionController::class, 'dislike']);

    Route::get('/company/select', [CompanyController::class, 'getSelectList']);

    Route::get('contract/{contract}/service/select', [ServicesContractController::class, 'getSelectList']);

    Route::get('/dashboard/manager', [DashboardController::class, 'managerDashboard']);
    Route::get('/dashboard/technician/{user}', [DashboardController::class, 'technicianDashboard']);
    Route::get('/dashboard/company-admin/{user}', [DashboardController::class, 'companyAdminDashboard']);
    Route::get('/dashboard/customer/{user}', [DashboardController::class, 'customerDashboard']);

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
    Route::apiResource('ticket', TicketController::class);
    Route::apiResource('ticket/{ticket}/ticket-task', TicketTaskController::class);
});
