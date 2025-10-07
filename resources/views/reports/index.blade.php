<x-app-layout>

    <div class="py-12" x-data="reportPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight"></h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('reports.export') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>

                    <button type="button" @click="$dispatch('open-modal', 'add-semester-modal')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Semester
                    </button>
                </div>
            </div>
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if ($reports->isEmpty())
                <!-- Empty State -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Laporan</h3>
                    <p class="text-sm text-gray-500 mb-6">Buat Laporan Terlebih Dahulu</p>
                </div>
            @else
                <!-- Report Header Info -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Report Detail</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Study Program</p>
                            <p class="text-base font-bold text-gray-900">
                                {{ $reports->first()->studyProgram->sp_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Jenjang</p>
                            <p class="text-base font-bold text-gray-900">
                                {{ $reports->first()->studyProgram->degree->degree_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Kurikulum</p>
                            <p class="text-base font-bold text-gray-900">{{ $reports->count() }} Semester(s)</p>
                        </div>
                    </div>

                    <!-- Cost Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-600 uppercase mb-1">BKT</p>
                            <p class="text-xl font-bold text-blue-600">
                                Rp
                                {{ number_format(($reports->sum('grand_total') + $reports->sum('grand_total') / 2) / 7, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-600 uppercase mb-1">Biaya Langsung</p>
                            <p class="text-xl font-bold text-green-600">
                                Rp {{ number_format($reports->sum('grand_total'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-xs text-gray-600 uppercase mb-1">Biaya Tidak Langsung</p>
                            <p class="text-xl font-bold text-yellow-600">
                                Rp {{ number_format($reports->sum('grand_total') / 2, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Semesters & Activities -->
                @foreach ($reports as $report)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 overflow-hidden">
                        <!-- Semester Header -->
                        <div class="bg-blue-600 px-6 py-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-white">
                                    {{ $report->semester->semester_name ?? 'Semester' }} - Total:
                                    <span class="font-bold">Rp
                                        {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}</span>
                                </h3>
                            </div>
                            <form method="POST" action="{{ route('reports.destroy', $report) }}" class="inline"
                                onsubmit="return confirm('Delete this semester?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white hover:text-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <!-- Activities Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            NO</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            AKTIVITAS</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            VOLUME</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            SATUAN</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            HARGA SATUAN</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            TOTAL</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            BEBAN</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            UNIT COST</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase border-r">
                                            KETERANGAN</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase border-l">
                                            ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $no = 1; @endphp
                                    @forelse($report->activityDetails as $activity)
                                        <!-- Main Activity -->
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 border-r text-sm">{{ $no++ }}</td>
                                            <td class="px-4 py-3 border-r text-sm font-medium">
                                                {{ $activity->activity_name }}</td>
                                            <td class="px-4 py-3 border-r text-sm text-right">
                                                {{ number_format($activity->volume, 1) }}</td>
                                            <td class="px-4 py-3 border-r text-sm">{{ $activity->unit->name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 border-r text-sm text-right">
                                                {{ number_format($activity->unit_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 border-r text-sm text-right font-semibold">
                                                {{ number_format($activity->total, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 border-r text-sm text-right">
                                                {{ $activity->allocation ?? '-' }}</td>
                                            <td class="px-4 py-3 border-r text-sm text-right">
                                                {{ number_format($activity->unit_cost, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 border-r text-sm">{{ $activity->notes ?? '-' }}</td>
                                            <td class="px-4 py-3 border-l text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <!-- Edit Button -->
                                                    <button type="button"
                                                        @click="openEditActivityModal({{ $activity->id }}, {{ json_encode($activity) }})"
                                                        class="p-1 text-blue-600 hover:text-blue-800" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form method="POST"
                                                        action="{{ route('reports.activities.destroy', $activity) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Delete this activity?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1 text-red-600 hover:text-red-800"
                                                            title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Sub Activities -->
                                        {{-- @foreach ($activity->subActivities ?? [] as $sub)
                                            <tr class="bg-blue-50 hover:bg-blue-100">
                                                <td class="px-4 py-3 border-r text-sm"></td>
                                                <td class="px-4 py-3 border-r text-sm pl-8 italic">
                                                    {{ $sub->sub_activity_name }}</td>
                                                <td class="px-4 py-3 border-r text-sm text-right">
                                                    {{ number_format($sub->volume, 1) }}</td>
                                                <td class="px-4 py-3 border-r text-sm">{{ $sub->unit_satuan ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 border-r text-sm text-right">
                                                    {{ number_format($sub->unit_price, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 border-r text-sm text-right font-semibold">
                                                    {{ number_format($sub->total, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 border-r text-sm text-right">
                                                    {{ $sub->allocation ?? '-' }}</td>
                                                <td class="px-4 py-3 border-r text-sm text-right">
                                                    {{ number_format($sub->unit_cost, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 border-r text-sm">-</td>
                                                <td class="px-4 py-3 border-l text-center">
                                                    <form method="POST"
                                                        action="{{ route('reports.sub-activities.destroy', $sub) }}"
                                                        class="inline" onsubmit="return confirm('Delete?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1 text-red-600 hover:text-red-800"
                                                            title="Delete">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-4 py-8 text-center text-sm text-gray-500">
                                                No activities yet.
                                            </td>
                                        </tr>
                                    @endforelse

                                    <!-- Add Activity Button -->
                                    <tr class="bg-gray-50">
                                        <td colspan="10" class="px-4 py-4 text-center">
                                            <button type="button" @click="openAddActivityModal({{ $report->id }})"
                                                class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah Aktivitas
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- MODALS -->
        @include('reports.partials.add-semester-modal')
        @include('reports.partials.add-activity-modal')
        @include('reports.partials.edit-activity-modal')
        @include('reports.partials.add-sub-activity-modal')
    </div>
    @push('scripts')
        <script>
            function reportPage() {
                return {
                    currentReportId: null,
                    currentActivityId: null,
                    loading: false,

                    // Activity Form Data
                    activity: {
                        activity_name: '',
                        unit_id: '',
                        volume: 0,
                        unit_price: 0,
                        total: 0,
                        allocation: 0,
                        unit_cost: 0,
                        notes: ''
                    },

                    // Edit Activity Form Data
                    editActivity: {
                        activity_name: '',
                        unit_id: '',
                        volume: 0,
                        unit_price: 0,
                        total: 0,
                        allocation: 0,
                        unit_cost: 0,
                        notes: ''
                    },

                    openAddActivityModal(reportId) {
                        this.currentReportId = reportId;
                        this.activity = {
                            activity_name: '',
                            unit_id: '',
                            volume: 0,
                            unit_price: 0,
                            total: 0,
                            allocation: 0,
                            unit_cost: 0,
                            notes: ''
                        };
                        this.$dispatch('open-modal', 'add-activity-modal');
                    },

                    openEditActivityModal(activityId, activityData) {
                        this.currentActivityId = activityId;
                        this.editActivity = {
                            activity_name: activityData.activity_name || '',
                            unit_id: activityData.unit_id || '',
                            volume: parseFloat(activityData.volume) || 0,
                            unit_price: parseFloat(activityData.unit_price) || 0,
                            total: parseFloat(activityData.total) || 0,
                            allocation: parseFloat(activityData.allocation) || 0,
                            unit_cost: parseFloat(activityData.unit_cost) || 0,
                            notes: activityData.notes || ''
                        };
                        this.$dispatch('open-modal', 'edit-activity-modal');
                    },

                    async submitActivity() {
                        if (!this.currentReportId) {
                            alert('Report ID tidak ditemukan');
                            return;
                        }

                        this.loading = true;
                        const formData = new FormData(document.getElementById('addActivityForm'));

                        try {
                            const response = await fetch(`/reports/${this.currentReportId}/activities`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Gunakan close-modal yang sama seperti add-semester
                                this.$dispatch('close-modal', 'add-activity-modal');
                                location.reload();
                            } else {
                                console.error('Server response:', data);
                                alert(data.message || 'Terjadi kesalahan saat menyimpan');
                            }
                        } catch (error) {
                            console.error('Network error:', error);
                            alert('Terjadi kesalahan jaringan');
                        } finally {
                            this.loading = false;
                        }
                    },

                    async submitEditActivity() {
                        if (!this.currentActivityId) {
                            alert('Activity ID tidak ditemukan');
                            return;
                        }

                        this.loading = true;
                        const formData = new FormData(document.getElementById('editActivityForm'));
                        formData.append('_method', 'PUT');

                        try {
                            const response = await fetch(`/reports/activities/${this.currentActivityId}`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Gunakan close-modal yang sama seperti add-semester
                                this.$dispatch('close-modal', 'edit-activity-modal');
                                location.reload();
                            } else {
                                console.error('Server response:', data);
                                alert(data.message || 'Terjadi kesalahan saat mengupdate');
                            }
                        } catch (error) {
                            console.error('Network error:', error);
                            alert('Terjadi kesalahan jaringan');
                        } finally {
                            this.loading = false;
                        }
                    },

                    closeModal() {
                        // Reset form data
                        this.activity = {
                            activity_name: '',
                            unit_id: '',
                            volume: 0,
                            unit_price: 0,
                            total: 0,
                            allocation: 0,
                            unit_cost: 0,
                            notes: ''
                        };

                        // Close modal
                        this.$dispatch('close');
                    },

                    calculateActivity() {
                        const volume = parseFloat(this.activity.volume) || 0;
                        const unitPrice = parseFloat(this.activity.unit_price) || 0;
                        const allocation = parseFloat(this.activity.allocation) || 0;

                        this.activity.total = volume * unitPrice;
                        this.activity.unit_cost = allocation > 0 ? this.activity.total / allocation : 0;
                    },

                    calculateEditActivity() {
                        const volume = parseFloat(this.editActivity.volume) || 0;
                        const unitPrice = parseFloat(this.editActivity.unit_price) || 0;
                        const allocation = parseFloat(this.editActivity.allocation) || 0;

                        this.editActivity.total = volume * unitPrice;
                        this.editActivity.unit_cost = allocation > 0 ? this.editActivity.total / allocation : 0;
                    },

                    formatCurrency(val) {
                        const value = parseFloat(val) || 0;
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
