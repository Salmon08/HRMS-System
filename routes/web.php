<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EmployeeLeaveController;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

Route::get('', function () {
    return Auth::check()
        ? redirect('/employees')   // if logged in
        : redirect('/login');      // if not logged in
});

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Employees: view list
    Route::middleware('permission:view employees')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index']);
    });

    // Employees: add/create
    Route::middleware('permission:add employees')->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create']);
        Route::post('/employees', [EmployeeController::class, 'store']);
    });

    // Employees: edit/update
    Route::middleware('permission:edit employees')->group(function () {
        Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    });

    // Employees: delete
    Route::middleware('permission:delete employees')->group(function () {
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    });

    // Leaves: view (add more leave routes as needed)
    Route::middleware('permission:view leaves')->group(function () {
        Route::get('/leaves', [EmployeeLeaveController::class, 'index']);
    });

    // Roles management
    Route::middleware('permission:manage roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });

    // Permissions management
    Route::middleware('permission:manage permissions')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{id}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{id}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Employee: profile (all roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/forget-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/firebase-login', [AuthController::class, 'firebaseLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
});

require __DIR__.'/auth.php';
