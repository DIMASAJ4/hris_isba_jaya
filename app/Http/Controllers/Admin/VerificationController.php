<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberStatusLog;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['department', 'position']);

        if ($request->has('search')) {
            $query->where('full_name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(10);

        $statsSummary = [
            'total' => Member::count(),
            'pending' => Member::where('status', 'Pending')->count(),
            'aktif' => Member::where('status', 'Aktif')->count(),
        ];

        return view('admin.verification.index', compact('members', 'statsSummary'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif,Alumni,Pending',
            'note' => 'nullable|string',
        ]);

        $oldStatus = $member->status;
        $member->status = $request->status;
        $member->save();

        MemberStatusLog::create([
            'member_id' => $member->id,
            'old_status' => $oldStatus,
            'new_status' => $member->status,
            'changed_by' => auth()->id(),
            'note' => $request->note ?? 'Verifikasi status anggota.',
        ]);

        return redirect()->back()->with('success', 'Status anggota berhasil diperbarui.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'member_ids' => 'required|array',
            'status' => 'required|in:Aktif,Tidak Aktif,Alumni,Pending',
        ]);

        foreach ($request->member_ids as $id) {
            $member = Member::find($id);
            if ($member) {
                $oldStatus = $member->status;
                $member->status = $request->status;
                $member->save();

                MemberStatusLog::create([
                    'member_id' => $member->id,
                    'old_status' => $oldStatus,
                    'new_status' => $member->status,
                    'changed_by' => auth()->id(),
                    'note' => 'Update status massal oleh Admin.',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status anggota terpilih berhasil diperbarui.');
    }
}
