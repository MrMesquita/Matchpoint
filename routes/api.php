<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArenaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\AuthAdminMiddleware;
use App\Http\Middleware\AuthSystemMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function() {
    Route::prefix('/admins')->middleware(AuthAdminMiddleware::class)->group(function() {
        Route::get('/arenas', [AdminController::class, 'arenas'])->name('admins.arenas');
        Route::post('/arenas', [AdminController::class, 'createArena'])->name('admins.createArena');
    });
    
    Route::middleware(AuthSystemMiddleware::class)->group(function() {
        Route::prefix('/admins')->group(function() {
            Route::get('', [AdminController::class, 'index'])->name('admins.index');
            Route::post('', [AdminController::class, 'store'])->name('admins.store');
            Route::get('/{admin}', [AdminController::class, 'show'])->name('admins.show');
            Route::put('/{admin}', [AdminController::class, 'update'])->name('admins.update');
            Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
        });
        
        Route::prefix('/customers')->group(function() {
            Route::get('', [CustomerController::class, 'index'])->name('customers.index');
            Route::post('', [CustomerController::class, 'store'])->name('customers.store');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        });
        
        Route::prefix('/arenas')->group(function () {
            Route::get('', [ArenaController::class, 'index'])->name('arenas.index');
            Route::post('/', [ArenaController::class, 'store'])->name('arenas.store');
            Route::get('/{arena}', [ArenaController::class, 'show'])->name('arenas.show');
            Route::put('/{arena}', [ArenaController::class, 'update'])->name('arenas.update');
            Route::delete('/{arena}', [ArenaController::class, 'destroy'])->name('arenas.destroy');
        });
    });
});


Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/status', function () {
    return json_response(['status' => "It's running"]);
});