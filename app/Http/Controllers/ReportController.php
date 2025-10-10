<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\ActivityDetail;
use App\Models\Report;
use App\Models\StudyProgram;
use App\Models\Semester;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $query = Report::with(['studyProgram.degree', 'semester', 'activityDetails.unit']);

        // Filter by user role
        if (Auth::user()->role === 'staff' && Auth::user()->study_program_id) {
            $query->where('study_program_id', Auth::user()->study_program_id);
        }

        // Urutkan berdasarkan semester_id
        $reports = $query->orderBy('semester_id', 'asc')->get();

        // Data for modals
        $studyPrograms = StudyProgram::with('degree')->get();
        $semesters = Semester::orderBy('id', 'asc')->get();
        $units = Unit::all();

        return view('reports.index', compact('reports', 'studyPrograms', 'semesters', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'study_program_id' => 'required|exists:study_programs,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $report = Report::create([
            'study_program_id' => $request->study_program_id,
            'semester_id' => $request->semester_id, // Tambahkan ini
            'grand_total' => 0, // Tambahkan ini
            'user_id' => Auth::id(), // Ganti dari created_by ke user_id
        ]);

        return redirect()->route('reports.index')->with('success', 'Semester berhasil ditambahkan!');
    }

    public function storeActivity(Request $request, Report $report)
    {
        try {
            $request->validate([
                'activity_name' => 'required|string|max:255',
                'unit_id' => 'required|exists:units,id',
                'volume' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'allocation' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            $total = $request->volume * $request->unit_price;
            $unit_cost = $request->allocation > 0 ? $total / $request->allocation : 0;

            $activity = $report->activityDetails()->create([
                'activity_name' => $request->activity_name,
                'unit_id' => $request->unit_id,
                'volume' => $request->volume,
                'unit_price' => $request->unit_price,
                'total' => $total,
                'allocation' => $request->allocation ?? 0,
                'unit_cost' => $unit_cost,
                'notes' => $request->notes,
            ]);

            // Update grand total
            $grandTotal = $report->activityDetails()->sum('total');
            $report->update(['grand_total' => $grandTotal]);

            return response()->json([
                'success' => true,
                'message' => 'Activity berhasil ditambahkan!',
                'activity' => $activity->load('unit')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating activity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateActivity(Request $request, ActivityDetail $activity)
    {
        try {
            $request->validate([
                'activity_name' => 'required|string|max:255',
                'unit_id' => 'required|exists:units,id',
                'volume' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric|min:0',
                'allocation' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            $total = $request->volume * $request->unit_price;
            $unit_cost = $request->allocation > 0 ? $total / $request->allocation : 0;

            $activity->update([
                'activity_name' => $request->activity_name,
                'unit_id' => $request->unit_id,
                'volume' => $request->volume,
                'unit_price' => $request->unit_price,
                'total' => $total,
                'allocation' => $request->allocation ?? 0,
                'unit_cost' => $unit_cost,
                'notes' => $request->notes,
            ]);

            // Update grand total
            $report = $activity->report;
            $grandTotal = $report->activityDetails()->sum('total');
            $report->update(['grand_total' => $grandTotal]);

            return response()->json([
                'success' => true,
                'message' => 'Activity berhasil diupdate!',
                'activity' => $activity->load('unit')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating activity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyActivity(ActivityDetail $activity)
    {
        $report = $activity->report;
        $activity->delete();

        // Update grand total
        $this->updateReportGrandTotal($report);

        return redirect()->route('reports.index')->with('success', 'Activity berhasil dihapus!');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Semester berhasil dihapus!');
    }

    private function updateReportGrandTotal(Report $report)
    {
        $grandTotal = $report->activityDetails()->sum('total');
        $report->update(['grand_total' => $grandTotal]);
    }

    public function export()
    {
        $user = Auth::user();
        return Excel::download(new ReportExport, 'Laporan_Prodi_'.$user->studyProgram->sp_name.'.xlsx');
    }
}
