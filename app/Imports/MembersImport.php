<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Department;
use App\Models\Position;
use App\Models\MemberStatusLog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MembersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $department = Department::where('name', $row['departemen'])->first();
        $position = Position::where('name', $row['jabatan'])->first();

        $member = Member::create([
            'full_name'     => $row['nama_lengkap'],
            'nim'           => $row['nim'],
            'gender'        => $row['jenis_kelamin'],
            'birth_place'   => $row['tempat_lahir'] ?? null,
            'birth_date'    => $row['tanggal_lahir'] ?? null,
            'phone'         => $row['no_hp'] ?? null,
            'email'         => $row['email'] ?? null,
            'address'       => $row['alamat'] ?? null,
            'department_id' => $department->id ?? null,
            'position_id'   => $position->id ?? null,
            'batch_year'    => $row['angkatan'] ?? null,
            'university'    => $row['universitas'] ?? null,
            'status'        => $row['status'] ?? 'Pending',
            'joined_at'     => $row['tanggal_bergabung'] ?? null,
        ]);

        MemberStatusLog::create([
            'member_id' => $member->id,
            'old_status' => null,
            'new_status' => $member->status,
            'changed_by' => auth()->id(),
            'note' => 'Import dari Excel.',
        ]);

        return $member;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required',
            'nim' => 'required|unique:members,nim',
            'status' => 'required|in:Aktif,Tidak Aktif,Alumni,Pending',
        ];
    }
}
