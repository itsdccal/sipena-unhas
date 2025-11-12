<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityTemplate::query();

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where('activity_name', 'like', '%' . $request->search . '%');
        }

        $activityTemplates = $query->orderBy('activity_name')->paginate(15);

        // Untuk dropdown autocomplete di form staff
        $allActivityTemplates = ActivityTemplate::active()
            ->orderBy('activity_name')
            ->get();

        return view('admin.activity-templates.index', compact('activityTemplates', 'allActivityTemplates'));
    }

    public function create(): View
    {
        return view('admin.activity-templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        ActivityTemplate::create($validated);

        return redirect()->route('admin.activity-templates.index')
            ->with('success', 'Template aktivitas berhasil dibuat!');
    }

    public function edit(ActivityTemplate $activityTemplate): View
    {
        return view('admin.activity-templates.edit', compact('activityTemplate'));
    }

    public function update(Request $request, ActivityTemplate $activityTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $activityTemplate->update($validated);

        return redirect()->route('admin.activity-templates.index')
            ->with('success', 'Template aktivitas berhasil diupdate!');
    }

    public function destroy(ActivityTemplate $activityTemplate): RedirectResponse
    {
        $activityTemplate->delete();

        return redirect()->route('admin.activity-templates.index')
            ->with('success', 'Template aktivitas berhasil dihapus!');
    }
}
