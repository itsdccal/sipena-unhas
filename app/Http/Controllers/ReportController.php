<?php

namespace App\Http\Controllers;

use App\Models\ActivityDetail;
use App\Models\Report;
use App\Models\Semester;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with(['studyProgram', 'semester', 'creator']);

        // Regular users only see their own reports
        if (Auth::user()->role !== 'admin') {
            $query->where('created_by', Auth::id());
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('period', 'like', '%' . $search . '%');
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

        $reports = $query->latest()->paginate(10);
        $studyPrograms = StudyProgram::orderBy('sp_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();

        return view('reports.index', compact('reports', 'studyPrograms', 'semesters'));
    }

    public function create(): View
    {
        $studyPrograms = StudyProgram::orderBy('sp_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();

        return view('reports.create', compact('studyPrograms', 'semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'period' => ['required', 'string', 'max:100'],
            'activities' => ['required', 'array', 'min:1'],
            'activities.*.activity' => ['required', 'string'],
            'activities.*.volume' => ['required', 'numeric', 'min:0'],
            'activities.*.unit' => ['required', 'string'],
            'activities.*.unit_price' => ['required', 'numeric', 'min:0'],
            'activities.*.burden' => ['nullable', 'integer', 'min:0'],
            'documents.*' => ['nullable', 'file', 'max:10240'],
        ], [
            'title.required' => 'Report title is required',
            'study_program_id.required' => 'Study program must be selected',
            'semester_id.required' => 'Semester must be selected',
            'period.required' => 'Period is required',
            'activities.required' => 'At least one activity is required',
            'activities.*.activity.required' => 'Activity name is required',
            'activities.*.volume.required' => 'Volume is required',
            'activities.*.unit.required' => 'Unit is required',
            'activities.*.unit_price.required' => 'Unit price is required',
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $user = Auth::user();

            if (!$user) {
                throw new \RuntimeException('User not authenticated');
            }

            $report = Report::create([
                'title' => $validated['title'],
                'study_program_id' => (int) $validated['study_program_id'],
                'semester_id' => (int) $validated['semester_id'],
                'period' => $validated['period'],
                'total_cost' => 0,
                'created_by' => $user->id,
            ]);

            foreach ($validated['activities'] as $activity) {
                $volume = (float) $activity['volume'];
                $unitPrice = (float) $activity['unit_price'];
                $burden = !empty($activity['burden']) ? (int) $activity['burden'] : 0;
                $total = $volume * $unitPrice;
                $unitCost = $burden > 0 ? $total / $burden : 0;

                ActivityDetail::create([
                    'report_id' => $report->id,
                    'activity' => $activity['activity'],
                    'volume' => $volume,
                    'unit' => $activity['unit'],
                    'unit_price' => $unitPrice,
                    'total' => $total,
                    'burden' => $burden,
                    'unit_cost' => $unitCost,
                ]);
            }

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    if ($document->isValid()) {
                        $path = $document->store('report-documents', 'public');

                        $report->documents()->create([
                            'file_name' => $document->getClientOriginalName(),
                            'path' => $path,
                            'mime_type' => $document->getMimeType(),
                            'size' => $document->getSize(),
                        ]);
                    }
                }
            }

            $report->calculateTotal();
        });

        return redirect()->route('reports.index')
            ->with('success', 'Report created successfully!');
    }

    public function show(Report $report): View
    {
        $report->load(['studyProgram', 'semester', 'creator', 'activityDetails', 'documents']);

        // Regular users can only view their own reports
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function edit(Report $report): View
    {
        // Regular users can only edit their own reports
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->load(['activityDetails', 'documents']);
        $studyPrograms = StudyProgram::orderBy('sp_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();

        return view('reports.edit', compact('report', 'studyPrograms', 'semesters'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        // Regular users can only update their own reports
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'period' => ['required', 'string', 'max:100'],
            'activities' => ['required', 'array', 'min:1'],
            'activities.*.activity' => ['required', 'string'],
            'activities.*.volume' => ['required', 'numeric', 'min:0'],
            'activities.*.unit' => ['required', 'string'],
            'activities.*.unit_price' => ['required', 'numeric', 'min:0'],
            'activities.*.burden' => ['nullable', 'integer', 'min:0'],
            'documents.*' => ['nullable', 'file', 'max:10240'],
        ]);

        DB::transaction(function () use ($validated, $request, $report): void {
            $report->update([
                'title' => $validated['title'],
                'study_program_id' => (int) $validated['study_program_id'],
                'semester_id' => (int) $validated['semester_id'],
                'period' => $validated['period'],
            ]);

            // Delete old activities
            $report->activityDetails()->delete();

            // Create new activities
            foreach ($validated['activities'] as $activity) {
                $volume = (float) $activity['volume'];
                $unitPrice = (float) $activity['unit_price'];
                $burden = !empty($activity['burden']) ? (int) $activity['burden'] : 0;
                $total = $volume * $unitPrice;
                $unitCost = $burden > 0 ? $total / $burden : 0;

                ActivityDetail::create([
                    'report_id' => $report->id,
                    'activity' => $activity['activity'],
                    'volume' => $volume,
                    'unit' => $activity['unit'],
                    'unit_price' => $unitPrice,
                    'total' => $total,
                    'burden' => $burden,
                    'unit_cost' => $unitCost,
                ]);
            }

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    if ($document->isValid()) {
                        $path = $document->store('report-documents', 'public');

                        $report->documents()->create([
                            'file_name' => $document->getClientOriginalName(),
                            'path' => $path,
                            'mime_type' => $document->getMimeType(),
                            'size' => $document->getSize(),
                        ]);
                    }
                }
            }

            $report->calculateTotal();
        });

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report updated successfully!');
    }

    public function destroy(Report $report): RedirectResponse
    {
        if (Auth::user()->role !== 'admin' && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully!');
    }
}
