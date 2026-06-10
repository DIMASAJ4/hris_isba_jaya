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

// 🛠️ GENERATE SEMUA AKUN ANGGOTA
Route::get('/generate-all-accounts', function () {
    $members = \App\Models\Member::whereNull('user_id')->get();
    
    if ($members->isEmpty()) {
        return "<h2 style='color:#980D0D; font-family:sans-serif;'>Semua anggota sudah memiliki akun!</h2>";
    }

    $output = "<h2 style='color:#980D0D; font-family:sans-serif;'>✅ AKUN BERHASIL DIBUAT</h2>";
    $output .= "<p style='font-family:sans-serif;'>Silakan copy data berikut (Role disesuaikan dengan instruksi):</p>";
    $output .= "<table border='1' cellpadding='10' style='border-collapse: collapse; font-family:sans-serif;'>";
    $output .= "<tr style='background:#f4f4f4;'><th>Nama</th><th>Email</th><th>Password</th><th>Role</th></tr>";

    foreach ($members as $member) {
        $email = strtolower(str_replace(' ', '.', $member->full_name)) . '@isbajaya.org';
        
        // Pastikan email unik
        $baseEmail = $email;
        $counter = 1;
        while (\App\Models\User::where('email', $email)->exists()) {
            $email = str_replace('@isbajaya.org', $counter . '@isbajaya.org', $baseEmail);
            $counter++;
        }

        $password = 'isbajaya' . date('Y');

        $user = \App\Models\User::create([
            'name' => $member->full_name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
        ]);

        // Tentukan Role
        $roleName = 'member'; // Default
        if ($member->full_name === 'Rangga Pratama Yudha') {
            $roleName = 'chairman';
        } elseif ($member->full_name === 'Fitria Firdawati') {
            $roleName = 'admin';
        }

        $user->assignRole($roleName);
        $member->update(['user_id' => $user->id]);

        $output .= "<tr><td>{$member->full_name}</td><td><b>{$email}</b></td><td><b>{$password}</b></td><td><span style='text-transform:uppercase;'>{$roleName}</span></td></tr>";
    }

    $output .= "</table>";
    return $output;
});

// 🛠️ PERBAIKI ROLE PENGURUS INTI
Route::get('/fix-roles', function () {
    $rangga = \App\Models\Member::where('full_name', 'Rangga Pratama Yudha')->first();
    if ($rangga && $rangga->user) {
        $rangga->user->syncRoles(['chairman']);
    }

    $fitria = \App\Models\Member::where('full_name', 'Fitria Firdawati')->first();
    if ($fitria && $fitria->user) {
        $fitria->user->syncRoles(['admin']);
    }

    return "<h2 style='color:#980D0D; font-family:sans-serif;'>✅ ROLE TELAH DIPERBAIKI!</h2>
            <p style='font-family:sans-serif;'>Rangga -> CHAIRMAN <br> Fitria -> ADMIN</p>
            <p style='font-family:sans-serif;'>Silakan Fitria/Rangga logout dulu lalu login kembali.</p>";
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

    // Absensi (Attendance)
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{event}/manage', [\App\Http\Controllers\Admin\AttendanceController::class, 'manage'])->name('attendance.manage');
    Route::post('/attendance/{event}/toggle', [\App\Http\Controllers\Admin\AttendanceController::class, 'toggleAttendance'])->name('attendance.toggle');
    Route::put('/attendance/{event}/bulk', [\App\Http\Controllers\Admin\AttendanceController::class, 'updateBulk'])->name('attendance.updateBulk');
    Route::get('/attendance/{event}/pdf', [\App\Http\Controllers\Admin\AttendanceController::class, 'exportPdf'])->name('attendance.pdf');
    Route::get('/attendance/{event}/excel', [\App\Http\Controllers\Admin\AttendanceController::class, 'exportExcel'])->name('attendance.excel');

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

    // Absensi (Attendance)
    Route::get('/attendance', [\App\Http\Controllers\Member\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/{event}/checkin', [\App\Http\Controllers\Member\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/{event}/permit', [\App\Http\Controllers\Member\AttendanceController::class, 'submitStatus'])->name('attendance.permit');
});

// 🛠️ MIGRATION ROUTE FOR PRODUCTION (InfinityFree)
Route::get('/migrate-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "<h2 style='color:#980D0D; font-family:sans-serif;'>✅ MIGRATION SUCCESSFUL!</h2><pre style='background:#f4f4f4; padding:15px; border-radius:8px;'>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "<h2 style='color:red; font-family:sans-serif;'>❌ MIGRATION FAILED!</h2><p style='font-family:sans-serif;'>" . $e->getMessage() . "</p>";
    }
});

// 🛠️ SEED ADMIN USERS IN PRODUCTION
Route::get('/seed-admins', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'AdminUsersSeeder',
            '--force' => true
        ]);
        return "<h2 style='color:#980D0D; font-family:sans-serif;'>✅ ADMIN SEEDING SUCCESSFUL!</h2><pre style='background:#f4f4f4; padding:15px; border-radius:8px;'>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "<h2 style='color:red; font-family:sans-serif;'>❌ SEEDING FAILED!</h2><p style='font-family:sans-serif;'>" . $e->getMessage() . "</p>";
    }
});

// 🛠️ DEBUG FILES IN PRODUCTION
Route::get('/debug-files', function () {
    $dir = public_path('images');
    if (!is_dir($dir)) {
        return "Folder public/images tidak ditemukan.";
    }
    $files = scandir($dir);
    $output = "<h3>Daftar file di public/images:</h3><ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $output .= "<li>" . $file . " (" . filesize($dir . '/' . $file) . " bytes)</li>";
        }
    }
    $output .= "</ul>";
    
    $output .= "<h3>Path Info:</h3>";
    $output .= "Public Path: " . public_path() . "<br>";
    $output .= "Base Path: " . base_path() . "<br>";
    return $output;
});

// 🛠️ RESET ADMIN PASSWORDS IN PRODUCTION
Route::get('/reset-admin-password', function () {
    try {
        $emails = [
            'admin@isbajaya.org',
            'bunga@isbajaya.org',
            'aulya@isbajaya.org',
            'hendri@isbajaya.org',
            'miftah@isbajaya.org'
        ];
        
        $output = "<h3>Status Reset Password Admin:</h3><ul>";
        $password = \Illuminate\Support\Facades\Hash::make('admin123');

        foreach ($emails as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $user->update(['password' => $password]);
                $output .= "<li>✅ {$email} berhasil di-reset menjadi <b>admin123</b></li>";
            } else {
                // Jika user belum ada, buat baru
                $name = explode('@', $email)[0];
                $user = \App\Models\User::create([
                    'name' => ucfirst($name),
                    'email' => $email,
                    'password' => $password
                ]);
                $user->assignRole('admin');
                $output .= "<li>🆕 {$email} tidak ditemukan, berhasil dibuat baru dengan password <b>admin123</b></li>";
            }
        }
        $output .= "</ul>";
        return $output;
    } catch (\Exception $e) {
        return "<h2 style='color:red; font-family:sans-serif;'>❌ RESET FAILED!</h2><p style='font-family:sans-serif;'>" . $e->getMessage() . "</p>";
    }
});


