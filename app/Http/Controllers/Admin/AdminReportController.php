<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        // Get study programs that have reports
        $query = StudyProgram::with(['degree', 'faculty'])
            ->whereHas('reports')
            ->withCount('reports')
            ->withSum('reports', 'grand_total');

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('sp_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('sp_code', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('degree', function ($q) use ($search) {
                      $q->where('degree_name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('faculty', function ($q) use ($search) {
                      $q->where('faculty_name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Apply faculty filter if provided
        if ($request->filled('faculty')) {
            $query->where('faculty_id', $request->input('faculty'));
        }

        // Apply degree filter if provided
        if ($request->filled('degree')) {
            $query->where('degree_id', $request->input('degree'));
        }

        $studyProgramsWithReports = $query->orderBy('sp_name')->get();

        // Get all faculties and degrees for filter dropdowns
        $faculties = Faculty::orderBy('faculty_name')->get();
        $degrees = Degree::orderBy('degree_name')->get();

        // All study programs for search suggestions (including those without reports)
        $allStudyPrograms = StudyProgram::with(['degree', 'faculty'])
            ->orderBy('sp_name')
            ->get();

        return view('admin.reports.index', compact('studyProgramsWithReports', 'faculties', 'degrees', 'allStudyPrograms'));
    }

    public function show(Report $report): View
    {
        $report->load(['studyProgram', 'semester', 'user', 'activityDetails']);

        return view('admin.reports.show', compact('report'));
    }

    public function showProgram(StudyProgram $studyProgram): View
    {
        // Get all reports for this study program with details
        $reports = Report::with(['semester', 'user', 'activityDetails.unit'])
            ->where('study_program_id', $studyProgram->id)
            ->orderBy('semester_id', 'asc')
            ->get();

        $studyProgram->load(['degree', 'faculty']);

        return view('admin.reports.show-program', compact('studyProgram', 'reports'));
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
        return redirect()->back()->with('info', 'Export feature coming soon!');
    }
}
