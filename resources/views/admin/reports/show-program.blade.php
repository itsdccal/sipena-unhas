<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Laporan - {{ $studyProgram->sp_name }}
                </h2>
                <div>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Reports
                    </a>
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
                    <p class="text-sm text-gray-500 mb-6">Program studi ini belum memiliki laporan.</p>
                </div>
            @else
                <!-- Program Studi Header -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Informasi Program Studi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Nama Program Studi</p>
                            <p class="text-base font-bold text-gray-900">{{ $studyProgram->sp_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Jenjang</p>
                            <p class="text-base font-bold text-gray-900">{{ $studyProgram->degree->degree_name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Kode Program Studi</p>
                            <p class="text-base font-bold text-gray-900">{{ $studyProgram->sp_code }}</p>
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
                                <p class="text-blue-100 text-sm">
                                    Dibuat oleh: {{ $report->user->name ?? 'N/A' }} |
                                    {{ $report->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $report->activityDetails->count() }} aktivitas
                                </span>
                            </div>
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
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">
                                            CATATAN</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $no = 1; @endphp
                                    @forelse($report->activityDetails as $activity)
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
                                            <td class="px-4 py-3 text-sm">{{ $activity->notes ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                                Tidak ada aktivitas untuk semester ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
