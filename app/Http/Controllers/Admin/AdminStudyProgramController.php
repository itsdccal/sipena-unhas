<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminStudyProgramController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudyProgram::with(['faculty', 'degree']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('sp_name', 'like', '%' . $search . '%')
                  ->orWhere('sp_code', 'like', '%' . $search . '%');
            });
        }

        // Filter by Faculty
        if ($request->filled('faculty')) {
            $query->where('faculty_id', $request->input('faculty'));
        }

        // Filter by Degree
        if ($request->filled('degree')) {
            $query->where('degree_id', $request->input('degree'));
        }

        $studyPrograms = $query->latest()->paginate(15);
        $faculties = Faculty::orderBy('faculty_name')->get();
        $degrees = Degree::orderBy('degree_name')->get();

        return view('admin.study-programs.index', compact('studyPrograms', 'faculties', 'degrees'));
    }

    public function create(): View
    {
        $faculties = Faculty::orderBy('faculty_name')->get();
        $degrees = Degree::orderBy('degree_name')->get();

        return view('admin.study-programs.create', compact('faculties', 'degrees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sp_name' => ['required', 'string', 'max:255'],
            'sp_code' => ['required', 'string', 'max:10', 'unique:study_programs,sp_code'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'degree_id' => ['required', 'exists:degrees,id'],
        ]);

        StudyProgram::create($validated);

        return redirect()->route('admin.study-programs.index')
            ->with('success', 'Data Program Studi berhasil ditambahkan!');
    }

    public function edit(StudyProgram $studyProgram): View
    {
        $faculties = Faculty::orderBy('faculty_name')->get();
        $degrees = Degree::orderBy('degree_name')->get();

        return view('admin.study-programs.edit', compact('studyProgram', 'faculties', 'degrees'));
    }

    public function update(Request $request, StudyProgram $studyProgram): RedirectResponse
    {
        $validated = $request->validate([
            'sp_name' => ['required', 'string', 'max:255'],
            'sp_code' => ['required', 'string', 'max:10', Rule::unique('study_programs', 'sp_code')->ignore($studyProgram->id)],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'degree_id' => ['required', 'exists:degrees,id'],
        ]);

        $studyProgram->update($validated);

        return redirect()->route('admin.study-programs.index')
            ->with('success', 'Data Program Studi berhasil diubah!');
    }

    public function destroy(StudyProgram $studyProgram): RedirectResponse
    {
        // Check if study program has users or reports
        if ($studyProgram->users()->count() > 0 || $studyProgram->reports()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete study program with existing users or reports!');
        }

        $studyProgram->delete();

        return redirect()->route('admin.study-programs.index')
            ->with('success', 'Data Program Studi berhasil dihapus!');
    }
}
