<?php

namespace App\Http\Controllers;

use App\Models\ActivityDetail;
use App\Models\SubActivityDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubActivityDetailController extends Controller
{
    public function store(Request $request, ActivityDetail $activity): RedirectResponse
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $activity->report->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'sub_activity_name' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'numeric', 'min:0'],
            'unit_satuan' => ['nullable', 'string', 'max:50'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'allocation' => ['nullable', 'integer', 'min:0'],
        ]);

        // Calculate total and unit_cost
        $total = $validated['volume'] * $validated['unit_price'];
        $unit_cost = 0;
        if (!empty($validated['allocation']) && $validated['allocation'] > 0) {
            $unit_cost = $total / $validated['allocation'];
        }

        SubActivityDetail::create([
            'activity_detail_id' => $activity->id,
            'sub_activity_name' => $validated['sub_activity_name'],
            'volume' => $validated['volume'],
            'unit_satuan' => $validated['unit_satuan'] ?? null,
            'unit_price' => $validated['unit_price'],
            'total' => $total,
            'allocation' => $validated['allocation'] ?? null,
            'unit_cost' => $unit_cost,
        ]);

        return redirect()->route('reports.show', $activity->report)
            ->with('success', 'Sub activity added successfully!');
    }

    public function destroy(SubActivityDetail $subActivity): RedirectResponse
    {
        // Check authorization
        if (auth()->user()->role !== 'admin' && $subActivity->activityDetail->report->user_id !== auth()->id()) {
            abort(403);
        }

        $report = $subActivity->activityDetail->report;
        $subActivity->delete();

        return redirect()->route('reports.show', $report)
            ->with('success', 'Sub activity deleted successfully!');
    }
}
