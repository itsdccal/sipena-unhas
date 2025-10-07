<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Manajemen Laporan</h2>
                    <p class="mt-1 text-sm text-gray-600">Lihat dan kelola semua laporan sistem</p>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-visible shadow-sm rounded-lg border border-gray-200 mb-6"
                x-data="searchFilters()">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.reports.index') }}" x-ref="filterForm"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <!-- Search with Suggestions -->
                        <div class="md:col-span-2 relative">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Program Studi
                            </label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    x-model="searchQuery"
                                    @input="filterSuggestions()"
                                    @focus="showSuggestions = true"
                                    @blur="setTimeout(() => showSuggestions = false, 200)"
                                    @keydown.escape="showSuggestions = false"
                                    @keydown.arrow-down.prevent="focusNextSuggestion()"
                                    @keydown.arrow-up.prevent="focusPrevSuggestion()"
                                    placeholder="Cari nama, kode, atau fakultas..."
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm  pr-10 py-2.5 transition duration-150">

                                <!-- Clear Button -->
                                <button type="button"
                                    x-show="searchQuery.length > 0"
                                    @click="searchQuery = ''; filteredSuggestions = []"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Search Suggestions Dropdown - FIXED WIDTH -->
                                <div x-show="showSuggestions && filteredSuggestions.length > 0"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-80 overflow-y-auto z-50">

                                    <!-- Suggestion Count -->
                                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200 text-xs text-gray-600 font-medium">
                                        <span x-text="filteredSuggestions.length"></span> hasil ditemukan
                                    </div>

                                    <template x-for="(suggestion, index) in filteredSuggestions" :key="suggestion.id">
                                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition duration-150"
                                            :class="{ 'bg-blue-50': index === selectedIndex }"
                                            @click="selectSuggestion(suggestion)"
                                            @mouseenter="selectedIndex = index">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-gray-900 truncate" x-text="suggestion.sp_name"></div>
                                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-medium" x-text="suggestion.sp_code"></span>
                                                        <span x-text="suggestion.degree?.degree_name"></span>
                                                        <span class="text-gray-400">•</span>
                                                        <span x-text="suggestion.faculty?.faculty_name" class="truncate"></span>
                                                    </div>
                                                </div>
                                                <svg class="ml-2 h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- No Results -->
                                <div x-show="showSuggestions && searchQuery.length >= 2 && filteredSuggestions.length === 0"
                                    x-transition
                                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-4 z-50">
                                    <div class="text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-medium">Tidak ada hasil ditemukan</p>
                                        <p class="text-xs mt-1">Coba kata kunci yang berbeda</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Faculty Filter - FIXED WIDTH -->
                        <div>
                            <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Fakultas
                            </label>
                            <div class="relative" x-data="{ open: false, selected: '{{ request('faculty') }}', query: '' }">
                                <button type="button" @click="open = !open"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm hover:border-gray-400 transition duration-150">
                                    <span class="block truncate"
                                        x-text="selected ? getFacultyName(selected) : 'Semua Fakultas'"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-150"
                                            :class="{ 'rotate-180': open }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="faculty" :value="selected">

                                <div x-show="open"
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white shadow-xl max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm z-50">

                                    <!-- Search input -->
                                    <div class="sticky top-0 bg-white p-2 border-b border-gray-200">
                                        <input type="text"
                                            x-model="query"
                                            placeholder="Cari fakultas..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- All option -->
                                    <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50 transition duration-150"
                                        @click="selected = ''; open = false">
                                        <span class="block truncate font-normal">Semua Fakultas</span>
                                        <span x-show="selected === ''"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>

                                    <!-- Faculty options -->
                                    @foreach ($faculties as $faculty)
                                        <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50 transition duration-150"
                                            x-show="query === '' || '{{ strtolower($faculty->faculty_name) }}'.includes(query.toLowerCase())"
                                            @click="selected = '{{ $faculty->id }}'; open = false">
                                            <span class="block truncate font-normal">{{ $faculty->faculty_name }}</span>
                                            <span x-show="selected === '{{ $faculty->id }}'"
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Degree Filter - FIXED WIDTH -->
                        <div>
                            <label for="degree" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                Jenjang
                            </label>
                            <div class="relative" x-data="{ open: false, selected: '{{ request('degree') }}', query: '' }">
                                <button type="button" @click="open = !open"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm hover:border-gray-400 transition duration-150">
                                    <span class="block truncate"
                                        x-text="selected ? getDegreeName(selected) : 'Semua Jenjang'"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-150"
                                            :class="{ 'rotate-180': open }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="degree" :value="selected">

                                <div x-show="open"
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white shadow-xl max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm z-50">

                                    <!-- Search input -->
                                    <div class="sticky top-0 bg-white p-2 border-b border-gray-200">
                                        <input type="text"
                                            x-model="query"
                                            placeholder="Cari jenjang..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- All option -->
                                    <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50 transition duration-150"
                                        @click="selected = ''; open = false">
                                        <span class="block truncate font-normal">Semua Jenjang</span>
                                        <span x-show="selected === ''"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>

                                    <!-- Degree options -->
                                    @foreach ($degrees as $degree)
                                        <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50 transition duration-150"
                                            x-show="query === '' || '{{ strtolower($degree->degree_name) }}'.includes(query.toLowerCase())"
                                            @click="selected = '{{ $degree->id }}'; open = false">
                                            <span class="block truncate font-normal">{{ $degree->degree_name }}</span>
                                            <span x-show="selected === '{{ $degree->id }}'"
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex items-end gap-2 md:col-span-4">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('admin.reports.index') }}"
                                class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filter
                            </a>

                            <!-- Active Filters Indicator -->
                            <div class="ml-auto flex items-center gap-2"
                                x-show="'{{ request('search') }}' || '{{ request('faculty') }}' || '{{ request('degree') }}'">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                    </svg>
                                    Filter Aktif
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Study Programs List -->
            @if ($studyProgramsWithReports->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($studyProgramsWithReports as $program)
                        <div
                            class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <!-- Program Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $program->sp_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $program->degree->degree_name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500 font-mono">{{ $program->sp_code }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $program->reports_count }} Semester
                                        </span>
                                    </div>
                                </div>

                                <!-- Statistics -->
                                <div class="mb-4">
                                    <div class="text-sm text-gray-600 mb-1">Total Biaya Langsung</div>
                                    <div class="text-xl font-bold text-green-600">
                                        Rp {{ number_format($program->reports_sum_grand_total ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="pt-4 border-t border-gray-200">
                                    <a href="{{ route('admin.reports.show-program', $program->id) }}"
                                        class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Detail Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if (request('search'))
                            Tidak ada program studi yang ditemukan
                        @else
                            Belum Ada Program Studi dengan Laporan
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500">
                        @if (request('search'))
                            Coba kata kunci pencarian yang berbeda
                        @else
                            Program studi akan muncul setelah ada laporan yang dibuat
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function searchFilters() {
                return {
                    searchQuery: '{{ request('search', '') }}',
                    showSuggestions: false,
                    suggestions: @json($allStudyPrograms),
                    filteredSuggestions: [],
                    searchTimeout: null,
                    selectedIndex: -1,

                    init() {
                        this.filterSuggestions();
                    },

                    filterSuggestions() {
                        if (this.searchQuery.length < 2) {
                            this.filteredSuggestions = [];
                            return;
                        }

                        // Clear previous timeout
                        if (this.searchTimeout) {
                            clearTimeout(this.searchTimeout);
                        }

                        // Add debouncing to reduce search calls
                        this.searchTimeout = setTimeout(() => {
                            this.filteredSuggestions = this.suggestions.filter(program => {
                                const query = this.searchQuery.toLowerCase();
                                return program.sp_name.toLowerCase().includes(query) ||
                                    program.sp_code.toLowerCase().includes(query) ||
                                    (program.degree && program.degree.degree_name.toLowerCase().includes(query)) ||
                                    (program.faculty && program.faculty.faculty_name.toLowerCase().includes(query));
                            }).slice(0, 8); // Limit to 8 suggestions
                            this.selectedIndex = -1;
                        }, 300); // 300ms debounce
                    },

                    selectSuggestion(suggestion) {
                        this.searchQuery = suggestion.sp_name;
                        this.showSuggestions = false;
                        // Auto submit form
                        this.$refs.filterForm.submit();
                    },

                    focusNextSuggestion() {
                        if (this.filteredSuggestions.length > 0) {
                            this.selectedIndex = (this.selectedIndex + 1) % this.filteredSuggestions.length;
                        }
                    },

                    focusPrevSuggestion() {
                        if (this.filteredSuggestions.length > 0) {
                            this.selectedIndex = this.selectedIndex <= 0
                                ? this.filteredSuggestions.length - 1
                                : this.selectedIndex - 1;
                        }
                    },

                    getFacultyName(id) {
                        const faculties = @json($faculties);
                        const faculty = faculties.find(f => f.id == id);
                        return faculty ? faculty.faculty_name : 'Semua Fakultas';
                    },

                    getDegreeName(id) {
                        const degrees = @json($degrees);
                        const degree = degrees.find(d => d.id == id);
                        return degree ? degree.degree_name : 'Semua Jenjang';
                    }
                }
            }
        </script>

        <style>
            /* Ensure consistent dropdown styling and proper z-index */
            .z-50 {
                z-index: 50 !important;
            }

            /* Smooth scrollbar for dropdowns */
            .overflow-auto::-webkit-scrollbar {
                width: 6px;
            }

            .overflow-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .overflow-auto::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 3px;
            }

            .overflow-auto::-webkit-scrollbar-thumb:hover {
                background: #a0aec0;
            }

            /* Prevent dropdown from being clipped */
            .overflow-visible {
                overflow: visible !important;
            }
        </style>
    @endpush
</x-app-layout>
