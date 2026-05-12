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

// 🛠️ SETUP ADMIN DARURAT (Hapus rute ini setelah berhasil login di server!)
Route::get('/setup-admin-isba', function () {
    try {
        // 1. PEMBERSIHAN TOTAL (Raw SQL - Tanpa Artisan agar aman dari error Termwind)
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Illuminate\Support\Facades\DB::table('members')->delete();
        \Illuminate\Support\Facades\DB::table('positions')->delete();
        \Illuminate\Support\Facades\DB::table('departments')->delete();
        \Illuminate\Support\Facades\DB::table('users')->delete();
        \Illuminate\Support\Facades\DB::table('roles')->delete();
        \Illuminate\Support\Facades\DB::table('permissions')->delete();
        \Illuminate\Support\Facades\DB::table('model_has_roles')->delete();
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->delete();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->delete();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. BUAT ULANG PERAN (Role) - Langsung ke Database
        $adminRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
            'name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()
        ]);
        \Illuminate\Support\Facades\DB::table('roles')->insert(['name' => 'chairman', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);
        \Illuminate\Support\Facades\DB::table('roles')->insert(['name' => 'member', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]);

        // 3. BUAT USER ADMIN
        $adminId = \Illuminate\Support\Facades\DB::table('users')->insertGetId([
            'name' => 'Super Admin ISBA',
            'email' => 'admin@isbajaya.org',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. PASANG ROLE KE ADMIN
        \Illuminate\Support\Facades\DB::table('model_has_roles')->insert([
            'role_id' => $adminRoleId,
            'model_type' => 'App\Models\User',
            'model_id' => $adminId
        ]);

        // 5. JALANKAN SEEDER DATA PENGURUS
        $seeder = new \Database\Seeders\InitialMemberSeeder();
        $seeder->run();

        return "<div style='font-family:sans-serif; padding:40px; text-align:center;'>
                    <h2 style='color:#980D0D;'>✅ DATABASE DIBERSIHKAN & ADMIN SIAP!</h2>
                    <p>Semua data lama sudah dihapus dan data baru sudah dimasukkan.</p>
                    <div style='background:#f4f4f4; padding:20px; display:inline-block; border-radius:10px; text-align:left;'>
                        Email: <b>admin@isbajaya.org</b><br>
                        Password: <b>password123</b>
                    </div><br><br>
                    <a href='/login' style='background:#980D0D; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Ke Halaman Login</a>
                </div>";
    } catch (\Exception $e) {
        return "❌ Gagal Total: " . $e->getMessage() . "<br><br>Saran: Pastikan folder 'storage' di server sudah di-CHMOD ke 775 atau 777.";
    }
});

// Rute cek data (Hapus nanti!)
Route::get('/debug-admin', function() {
    $user = \App\Models\User::where('email', 'admin@isbajaya.org')->first();
    if (!$user) return "❌ Akun admin@isbajaya.org TIDAK ditemukan di database.";
    
    $checkPass = \Illuminate\Support\Facades\Hash::check('password123', $user->password);
    return "✅ Akun Ditemukan! Nama: " . $user->name . " | Password password123: " . ($checkPass ? 'COCOK' : 'SALAH');
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
