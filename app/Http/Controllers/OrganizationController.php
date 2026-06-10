<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    private function getOrgTreeData()
    {
        // BPH
        $bph = Department::where('slug', 'bph')->first();
        
        // BPH Positions
        $ketua = null;
        $wakil = null;
        $sekretaris = null;
        $bendahara = null;
        
        if ($bph) {
            $ketua = $bph->positions()->where('name', 'like', '%Ketua%')->whereNot('name', 'like', '%Wakil%')->with(['members' => fn($q) => $q->where('status', 'Aktif')])->first();
            $wakil = $bph->positions()->where('name', 'like', '%Wakil%')->with(['members' => fn($q) => $q->where('status', 'Aktif')])->first();
            $sekretaris = $bph->positions()->where('name', 'like', '%Sekretaris%')->with(['members' => fn($q) => $q->where('status', 'Aktif')])->first();
            $bendahara = $bph->positions()->where('name', 'like', '%Bendahara%')->with(['members' => fn($q) => $q->where('status', 'Aktif')])->first();
        }

        // Other Departments
        $otherDepartments = Department::where('slug', '!=', 'bph')
            ->with(['positions' => function($query) {
                $query->orderBy('level', 'asc');
            }, 'positions.members' => function($query) {
                $query->where('status', 'Aktif');
            }])->get();

        return compact('ketua', 'wakil', 'sekretaris', 'bendahara', 'otherDepartments');
    }

    public function index()
    {
        return view('admin.organization.index', $this->getOrgTreeData());
    }

    public function memberView()
    {
        return view('member.organization.index', $this->getOrgTreeData());
    }

    public function chairmanView()
    {
        return view('chairman.organization.index', $this->getOrgTreeData());
    }
}
