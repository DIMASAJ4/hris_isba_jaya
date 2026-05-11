<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Member;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members'    => Member::count(),
            'active_members'   => Member::where('status', 'Aktif')->count(),
            'inactive_members' => Member::where('status', 'Tidak Aktif')->count(),
            'total_departments'=> Department::where('is_active', true)->count(),
            'alumni'           => Member::where('status', 'Alumni')->count(),
            'pending'          => Member::where('status', 'Pending')->count(),
        ];

        // Data untuk bar chart: anggota per departemen
        $membersByDept = Department::withCount('members')
            ->where('is_active', true)
            ->get()
            ->map(fn($d) => [
                'name'  => $d->name,
                'count' => $d->members_count,
                'color' => $d->color,
            ]);

        // Data untuk donut chart: status keanggotaan
        $statusData = Member::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Anggota terbaru (5 data)
        $latestMembers = Member::with(['department', 'position'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats', 'membersByDept', 'statusData', 'latestMembers'
        ));
    }
}
