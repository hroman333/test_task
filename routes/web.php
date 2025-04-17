<?php

use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return auth()->check()
        ? to_route('employees.index')
        : to_route('login');
});

Route::get('login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('employees/data', [\App\Http\Controllers\EmployeeController::class, 'data'])
        ->name('employees.data');
    Route::get('/employees/search', [\App\Http\Controllers\EmployeeController::class, 'search'])
        ->name('employees.search');
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class)
        ->names(['index' => 'employees.index', 'data' => 'employees.data']);


    Route::get('positions/data', [\App\Http\Controllers\PositionController::class, 'data'])
        ->name('positions.data');
    Route::resource('positions', \App\Http\Controllers\PositionController::class);


});
