<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminFacultyController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSemesterController;
use App\Http\Controllers\Admin\AdminStudyProgramController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\User\UserReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::resource('kelola-akun', AdminAccountController::class)
    ->middleware('admin')
    ->names([
        'index' => 'kelola-akun.index',
        'create' => 'kelola-akun.create',
        'store' => 'kelola-akun.store',
        'show' => 'kelola-akun.show',         
        'edit' => 'kelola-akun.edit',
        'update' => 'kelola-akun.update',
        'destroy' => 'kelola-akun.destroy',
    ]);

    Route::resource('kelola-fakultas', AdminFacultyController::class)
    ->middleware('admin')
    ->names([
        'index' => 'kelola-fakultas.index',
        'create' => 'kelola-fakultas.create',
        'store' => 'kelola-fakultas.store',
        'show' => 'kelola-fakultas.show',         
        'edit' => 'kelola-fakultas.edit',
        'update' => 'kelola-fakultas.update',
        'destroy' => 'kelola-fakultas.destroy',
    ]);

    Route::resource('semester', AdminSemesterController::class)
    ->middleware('admin')
    ->names([
        'index' => 'semester.index',
        'create' => 'semester.create',
        'store' => 'semester.store',
        'show' => 'semester.show',         
        'edit' => 'semester.edit',
        'update' => 'semester.update',
        'destroy' => 'semester.destroy',
    ]);

    Route::resource('kelola-program-studi', AdminStudyProgramController::class)
    ->middleware('admin')
    ->names([
        'index' => 'kelola-program-studi.index',
        'create' => 'kelola-program-studi.create',
        'store' => 'kelola-program-studi.store',
        'show' => 'kelola-program-studi.show',         
        'edit' => 'kelola-program-studi.edit',
        'update' => 'kelola-program-studi.update',
        'destroy' => 'kelola-program-studi.destroy',
    ]);
    
    Route::resource('kelola-laporan', AdminReportController::class)
    ->middleware('admin')
    ->names([
        'index' => 'kelola-laporan.index',
        'create' => 'kelola-laporan.create',
        'store' => 'kelola-laporan.store',
        'show' => 'kelola-laporan.show',         
        'edit' => 'kelola-laporan.edit',
        'update' => 'kelola-laporan.update',
        'destroy' => 'kelola-laporan.destroy',
    ]);
    
    Route::resource('laporan', UserReportController::class)
    ->middleware('admin')
    ->names([
        'index' => 'laporan.index',
        'create' => 'laporan.create',
        'store' => 'laporan.store',
        'show' => 'laporan.show',         
        'edit' => 'laporan.edit',
        'update' => 'laporan.update',
        'destroy' => 'laporan.destroy',
    ]);
});
