<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\HR\AnnouncementController as HRAnnouncementController;
use App\Http\Controllers\HR\AttendanceController as HRAttendanceController;
use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\HR\EmployeeController as HREmployeeController;
use App\Http\Controllers\HR\LeaveController as HRLeaveController;
use App\Http\Controllers\HR\PayrollController as HRPayrollController;
use App\Http\Controllers\HR\UserController as HRUserController;
use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\LeaveController as StaffLeaveController;
use App\Http\Controllers\Staff\PayrollController as StaffPayrollController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Order Routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Routes (Analytics, Reviews, System Overview)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Categories (Admin only)
    Route::resource('categories', AdminCategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
});

// Product management: Admin and Uploaders
Route::prefix('admin')->middleware(['auth', 'role:admin,uploader'])->group(function () {
    Route::resource('products', AdminProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
});

// HR Routes (Staff & Uploader Management, Employee Management)
Route::prefix('hr')->middleware(['auth', 'hr'])->group(function () {
    Route::get('/dashboard', [HRDashboardController::class, 'index'])->name('hr.dashboard');

    // Staff and Uploader account management
    Route::resource('users', HRUserController::class)->names([
        'index' => 'hr.users.index',
        'create' => 'hr.users.create',
        'store' => 'hr.users.store',
        'edit' => 'hr.users.edit',
        'update' => 'hr.users.update',
        'destroy' => 'hr.users.destroy',
    ]);

    // Employee Management (with government records)
    Route::resource('employees', HREmployeeController::class)->names([
        'index' => 'hr.employees.index',
        'create' => 'hr.employees.create',
        'store' => 'hr.employees.store',
        'show' => 'hr.employees.show',
        'edit' => 'hr.employees.edit',
        'update' => 'hr.employees.update',
    ]);

    // Announcements
    Route::resource('announcements', HRAnnouncementController::class)->names([
        'index' => 'hr.announcements.index',
        'create' => 'hr.announcements.create',
        'store' => 'hr.announcements.store',
        'destroy' => 'hr.announcements.destroy',
    ]);

    // Leave Management
    Route::get('/leaves', [HRLeaveController::class, 'index'])->name('hr.leaves.index');
    Route::post('/leaves/{leaveRequest}/approve', [HRLeaveController::class, 'approve'])->name('hr.leaves.approve');
    Route::post('/leaves/{leaveRequest}/reject', [HRLeaveController::class, 'reject'])->name('hr.leaves.reject');

    // Attendance Management
    Route::get('/attendance', [HRAttendanceController::class, 'index'])->name('hr.attendance.index');
    Route::get('/attendance/{attendance}/edit', [HRAttendanceController::class, 'edit'])->name('hr.attendance.edit');
    Route::put('/attendance/{attendance}', [HRAttendanceController::class, 'update'])->name('hr.attendance.update');

    // Payroll
    Route::get('/payroll', [HRPayrollController::class, 'index'])->name('hr.payroll.index');
    Route::get('/payroll/create', [HRPayrollController::class, 'create'])->name('hr.payroll.create');
    Route::post('/payroll', [HRPayrollController::class, 'store'])->name('hr.payroll.store');
    Route::get('/payroll/{payrollRun}', [HRPayrollController::class, 'show'])->name('hr.payroll.show');
});

// Staff Routes (Employee Self-Service)
Route::prefix('staff')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    // Attendance (Time In/Out)
    Route::get('/attendance', [StaffAttendanceController::class, 'index'])->name('staff.attendance.index');
    Route::post('/attendance/time-in', [StaffAttendanceController::class, 'timeIn'])->name('staff.attendance.timeIn');
    Route::post('/attendance/time-out', [StaffAttendanceController::class, 'timeOut'])->name('staff.attendance.timeOut');

    // Leave Requests
    Route::get('/leaves', [StaffLeaveController::class, 'index'])->name('staff.leaves.index');
    Route::get('/leaves/create', [StaffLeaveController::class, 'create'])->name('staff.leaves.create');
    Route::post('/leaves', [StaffLeaveController::class, 'store'])->name('staff.leaves.store');

    // Payroll History
    Route::get('/payroll', [StaffPayrollController::class, 'index'])->name('staff.payroll.index');
    Route::get('/payroll/{payrollItem}', [StaffPayrollController::class, 'show'])->name('staff.payroll.show');
});
