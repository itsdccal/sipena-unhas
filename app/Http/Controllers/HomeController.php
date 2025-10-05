<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Check if user is active - CEK STATUS
        if (!$user->status) {
            auth()->logout();
            abort(403, 'Your account has been deactivated.');
        }

        // CEK ROLE LANGSUNG
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard(): View
    {
        $totalReports = Report::count();

        $totalUsers = User::where('role', '!=', 'admin')
            ->where('status', true)
            ->count();

        $totalStudyPrograms = StudyProgram::count();
        $totalFaculties = Faculty::count();


        // SESUAIKAN dengan kolom yang benar di database
        $totalCost = Report::sum('grand_total');

        // Reports by Study Program
        $reportsByStudyProgram = Report::select(
                'study_programs.sp_name as study_program',
                DB::raw('count(*) as total')
            )
            ->join('study_programs', 'reports.study_program_id', '=', 'study_programs.id')
            ->groupBy('study_programs.sp_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $reportsByFaculty = Report::select(
            'faculties.faculty_name as faculty',
            DB::raw('count(*) as count')
        )
        ->join('study_programs', 'reports.study_program_id', '=', 'study_programs.id')
        ->join('faculties', 'study_programs.faculty_id', '=', 'faculties.id')
        ->groupBy('faculties.faculty_name')
        ->orderByDesc('count')
        ->get();

        return view('dashboard.admin', compact(
            'totalReports',
            'totalUsers',
            'totalStudyPrograms',
            'totalFaculties',
            'totalCost',
            'reportsByStudyProgram',
            'reportsByFaculty'
        ));
    }

    private function userDashboard(User $user): View
    {
        $totalReports = Report::where('user_id', $user->id)->count();
        $totalCost = Report::where('user_id', $user->id)->sum('grand_total');

        $recentReports = Report::with(['studyProgram', 'semester'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.user', compact(
            'totalReports',
            'totalCost',
            'recentReports'
        ));
    }
}
