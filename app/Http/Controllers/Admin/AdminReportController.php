<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Semester;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with(['studyProgram', 'semester', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('period', 'like', '%' . $search . '%')
                  ->orWhereHas('creator', function ($q) use ($search): void {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by Study Program
        if ($request->filled('study_program')) {
            $query->where('study_program_id', $request->input('study_program'));
        }

        // Filter by Semester
        if ($request->filled('semester')) {
            $query->where('semester_id', $request->input('semester'));
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $reports = $query->latest()->paginate(15);
        $studyPrograms = StudyProgram::where('is_active', true)->get();
        $semesters = Semester::where('is_active', true)->get();

        return view('admin.reports.index', compact('reports', 'studyPrograms', 'semesters'));
    }

    public function show(Report $report): View
    {
        $report->load(['studyProgram', 'semester', 'creator', 'activityDetails', 'documents']);

        return view('admin.reports.show', compact('report'));
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully!');
    }

    public function exportExcel(Request $request): mixed
    {
        // TODO: Implement Excel export
        // You can use maatwebsite/excel package

        return redirect()->back()->with('info', 'Export feature coming soon!');
    }
}
