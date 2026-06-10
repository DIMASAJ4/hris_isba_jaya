<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateMemberUsers extends Command
{
    protected $signature = 'users:generate-members';
    protected $description = 'Generate user accounts for all members';

    public function handle()
    {
        $members = Member::all();
        $this->info("Found {$members->count()} members.");

        $adminNames = [
            'Rangga Pratama Yudha', // Ketua
            'Vattrik Aldiansah',    // Wakil
            'Fitria Firdawati',     // Sekretaris
            'Sabina Agustina',      // Bendahara
        ];

        foreach ($members as $member) {
            $firstName = strtolower(explode(' ', trim($member->full_name))[0]);
            $email = $firstName . $member->id . '@isbajaya.org';
            $password = 'isba' . date('Y'); // e.g. isba2026

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $member->full_name,
                    'password' => Hash::make($password),
                ]
            );

            $role = 'member';
            if (in_array($member->full_name, $adminNames)) {
                if ($member->full_name === 'Rangga Pratama Yudha') {
                    $role = 'chairman';
                } else {
                    $role = 'admin';
                }
            }
            
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            $this->info("Created user: {$email} (Role: {$role})");
        }

        $this->info('All member users generated successfully!');
    }
}
