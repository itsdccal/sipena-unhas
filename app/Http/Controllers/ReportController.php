<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\ActivityDetail;
use App\Models\Report;
use App\Models\Semester;
use App\Models\StudyProgram;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
public function index(Request $request): View
{
    // Get all reports for current user (grouped by study program implicitly)
    $reports = Report::with([
            'studyProgram.degree',
            'semester',
            'activityDetails.unit',
        ])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'asc')
        ->get();

    $studyPrograms = StudyProgram::all();
    $semesters = Semester::orderBy('semester_name', 'desc')->get();
    $units = Unit::where('is_active', true)->get();

    return view('reports.index', compact('reports', 'studyPrograms', 'semesters', 'units'));
}

    public function create(): View
    {
        $studyPrograms = StudyProgram::all();
        $semesters = Semester::all();
        $units = Unit::where('is_active', true)->get();

        return view('reports.create', compact('studyPrograms', 'semesters', 'units'));
    }

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'study_program_id' => ['required', 'exists:study_programs,id'],
        'semester_id' => ['required', 'exists:semesters,id'],
    ]);

    // Create report dengan grand_total = 0 (belum ada activities)
    $report = Report::create([
        'study_program_id' => $validated['study_program_id'],
        'semester_id' => $validated['semester_id'],
        'user_id' => auth()->id(),
        'grand_total' => 0,
    ]);

    // Redirect ke show page untuk tambah activities
    return redirect()->route('reports.show', $report)
        ->with('success', 'Report created successfully! Now add your activities.');
}

public function show(Report $report): View
{
    // Check authorization
    if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
        abort(403);
    }

    $report->load([
        'studyProgram.degree',
        'semester',
        'user',
        'activityDetails.unit',
    ]);

    $units = Unit::where('is_active', true)->get(); // ADD THIS

    return view('reports.show', compact('report', 'units')); // UPDATE THIS
}

    public function edit(Report $report): View
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $studyPrograms = StudyProgram::all();
        $semesters = Semester::all();
        $units = Unit::where('is_active', true)->get();

        $report->load([
            'activityDetails.unit',
            'activityDetails.subActivities'
        ]);

        return view('reports.edit', compact('report', 'studyPrograms', 'semesters', 'units'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'activities' => ['required', 'array', 'min:1'],
            'activities.*.activity_name' => ['required', 'string', 'max:255'],
            'activities.*.unit_id' => ['nullable', 'exists:units,id'],
            'activities.*.volume' => ['required', 'numeric', 'min:0'],
            'activities.*.unit_price' => ['required', 'numeric', 'min:0'],
            'activities.*.total' => ['required', 'numeric', 'min:0'],
            'activities.*.allocation' => ['nullable', 'integer', 'min:0'],
            'activities.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'activities.*.notes' => ['nullable', 'string'],
            'activities.*.sub_activities' => ['nullable', 'array'],
            'activities.*.sub_activities.*.sub_activity_name' => ['required', 'string', 'max:255'],
            'activities.*.sub_activities.*.volume' => ['required', 'numeric', 'min:0'],
            'activities.*.sub_activities.*.unit_satuan' => ['nullable', 'string', 'max:50'],
            'activities.*.sub_activities.*.unit_price' => ['required', 'numeric', 'min:0'],
            'activities.*.sub_activities.*.total' => ['required', 'numeric', 'min:0'],
            'activities.*.sub_activities.*.allocation' => ['nullable', 'integer', 'min:0'],
            'activities.*.sub_activities.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($report, $validated) {
            $report->update([
                'study_program_id' => $validated['study_program_id'],
                'semester_id' => $validated['semester_id'],
                'grand_total' => 0,
            ]);

            // Delete old activities
            $report->activityDetails()->delete();

            foreach ($validated['activities'] as $activityData) {
                $activity = ActivityDetail::create([
                    'report_id' => $report->id,
                    'activity_name' => $activityData['activity_name'],
                    'unit_id' => $activityData['unit_id'] ?? null,
                    'calculation_type' => 'manual',
                    'volume' => $activityData['volume'],
                    'unit_price' => $activityData['unit_price'],
                    'total' => $activityData['total'],
                    'allocation' => $activityData['allocation'] ?? null,
                    'unit_cost' => $activityData['unit_cost'] ?? 0,
                    'notes' => $activityData['notes'] ?? null,
                ]);

            }

            $report->recalculateGrandTotal();
        });

        return redirect()->route('reports.index')
            ->with('success', 'Report updated successfully!');
    }

    public function destroy(Report $report): RedirectResponse
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully!');
    }

    public function export()
    {
        return Excel::download(new ReportExport, 'reports.xlsx');
    }
}
