<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $query = StudyProgram::with('faculty');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        // Filter by Faculty
        if ($request->filled('faculty')) {
            $query->where('faculty_id', $request->input('faculty'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        $studyPrograms = $query->latest()->paginate(15);
        $faculties = Faculty::where('is_active', true)->get();

        return view('admin.study-programs.index', compact('studyPrograms', 'faculties'));
    }

    public function create(): View
    {
        $faculties = Faculty::where('is_active', true)->get();

        return view('admin.study-programs.create', compact('faculties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:study_programs,code'],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'is_active' => ['boolean'],
        ]);

        StudyProgram::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'faculty_id' => $validated['faculty_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.study-programs.index')
            ->with('success', 'Study program created successfully!');
    }

    public function edit(StudyProgram $studyProgram): View
    {
        $faculties = Faculty::where('is_active', true)->get();

        return view('admin.study-programs.edit', compact('studyProgram', 'faculties'));
    }

    public function update(Request $request, StudyProgram $studyProgram): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('study_programs', 'code')->ignore($studyProgram->id)],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'is_active' => ['boolean'],
        ]);

        $studyProgram->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'faculty_id' => $validated['faculty_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.study-programs.index')
            ->with('success', 'Study program updated successfully!');
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
            ->with('success', 'Study program deleted successfully!');
    }

    public function toggleStatus(StudyProgram $studyProgram): RedirectResponse
    {
        $studyProgram->update(['is_active' => !$studyProgram->is_active]);

        $status = $studyProgram->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Study program {$status} successfully!");
    }
}
