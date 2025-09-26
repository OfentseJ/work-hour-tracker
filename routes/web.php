<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/employees/create', [AdminController::class, 'createEmployee'])->name('admin.employees.create');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
    Route::post('/check-in', [AdminController::class, 'checkIn'])->name('admin.checkin');
    Route::post('/check-out', [AdminController::class, 'checkOut'])->name('admin.checkout');
    Route::get('/weekly-report', [AdminController::class, 'weeklyReport'])->name('admin.weekly-report');
});
