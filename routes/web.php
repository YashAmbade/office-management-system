<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('gmb')->name('gmb.')->group(function () {
    Route::view('/checklist', 'gmb.checklist')->name('checklist');
    Route::view('/clients', 'gmb.clients')->name('clients');
    Route::view('/categories', 'gmb.categories')->name('categories');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/all', [TaskController::class, 'allLog'])->name('tasks.all-log');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/work-log', [\App\Http\Controllers\WorkLogController::class, 'index'])->name('work-log.index');
    Route::get('/work-log/manage-clients', [\App\Http\Controllers\WorkLogController::class, 'manageClients'])->name('work-log.clients');

    Route::get('/reports/employees', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.employees');

});

Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

Route::get('/departments', [\App\Http\Controllers\DepartmentController::class, 'index'])->name('departments.index');

Route::get('/clients', [\App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');

Route::get('/clients/{client}', [\App\Http\Controllers\ClientController::class, 'show'])->name('clients.show');

// routes/web.php
Route::get('/work-log/report', [\App\Http\Controllers\WorkLogController::class, 'report'])->name('work-log.report');

Route::get('/settings/permissions', [\App\Http\Controllers\MenuPermissionController::class, 'index'])->name('settings.permissions');


Route::view('/settings/menu-items', 'settings.menu-items')->name('settings.menu-items')->middleware('auth');
