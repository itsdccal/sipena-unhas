<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">My Reports</h2>
                <button type="button"
                    x-data
                    @click="$dispatch('open-modal', 'create-report')"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    + Create New Report
                </button>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Reports Table -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Study Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grand Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $reportsByYear = $reports->groupBy(function($report) {
                                return $report->semester->academic_year ?? 'Unknown';
                            });
                            $globalNo = 1;
                        @endphp

                        @forelse($reportsByYear as $academicYear => $yearReports)
                            <!-- Academic Year Header Row -->
                            <tr class="bg-blue-600">
                                <td colspan="6" class="px-6 py-3 text-sm font-bold text-white">
                                    ACADEMIC YEAR {{ $academicYear }}
                                </td>
                            </tr>

                            <!-- Reports for this year -->
                            @foreach($yearReports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $globalNo++ }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $report->studyProgram->sp_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $report->semester->semester_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        Rp {{ number_format($report->grand_total ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('reports.show', $report) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('reports.destroy', $report) }}"
                                            class="inline" onsubmit="return confirm('Delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                    No reports found. Click "Create New Report" to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Report Modal (Basic Info Only) -->
    <x-modal name="create-report" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Create New Report</h2>

            <form method="POST" action="{{ route('reports.store') }}">
                @csrf

                <div class="space-y-4">
                    <!-- Study Program -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Study Program <span class="text-red-500">*</span>
                        </label>
                        <select name="study_program_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Study Program</option>
                            @foreach($studyPrograms as $program)
                                <option value="{{ $program->id }}">
                                    {{ $program->sp_code }} - {{ $program->sp_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Semester & Academic Year <span class="text-red-500">*</span>
                        </label>
                        <select name="semester_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">
                                    {{ $semester->semester_name }} - {{ $semester->academic_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hidden grand_total (default 0) -->
                    <input type="hidden" name="grand_total" value="0">
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                        @click="$dispatch('close-modal', 'create-report')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                        Create Report
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
