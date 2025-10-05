<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class AdminDegreeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Degree::with(['studyPrograms']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('degree_name', 'like', '%' . $search . '%')
                  ->orWhere('degree_code', 'like', '%' . $search . '%');
            });
        }

        $degrees = $query->latest()->paginate(15);

        return view('admin.degrees.index', compact('degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.degrees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'degree_code' => ['required', 'string', 'max:10', 'unique:degrees,degree_code'],
            'degree_name' => ['required', 'string', 'max:255'],
        ]);

        Degree::create($validated);

        return redirect()->route('admin.degrees.index')
            ->with('success', 'Data Jenjang berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Degree $degree): View
    {

        return view('admin.degrees.edit', compact('degree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Degree $degree): RedirectResponse
    {
        $validated = $request->validate([
            'degree_code' => ['required', 'string', 'max:10', Rule::unique('degrees', 'degree_code')->ignore($degree->id)],
            'degree_name' => ['required', 'string', 'max:255'],
        ]);

        $degree->update($validated);

        return redirect()->route('admin.degrees.index')
            ->with('success', 'Data Jenjang berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Degree $degree): RedirectResponse
    {
        if ($degree->studyPrograms()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete study program with existing users or reports!');
        }

        $degree->delete();

        return redirect()->route('admin.degrees.index')
            ->with('success', 'Data Jenjang berhasil dihapus!');
    }
}
