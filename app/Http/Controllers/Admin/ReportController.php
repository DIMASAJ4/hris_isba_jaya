<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Member;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('is_active', true)->get();
        
        $query = Member::with(['department', 'position']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('batch_year')) {
            $query->where('batch_year', $request->batch_year);
        }

        $members = $query->get();

        return view('admin.reports.index', compact('departments', 'members'));
    }

    public function exportPdf(Request $request)
    {
        $query = Member::with(['department', 'position']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('batch_year')) {
            $query->where('batch_year', $request->batch_year);
        }
        
        $data = $query->get();
        $pdf = Pdf::loadView('reports.members_pdf', ['members' => $data]);
        return $pdf->download('laporan-keanggotaan-isba.pdf');
    }

    public function exportExcel(Request $request)
    {
        // For simplicity, we'll reuse MemberController's export if already defined, 
        // or we use the ReportExport class.
        return Excel::download(new ReportExport($request->all()), 'laporan-keanggotaan-isba.xlsx');
    }
}
