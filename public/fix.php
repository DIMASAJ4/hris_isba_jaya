<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// 🛠️ FORCE RESET & LIST SEMUA AKUN
$users = \App\Models\User::all();
$password = 'isba2026';
$hashedPassword = \Illuminate\Support\Facades\Hash::make($password);

$adminNames = [
    'Rangga Pratama Yudha', // Ketua
    'Vattrik Aldiansah',    // Wakil
    'Fitria Firdawati',     // Sekretaris
    'Sabina Agustina',      // Bendahara
];

$output = "<h2 style='color:#980D0D; font-family:sans-serif;'>✅ SEMUA AKUN BERHASIL DIRESET!</h2>";
$output .= "<p style='font-family:sans-serif;'>Silakan gunakan email di bawah ini dengan password: <b>isba2026</b></p>";
$output .= "<p style='font-family:sans-serif; color:green;'>Setelah berhasil login, mohon hapus file <b>fix.php</b> ini dari server demi keamanan.</p>";
$output .= "<table border='1' cellpadding='10' style='border-collapse: collapse; font-family:sans-serif;'>";
$output .= "<tr style='background:#f4f4f4;'><th>Nama</th><th>Email Login</th><th>Password Baru</th><th>Role Aktual</th></tr>";

foreach ($users as $user) {
    // Reset Password
    $user->update(['password' => $hashedPassword]);
    
    // Force Roles for BPH
    if (in_array($user->name, $adminNames)) {
        if ($user->name === 'Rangga Pratama Yudha') {
            $user->syncRoles(['chairman']);
        } else {
            $user->syncRoles(['admin']);
        }
    }

    $roles = $user->getRoleNames()->join(', ');
    $output .= "<tr><td>{$user->name}</td><td><b>{$user->email}</b></td><td><b>{$password}</b></td><td><span style='text-transform:uppercase;'>{$roles}</span></td></tr>";
}

$output .= "</table>";
echo $output;
