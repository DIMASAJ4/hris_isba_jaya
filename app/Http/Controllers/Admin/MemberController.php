<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Member;
use App\Models\MemberStatusLog;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembersImport;
use App\Exports\MembersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['department', 'position']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('nim', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        $members = $query->latest()->paginate(10);
        $departments = Department::where('is_active', true)->get();

        return view('admin.members.index', compact('members', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.members.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nim' => 'required|string|unique:members,nim',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:members,email',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'batch_year' => 'nullable|digits:4',
            'university' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif,Alumni,Pending',
            'joined_at' => 'nullable|date',
            'create_account' => 'nullable|boolean',
            'user_email' => 'required_if:create_account,1|email|unique:users,email',
            'user_password' => 'required_if:create_account,1|min:8',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        if ($request->create_account) {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
            ]);
            $user->assignRole('member');
            $data['user_id'] = $user->id;
        }

        $member = Member::create($data);

        MemberStatusLog::create([
            'member_id' => $member->id,
            'old_status' => null,
            'new_status' => $member->status,
            'changed_by' => auth()->id(),
            'note' => 'Pendaftaran awal anggota.',
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        $member->load(['department', 'position', 'user', 'statusLogs.changedBy']);
        return view('admin.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('department_id', $member->department_id)->get();
        return view('admin.members.edit', compact('member', 'departments', 'positions'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nim' => 'required|string|unique:members,nim,' . $member->id,
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Aktif,Tidak Aktif,Alumni,Pending',
        ]);

        $data = $request->all();
        $oldStatus = $member->status;

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member->update($data);

        if ($oldStatus !== $member->status) {
            MemberStatusLog::create([
                'member_id' => $member->id,
                'old_status' => $oldStatus,
                'new_status' => $member->status,
                'changed_by' => auth()->id(),
                'note' => 'Perubahan status manual oleh Admin.',
            ]);
        }

        return redirect()->route('admin.members.show', $member)->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus (Soft Delete).');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new MembersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data anggota berhasil diimport.');
    }

    public function exportExcel()
    {
        return Excel::download(new MembersExport, 'anggota-isba-jaya.xlsx');
    }

    public function exportPdf()
    {
        $members = Member::with(['department', 'position'])->get();
        $pdf = Pdf::loadView('reports.members_pdf', compact('members'));
        return $pdf->download('anggota-isba-jaya.pdf');
    }
}
