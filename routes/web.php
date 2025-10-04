<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminFacultyController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSemesterController;
use App\Http\Controllers\Admin\AdminStudyProgramController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
// Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Reports
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reports', ReportController::class);
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Account Management
    Route::resource('accounts', AdminAccountController::class);
    Route::patch('accounts/{user}/toggle-status', [AdminAccountController::class, 'toggleStatus'])->name('accounts.toggle-status');

    // Study Program Management
    Route::resource('study-programs', AdminStudyProgramController::class);
    Route::patch('study-programs/{study_program}/toggle-status', [AdminStudyProgramController::class, 'toggleStatus'])->name('study-programs.toggle-status');

    // Faculty Management
    Route::resource('faculties', AdminFacultyController::class);
    Route::patch('faculties/{faculty}/toggle-status', [AdminFacultyController::class, 'toggleStatus'])->name('faculties.toggle-status');

    // Semester Management
    Route::resource('semesters', AdminSemesterController::class);
    Route::patch('semesters/{semester}/toggle-status', [AdminSemesterController::class, 'toggleStatus'])->name('semesters.toggle-status');

    // Report Management
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::delete('reports/{report}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('reports/export/excel', [AdminReportController::class, 'exportExcel'])->name('reports.export.excel');
});

require __DIR__.'/auth.php';
