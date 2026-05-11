<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['members', 'positions'])->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name',
            'description' => 'nullable',
            'icon' => 'nullable',
            'color' => 'nullable',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil dibuat.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        if ($department->members()->where('status', 'Aktif')->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki anggota aktif.');
        }

        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
