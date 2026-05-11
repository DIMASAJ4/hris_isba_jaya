<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->get();
        $departments = Department::all();
        return view('admin.positions.index', compact('positions', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required',
            'level' => 'required',
        ]);

        Position::create($request->all());

        return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required',
            'level' => 'required',
        ]);

        $position->update($request->all());

        return redirect()->back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        if ($position->members()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus jabatan yang masih memiliki anggota.');
        }

        $position->delete();
        return redirect()->back()->with('success', 'Jabatan berhasil dihapus.');
    }

    public function byDepartment(Department $department)
    {
        return response()->json(
            Position::where('department_id', $department->id)->get(['id','name','level'])
        );
    }
}
