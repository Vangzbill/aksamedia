<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeesController;

Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('guest');

Route::middleware('ensure.token')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('divisions', [DivisionController::class, 'index']);
    Route::get('employees', [EmployeesController::class, 'index']);
    Route::post('employees', [EmployeesController::class, 'store']);
    Route::put('employees/{uuid}', [EmployeesController::class, 'update']);
    Route::delete('employees/{uuid}', [EmployeesController::class, 'destroy']);
});
