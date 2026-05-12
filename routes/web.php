<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('chairman')) return redirect()->route('chairman.dashboard');
        return redirect()->route('member.profile');
    }
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// ── ADMIN ROUTES ──────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Data Anggota
    Route::get('/members', [MemberController::class, 'index'])
        ->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])
        ->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])
        ->name('members.store');
    Route::get('/members/{member}', [MemberController::class, 'show'])
        ->name('members.show');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])
        ->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])
        ->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])
        ->name('members.destroy');
    Route::post('/members/import', [MemberController::class, 'import'])
        ->name('members.import');
    Route::get('/members/export/excel', [MemberController::class, 'exportExcel'])
        ->name('members.export.excel');
    Route::get('/members/export/pdf', [MemberController::class, 'exportPdf'])
        ->name('members.export.pdf');

    // Verifikasi Status
    Route::get('/verification', [VerificationController::class, 'index'])
        ->name('verification.index');
    Route::post('/verification/{member}/update', [VerificationController::class, 'update'])
        ->name('verification.update');
    Route::post('/verification/bulk', [VerificationController::class, 'bulkUpdate'])
        ->name('verification.bulk');

    // Departemen & Jabatan
    Route::resource('/departments', DepartmentController::class)
        ->names('departments');
    Route::resource('/positions', PositionController::class)
        ->names('positions');
    Route::get('/positions/by-department/{department}', [PositionController::class, 'byDepartment'])
        ->name('positions.by-department');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])
        ->name('reports.generate');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export.pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])
        ->name('reports.export.excel');

    // Struktur Organisasi
    Route::get('/organization', [OrganizationController::class, 'index'])
        ->name('organization.index');

    // Berita Acara (Events)
    Route::resource('/events', EventController::class)
        ->names('events');
    Route::get('/events/{event}/pdf', [EventController::class, 'downloadPdf'])
        ->name('events.pdf');

    // Pengaturan Sistem
    Route::get('/settings', [UserController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings/users', [UserController::class, 'store'])
        ->name('settings.users.store');
    Route::put('/settings/users/{user}', [UserController::class, 'update'])
        ->name('settings.users.update');
    Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])
        ->name('settings.users.destroy');
    Route::post('/settings/users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('settings.users.reset-password');
    Route::post('/settings/roles/update', [UserController::class, 'updateRoles'])
        ->name('settings.roles.update');
});

// ── CHAIRMAN ROUTES ───────────────────────────────────────────
Route::middleware(['auth', 'role:chairman'])
    ->prefix('chairman')
    ->name('chairman.')
    ->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])
        ->name('reports.generate');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export.pdf');
    
    // Struktur Organisasi
    Route::get('/organization', [OrganizationController::class, 'chairmanView'])
        ->name('organization');
});

// ── MEMBER ROUTES ─────────────────────────────────────────────
Route::middleware(['auth'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Struktur Organisasi
    Route::get('/organization', [OrganizationController::class, 'memberView'])
        ->name('organization');
});
