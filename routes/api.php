<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArenaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\CourtTimetableController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\AuthSystemMiddleware;
use App\Http\Middleware\AuthSystemOrAdminMiddleware;
use App\Models\CourtTimetable;
use Illuminate\Support\Facades\Route;

Route::get('/status', fn() => json_response(['status' => "It's running"]));

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::apiResource('admins', AdminController::class)->middleware(AuthSystemMiddleware::class);
    Route::apiResource('customers', CustomerController::class)->middleware(AuthSystemMiddleware::class);

    Route::prefix('/arenas')->group(function () {
        Route::get('', [ArenaController::class, 'index'])->name('arenas.index');
        Route::get('/{arena}', [ArenaController::class, 'show'])->name('arenas.show');
        Route::get('/{arena}/courts', [ArenaController::class, 'courts'])->name('arenas.courts');

        Route::middleware(AuthSystemOrAdminMiddleware::class)->group(function () {
            Route::post('', [ArenaController::class, 'store'])->name('arenas.store');
            Route::put('/{arena}', [ArenaController::class, 'update'])->name('arenas.update');
            Route::delete('/{arena}', [ArenaController::class, 'destroy'])->name('arenas.destroy');
        });
    });

    Route::prefix('/courts')->group(function () {
        Route::get('', [CourtController::class, 'index'])->name('courts.index');
        Route::get('/{court}', [CourtController::class, 'show'])->name('courts.show');

        Route::middleware(AuthSystemOrAdminMiddleware::class)->group(function () {
            Route::post('', [CourtController::class, 'store'])->name('courts.store');
            Route::put('/{court}', [CourtController::class, 'update'])->name('courts.update');
            Route::delete('/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');            
        });

        Route::prefix('/{court}/timetables')->group(function() {
            Route::get('', [CourtTimetableController::class, 'index'])->name('timetables.index');
            
            Route::middleware(AuthSystemOrAdminMiddleware::class)->group(function () {
                Route::post('', [CourtTimetableController::class, 'store'])->name('timetables.store');
                Route::delete('/{timetable}', [CourtTimetableController::class, 'destroy'])->name('timetables.destroy');
            });
        });
    });
});