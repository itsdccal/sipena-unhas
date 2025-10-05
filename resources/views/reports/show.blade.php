<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Detail</h2>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Reports</a>
        </div>
    </x-slot>

    <div class="py-12" x-data="reportDetail()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Info -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 p-6">
                <div class="grid grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Study Program</p>
                        <p class="text-sm font-semibold">{{ $report->studyProgram->sp_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Jenjang</p>
                        <p class="text-sm font-semibold">{{ $report->studyProgram->degree->degree_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Kurikulum</p>
                        <p class="text-sm font-semibold">{{ $report->semester->semester_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Academic Year</p>
                        <p class="text-sm font-semibold">{{ $report->semester->academic_year ?? '-' }}</p>
                    </div>
                </div>

                <!-- Grand Total Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-1">BKT</p>
                        <p class="text-lg font-bold text-blue-600">Rp 0</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-1">Biaya Langsung</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded">
                        <p class="text-xs text-gray-600 uppercase mb-1">Biaya Tidak Langsung</p>
                        <p class="text-lg font-bold text-yellow-600">Rp 0</p>
                    </div>
                </div>
            </div>

            <!-- Activities Table -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">NO</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">AKTIVITAS</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">VOLUME</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">SATUAN</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">HARGA SATUAN</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">TOTAL</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">BEBAN</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-r">UNIT COST</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Semester Header -->
                            <tr class="bg-blue-600">
                                <td colspan="9" class="px-3 py-2 text-sm font-bold text-white">
                                    {{ $report->semester->semester_name ?? 'SEMESTER' }} - Total: Rp {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>

                            @php $no = 1; @endphp
                            @foreach($report->activityDetails as $activity)
                                <!-- Main Activity -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border-r">{{ $no++ }}</td>
                                    <td class="px-3 py-2 border-r font-medium">{{ $activity->activity_name }}</td>
                                    <td class="px-3 py-2 border-r text-right">{{ number_format($activity->volume, 1) }}</td>
                                    <td class="px-3 py-2 border-r">{{ $activity->unit->code ?? '-' }}</td>
                                    <td class="px-3 py-2 border-r text-right">{{ number_format($activity->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 border-r text-right font-semibold">{{ number_format($activity->total, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 border-r text-right">{{ $activity->allocation ?? '-' }}</td>
                                    <td class="px-3 py-2 border-r text-right">{{ number_format($activity->unit_cost, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button"
                                            @click="openAddSubModal({{ $activity->id }})"
                                            class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            + Sub
                                        </button>
                                    </td>
                                </tr>

                                <!-- Sub Activities -->
                                @foreach($activity->subActivities ?? [] as $sub)
                                    <tr class="bg-blue-50 hover:bg-blue-100">
                                        <td class="px-3 py-2 border-r"></td>
                                        <td class="px-3 py-2 border-r pl-8">{{ $sub->sub_activity_name }}</td>
                                        <td class="px-3 py-2 border-r text-right">{{ number_format($sub->volume, 1) }}</td>
                                        <td class="px-3 py-2 border-r">{{ $sub->unit_satuan ?? '-' }}</td>
                                        <td class="px-3 py-2 border-r text-right">{{ number_format($sub->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 border-r text-right font-semibold">{{ number_format($sub->total, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 border-r text-right">{{ $sub->allocation ?? '-' }}</td>
                                        <td class="px-3 py-2 border-r text-right">{{ number_format($sub->unit_cost, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <form method="POST" action="{{ route('reports.sub-activities.destroy', $sub) }}" class="inline" onsubmit="return confirm('Delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <!-- Add Activity Button Row -->
                            <tr>
                                <td colspan="9" class="px-3 py-4 text-center">
                                    <button type="button"
                                        @click="$dispatch('open-modal', 'add-activity-modal')"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                                        + Add Activity
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Activity Modal -->
        <x-modal name="add-activity-modal" maxWidth="2xl">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Add New Activity</h3>
                <form method="POST" action="{{ route('reports.activities.store', $report) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Activity Name *</label>
                            <input type="text" name="activity_name" required class="w-full border-gray-300 rounded text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Volume *</label>
                                <input type="number" name="volume" step="0.01" required class="w-full border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit</label>
                                <select name="unit_id" class="w-full border-gray-300 rounded text-sm">
                                    <option value="">Select</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit Price *</label>
                                <input type="number" name="unit_price" required class="w-full border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Beban</label>
                                <input type="number" name="allocation" class="w-full border-gray-300 rounded text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'add-activity-modal')" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Add Activity
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- Add Sub Activity Modal -->
        <x-modal name="add-sub-activity-modal" maxWidth="2xl">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Add Sub Activity</h3>
                <form method="POST" :action="`/reports/activities/${currentActivityId}/sub-activities`" x-show="currentActivityId">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Sub Activity Name *</label>
                            <input type="text" name="sub_activity_name" required class="w-full border-gray-300 rounded text-sm">
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Volume *</label>
                                <input type="number" name="volume" step="0.01" required class="w-full border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit</label>
                                <input type="text" name="unit_satuan" class="w-full border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit Price *</label>
                                <input type="number" name="unit_price" required class="w-full border-gray-300 rounded text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'add-sub-activity-modal')" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            Add Sub Activity
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    @push('scripts')
    <script>
        function reportDetail() {
            return {
                currentActivityId: null,

                openAddSubModal(activityId) {
                    this.currentActivityId = activityId;
                    this.$dispatch('open-modal', 'add-sub-activity-modal');
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
