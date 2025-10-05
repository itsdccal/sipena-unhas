<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Report Details
                </h2>
                <a href="{{ route('admin.reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
            </div>

            <!-- Report Header Info -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Report Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Study Program</p>
                        <p class="text-base font-bold text-gray-900">
                            {{ $report->studyProgram->sp_name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Semester</p>
                        <p class="text-base font-bold text-gray-900">
                            {{ $report->semester->semester_name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Created By</p>
                        <p class="text-base font-bold text-gray-900">
                            {{ $report->user->name ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Created Date</p>
                        <p class="text-base font-bold text-gray-900">
                            {{ $report->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Cost Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-xs text-gray-600 uppercase mb-1">BKT</p>
                        <p class="text-xl font-bold text-blue-600">
                            Rp {{ number_format(($report->grand_total + $report->grand_total / 2) / 7, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-xs text-gray-600 uppercase mb-1">Biaya Langsung</p>
                        <p class="text-xl font-bold text-green-600">
                            Rp {{ number_format($report->grand_total, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-xs text-gray-600 uppercase mb-1">Biaya Tidak Langsung</p>
                        <p class="text-xl font-bold text-yellow-600">
                            Rp {{ number_format($report->grand_total / 2, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Activities Details -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 overflow-hidden">
                <!-- Activities Header -->
                <div class="bg-blue-600 px-6 py-3">
                    <h3 class="text-base font-bold text-white">
                        Activities - Total: Rp {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}
                    </h3>
                </div>

                <!-- Activities Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    NO</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    AKTIVITAS</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    VOLUME</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    SATUAN</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    HARGA SATUAN</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    TOTAL</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    BEBAN</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                    UNIT COST</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">CATATAN
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @forelse($report->activityDetails as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border-r text-sm">{{ $no++ }}</td>
                                    <td class="px-4 py-3 border-r text-sm font-medium">{{ $activity->activity_name }}
                                    </td>
                                    <td class="px-4 py-3 border-r text-sm text-right">
                                        {{ number_format($activity->volume, 1) }}</td>
                                    <td class="px-4 py-3 border-r text-sm">{{ $activity->unit->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border-r text-sm text-right">
                                        {{ number_format($activity->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 border-r text-sm text-right font-semibold">
                                        {{ number_format($activity->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 border-r text-sm text-right">
                                        {{ $activity->allocation ?? '-' }}</td>
                                    <td class="px-4 py-3 border-r text-sm text-right">
                                        {{ number_format($activity->unit_cost, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $activity->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No activities found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
