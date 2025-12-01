<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\DepartmentAccess;

Route::get('/playground', fn() => Inertia::render('Playground'))->name('playground');

Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', DepartmentAccess::class])->group(function () {
    Route::get('/hr-dashboard', fn() => Inertia::render('Departments/HR/Dashboard'))->name('hr.dashboard');
    Route::get('/budget-dashboard', fn() => Inertia::render('Departments/Budget/Dashboard'))->name('budget.dashboard');
    Route::get('/accounting-dashboard', fn() => Inertia::render('Departments/Accounting/Dashboard'))->name('accounting.dashboard');
    Route::get('/treasury-dashboard', fn() => Inertia::render('Departments/Treasury/Dashboard'))->name('treasury.dashboard');
});
