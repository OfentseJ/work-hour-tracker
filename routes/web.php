<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeAuthController;

// Home/Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Login Selection Page
Route::get('/login', function() {
    return view('auth.login-select');
})->name('login');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Employee Authentication Routes
Route::get('/employee/login', [EmployeeAuthController::class, 'showLoginForm'])->name('employee.login');
Route::post('/employee/login', [EmployeeAuthController::class, 'login'])->name('employee.login.submit');
Route::post('/employee/logout', [EmployeeAuthController::class, 'logout'])->name('employee.logout');

// Admin Protected Routes
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

// Employee Protected Routes
Route::middleware(['auth:employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/check-in', [EmployeeController::class, 'checkIn'])->name('employee.checkin');
    Route::post('/check-out', [EmployeeController::class, 'checkOut'])->name('employee.checkout');
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('employee.attendance');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
});