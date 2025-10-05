<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Dashboard Admin</h2>
                <p class="mt-1 text-sm text-gray-600">Selamat Datang</p>
            </div>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Reports -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Laporan</dt>
                                    <dd class="text-3xl font-bold text-gray-900">{{ number_format($totalReports) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Active Users -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pengguna Aktif</dt>
                                    <dd class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Study Programs -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Program Studi</dt>
                                    <dd class="text-3xl font-bold text-gray-900">
                                        {{ number_format($totalStudyPrograms) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Reports per Study Program -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Per Program Studi</h3>
                        <div class="space-y-4">
                            @forelse($reportsByStudyProgram as $item)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-700 font-medium">{{ $item->study_program }}</span>
                                        <span class="text-gray-600">{{ $item->total }} Laporan</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ ($item->total / $totalReports) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Laporan Belum Tersedia
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Reports by Faculty -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Per Fakultas</h3>
                        <div class="space-y-3">
                            @forelse($reportsByFaculty as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">{{ $item->faculty }}</span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                        {{ $item->count }} Laporan
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Laporan Belum Tersedia</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
