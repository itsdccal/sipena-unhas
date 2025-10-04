<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\StudyProgram;
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

        // Check if user is active
        if (!$user->is_active) {
            auth()->logout();
            abort(403, 'Your account has been deactivated.');
        }

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard(): View
    {
        $totalReports = Report::count();
        $totalUsers = User::where('role', 'user')->where('is_active', true)->count();
        $totalStudyPrograms = StudyProgram::where('is_active', true)->count();
        $totalCost = Report::sum('total_cost');

        // Reports by Study Program
        $reportsByStudyProgram = Report::select('study_programs.name as study_program', DB::raw('count(*) as total'))
            ->join('study_programs', 'reports.study_program_id', '=', 'study_programs.id')
            ->groupBy('study_programs.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Reports by Period
        $reportsByPeriod = Report::select('period', DB::raw('count(*) as count'))
            ->groupBy('period')
            ->orderByDesc('count')
            ->limit(6)
            ->get();

        return view('dashboard.admin', compact(
            'totalReports',
            'totalUsers',
            'totalStudyPrograms',
            'totalCost',
            'reportsByStudyProgram',
            'reportsByPeriod'
        ));
    }

    private function userDashboard(User $user): View
    {
        $totalReports = Report::where('created_by', $user->id)->count();
        $totalCost = Report::where('created_by', $user->id)->sum('total_cost');

        $recentReports = Report::with(['studyProgram', 'semester'])
            ->where('created_by', $user->id)
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
