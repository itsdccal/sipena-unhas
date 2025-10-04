<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report Detail') }}
            </h2>
            <a href="{{ route('admin.reports.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900">{{ $report->program_type }}</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Created by {{ $report->user->name ?? 'Unknown' }} on {{ $report->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Study Program -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Study Program</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $report->studyProgram->sp_code ?? 'N/A' }} - {{ $report->studyProgram->sp_name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Semester</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $report->semester->semester_name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Program Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Program Type</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $report->program_type }}
                            </p>
                        </div>

                        <!-- Grand Total -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Grand Total</label>
                            <p class="mt-1 text-lg font-bold text-blue-600">
                                Rp {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $report->created_at->format('d M Y H:i:s') }}
                            </p>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $report->updated_at->format('d M Y H:i:s') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Details</h3>

                    @if($report->activityDetails && $report->activityDetails->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Name</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calculation Type</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($report->activityDetails as $index => $detail)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $detail->activity_name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->calculation_type }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($detail->volume, 2, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Rp {{ number_format($detail->total, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->allocation ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($detail->unit_cost ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-right text-sm font-bold text-gray-900">
                                            Total:
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-blue-600">
                                            Rp {{ number_format($report->activityDetails->sum('total'), 0, ',', '.') }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No activity details available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Components (if any) -->
            @if($report->activityDetails && $report->activityDetails->count() > 0)
                @foreach($report->activityDetails as $detail)
                    @if($detail->activityComponents && $detail->activityComponents->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Components for: {{ $detail->activity_name }}
                                </h3>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Component</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Order</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($detail->activityComponents->sortBy('display_order') as $component)
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ $component->component_name }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ number_format($component->component_value, 2, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">
                                                        {{ $component->display_order }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- Note (if exists) -->
            @if($report->note)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                        <div class="prose max-w-none text-sm text-gray-700">
                            {{ $report->note }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>

                <form method="POST"
                    action="{{ route('admin.reports.destroy', $report) }}"
                    onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
