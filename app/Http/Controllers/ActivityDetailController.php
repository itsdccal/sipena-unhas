<?php

namespace App\Http\Controllers;

use App\Models\ActivityDetail;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityDetailController extends Controller
{
    public function store(Request $request, Report $report): RedirectResponse
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $report->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'volume' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'allocation' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        // Calculate total and unit_cost
        $total = $validated['volume'] * $validated['unit_price'];
        $unit_cost = 0;
        if (!empty($validated['allocation']) && $validated['allocation'] > 0) {
            $unit_cost = $total / $validated['allocation'];
        }

        ActivityDetail::create([
            'report_id' => $report->id,
            'activity_name' => $validated['activity_name'],
            'unit_id' => $validated['unit_id'] ?? null,
            'calculation_type' => 'manual',
            'volume' => $validated['volume'],
            'unit_price' => $validated['unit_price'],
            'total' => $total,
            'allocation' => $validated['allocation'] ?? null,
            'unit_cost' => $unit_cost,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Recalculate report grand total
        $report->recalculateGrandTotal();

        return redirect()->route('reports.index')
            ->with('success', 'Activity added successfully!');
    }

    public function edit(ActivityDetail $activity): View
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $activity->report->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reports.activities.edit', compact('activity'));
    }

    public function update(Request $request, ActivityDetail $activity): RedirectResponse
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $activity->report->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'volume' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'allocation' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        // Calculate total and unit_cost
        $total = $validated['volume'] * $validated['unit_price'];
        $unit_cost = 0;
        if (!empty($validated['allocation']) && $validated['allocation'] > 0) {
            $unit_cost = $total / $validated['allocation'];
        }

        $activity->update([
            'activity_name' => $validated['activity_name'],
            'unit_id' => $validated['unit_id'] ?? null,
            'volume' => $validated['volume'],
            'unit_price' => $validated['unit_price'],
            'total' => $total,
            'allocation' => $validated['allocation'] ?? null,
            'unit_cost' => $unit_cost,
            'notes' => $validated['notes'] ?? null,
        ]);

        $activity->report->recalculateGrandTotal();

        return redirect()->route('reports.index')
            ->with('success', 'Activity updated successfully!');
    }

    public function destroy(ActivityDetail $activity): RedirectResponse
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $activity->report->user_id !== auth()->id()) {
            abort(403);
        }

        $report = $activity->report;
        $activity->delete();

        $report->recalculateGrandTotal();

        return redirect()->route('reports.index')
            ->with('success', 'Activity deleted successfully!');
    }
}
