<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Redirect root to the dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/employees', function () {
        return view('pages.employees');
    })->name('employees');

    Route::get('/leaves', function () {
        return view('pages.leaves');
    })->name('leaves');

    Route::get('/payroll', function () {
        return view('pages.payroll');
    })->name('payroll');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include authentication routes
require __DIR__ . '/auth.php';
