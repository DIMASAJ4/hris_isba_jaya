<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        // Fetch departments with positions and their active members
        $departments = Department::with(['positions' => function($query) {
            $query->orderBy('level', 'asc');
        }, 'positions.members' => function($query) {
            $query->where('status', 'Aktif');
        }])->get();

        return view('admin.organization.index', compact('departments'));
    }

    public function memberView()
    {
        $departments = Department::with(['positions' => function($query) {
            $query->orderBy('level', 'asc');
        }, 'positions.members' => function($query) {
            $query->where('status', 'Aktif');
        }])->get();

        return view('member.organization.index', compact('departments'));
    }

    public function chairmanView()
    {
        $departments = Department::with(['positions' => function($query) {
            $query->orderBy('level', 'asc');
        }, 'positions.members' => function($query) {
            $query->where('status', 'Aktif');
        }])->get();

        return view('chairman.organization.index', compact('departments'));
    }
}
