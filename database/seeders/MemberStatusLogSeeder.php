<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberStatusLogSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@isbajaya.org')->first();
        $members = Member::all();

        foreach ($members as $member) {
            // Log 1: Registering
            MemberStatusLog::create([
                'member_id' => $member->id,
                'old_status' => null,
                'new_status' => 'Pending',
                'changed_by' => $adminUser->id,
                'note' => 'Pendaftaran anggota baru melalui sistem.',
                'created_at' => $member->created_at,
            ]);

            // Log 2: Verification (if status not Pending)
            if ($member->status !== 'Pending') {
                MemberStatusLog::create([
                    'member_id' => $member->id,
                    'old_status' => 'Pending',
                    'new_status' => $member->status,
                    'changed_by' => $adminUser->id,
                    'note' => 'Verifikasi berkas dan persetujuan keanggotaan.',
                    'created_at' => $member->created_at->addDays(2),
                ]);
            }
        }
    }
}
