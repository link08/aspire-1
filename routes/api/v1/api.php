<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\LoginController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\LoanController;
use App\Http\Controllers\API\v1\EmiTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes (version: v1)
|--------------------------------------------------------------------------
|
| Here is where you can register API v1 routes for your loan application.
|
*/

// User Login Route
Route::post('/user/login', [LoginController::class, 'login'])->name('user.login');;

// User Register Route
Route::post('/user/register', [UserController::class, 'store'])->name('user.register');;

// Resource Routes
Route::middleware('auth:api')->group(function () {
    Route::apiResource('user', UserController::class)->only(['index', 'show']);
    Route::apiResource('loans', LoanController::class)->except('destroy');
    Route::group(['prefix' => 'loans/{loanId}'], function() {
        Route::apiResource('emis', EmiTransactionController::class)->except('destroy');
        Route::post('emis/{id}/payment', [EmiTransactionController::class, 'payment'])->name('emis.payment');
    });
});
