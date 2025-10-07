<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AdminFacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Faculty::with(['studyPrograms']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('faculty_name', 'like', '%' . $search . '%')
                  ->orWhere('faculty_code', 'like', '%' . $search . '%');
            });
        }

        $faculties = $query->latest()->paginate(15);

        // All faculties for search suggestions
        $allFaculties = Faculty::with(['studyPrograms'])
            ->orderBy('faculty_name')
            ->get();

        return view('admin.faculties.index', compact('faculties', 'allFaculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.faculties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'faculty_code' => ['required', 'string', 'max:10', 'unique:faculties,faculty_code'],
            'faculty_name' => ['required', 'string', 'max:255'],
        ]);

        Faculty::create($validated);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Data Fakultas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Faculty $faculty): View
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty): RedirectResponse
    {
        $validated = $request->validate([
            'faculty_code' => ['required', 'string', 'max:10', Rule::unique('faculties', 'faculty_code')->ignore($faculty->id)],
            'faculty_name' => ['required', 'string', 'max:255'],
        ]);

        $faculty->update($validated);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Data Fakultas berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty): RedirectResponse
    {
        if ($faculty->studyPrograms()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete study program with existing users or reports!');
        }

        $faculty->delete();

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Data Fakultas berhasil dihapus!');
    }
}
