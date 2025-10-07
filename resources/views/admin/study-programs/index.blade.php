<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Study Program Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Data Akademik</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola data fakultas, jenjang, dan program studi</p>
                </div>
                <a href="{{ route('admin.study-programs.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Data Program Studi
                </a>
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

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="inline-flex rounded-md shadow-xs mb-5 shadow-sm" role="group">
                <a href="{{ route('admin.faculties.index') }}"
                    class="px-4 py-2 text-sm font-medium cursor-pointer text-gray-500 bg-gray-200 rounded-s-lg border-gray-200 hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Fakultas
                </a>
                <a href="{{ route('admin.degrees.index') }}"
                    class="px-4 py-2 text-sm font-medium cursor-pointer text-gray-500 bg-gray-200 border border-gray-200 hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Jenjang
                </a>
                <a href="{{ route('admin.study-programs.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-e-lg cursor-pointer text-blue-700 bg-white border border-gray-200 hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Program Studi
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-visible shadow-sm rounded-lg border border-gray-200 mb-6"
                x-data="searchFilters()" @click.away="showSuggestions = false">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.study-programs.index') }}" x-ref="filterForm"
                        id="study-programs-filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <!-- Search with Suggestions -->
                        <div class="md:col-span-2 relative">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Program Studi
                            </label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    x-model="searchQuery" @input="filterSuggestions()" @focus="showSuggestions = true"
                                    @blur="setTimeout(() => showSuggestions = false, 250)"
                                    @keydown.escape="showSuggestions = false"
                                    @keydown.arrow-down.prevent="focusNextSuggestion()"
                                    @keydown.arrow-up.prevent="focusPrevSuggestion()"
                                    placeholder="Cari nama, kode, fakultas, atau jenjang..." autocomplete="off"
                                    spellcheck="false"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 py-2.5 transition duration-150">


                                <!-- Clear Button -->
                                <button type="button" x-show="searchQuery.length > 0"
                                    @click="searchQuery = ''; filteredSuggestions = []"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Search Suggestions Dropdown -->
                                <div x-show="showSuggestions && filteredSuggestions.length > 0"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden z-50">

                                    <!-- Suggestion Count - STICKY Header -->
                                    <div class="sticky top-0 px-4 py-2.5 bg-white border-b-2 border-gray-300 text-xs text-gray-600 font-medium z-10">
                                        <span x-text="filteredSuggestions.length"></span> hasil ditemukan
                                    </div>

                                    <!-- Scrollable Content Area -->
                                    <div class="max-h-80 overflow-y-auto">
                                        <template x-for="(suggestion, index) in filteredSuggestions"
                                            :key="suggestion.id || index">
                                            <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition duration-150"
                                                :class="{ 'bg-blue-50': index === selectedIndex }"
                                                @click.stop="selectSuggestion(suggestion)"
                                                @mouseenter="selectedIndex = index">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-900 truncate"
                                                            x-text="suggestion.sp_name || 'N/A'"></div>
                                                        <div
                                                            class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-medium"
                                                                x-text="suggestion.sp_code || 'N/A'"></span>
                                                            <span x-text="suggestion.degree?.degree_name || 'N/A'"></span>
                                                            <span class="text-gray-400">•</span>
                                                            <span x-text="suggestion.faculty?.faculty_name || 'N/A'"
                                                                class="truncate"></span>
                                                        </div>
                                                    </div>
                                                    <svg class="ml-2 h-5 w-5 text-gray-400 flex-shrink-0" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- No Results -->
                                <div x-show="showSuggestions && searchQuery.length >= 2 && filteredSuggestions.length === 0"
                                    x-transition
                                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-4 z-50">
                                    <div class="text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-medium">Tidak ada hasil ditemukan</p>
                                        <p class="text-xs mt-1">Coba kata kunci yang berbeda</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Faculty Filter -->
                        <div>
                            <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                    <span
                                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-150"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="faculty" :value="selected">

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white shadow-xl rounded-lg ring-1 ring-black ring-opacity-5 overflow-hidden focus:outline-none sm:text-sm z-50">

                                    <!-- Search input - STICKY Header -->
                                    <div class="sticky top-0 bg-white p-2 border-b-2 border-gray-300 z-10">
                                        <input type="text" x-model="query" placeholder="Cari fakultas..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- Scrollable Content Area -->
                                    <div class="max-h-60 overflow-y-auto py-1">
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
                                                <span
                                                    class="block truncate font-normal">{{ $faculty->faculty_name }}</span>
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
                        </div>

                        <!-- Degree Filter -->
                        <div>
                            <label for="degree" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                Jenjang
                            </label>
                            <div class="relative" x-data="{ open: false, selected: '{{ request('degree') }}', query: '' }">
                                <button type="button" @click="open = !open"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm hover:border-gray-400 transition duration-150">
                                    <span class="block truncate"
                                        x-text="selected ? getDegreeName(selected) : 'Semua Jenjang'"></span>
                                    <span
                                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-150"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="degree" :value="selected">

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white shadow-xl rounded-lg ring-1 ring-black ring-opacity-5 overflow-hidden focus:outline-none sm:text-sm z-50">

                                    <!-- Search input - STICKY Header -->
                                    <div class="sticky top-0 bg-white p-2 border-b-2 border-gray-300 z-10">
                                        <input type="text" x-model="query" placeholder="Cari jenjang..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- Scrollable Content Area -->
                                    <div class="max-h-60 overflow-y-auto py-1">
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
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-4 flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('admin.study-programs.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filter
                            </a>

                            <!-- Active Filters Indicator -->
                            <div class="ml-auto flex items-center gap-2"
                                x-show="'{{ request('search') }}' || '{{ request('faculty') }}' || '{{ request('degree') }}'">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Filter Aktif
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Study Programs Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                @if ($studyPrograms->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Program Studi</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenjang</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fakultas</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Pengguna</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($studyPrograms as $program)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $program->sp_code }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $program->sp_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $program->degree->degree_name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $program->faculty->faculty_name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $program->users->count() }} users
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.study-programs.edit', $program) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                @if ($program->users->count() == 0 && $program->reports->count() == 0)
                                                    <form method="POST"
                                                        action="{{ route('admin.study-programs.destroy', $program) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this study program?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $studyPrograms->links('pagination::tailwind') }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Program Studi Tidak Ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Coba sesuaikan filter atau tambahkan data program studi
                            baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                'use strict';

                function searchFilters() {
                    return {
                        searchQuery: '{{ request('search', '') }}',
                        showSuggestions: false,
                        suggestions: @json($allStudyPrograms ?? []),
                        filteredSuggestions: [],
                        searchTimeout: null,
                        selectedIndex: -1,
                        isTyping: false,

                        init() {
                            this.isTyping = false;
                        },

                        filterSuggestions() {
                            if (!this.isTyping) {
                                this.isTyping = true;
                            }

                            if (this.searchQuery.length < 2) {
                                this.filteredSuggestions = [];
                                this.showSuggestions = false;
                                return;
                            }

                            if (this.searchTimeout) {
                                clearTimeout(this.searchTimeout);
                            }

                            this.searchTimeout = setTimeout(() => {
                                try {
                                    this.filteredSuggestions = this.suggestions.filter(program => {
                                        if (!program) return false;

                                        const query = this.searchQuery.toLowerCase();
                                        const spName = (program.sp_name || '').toLowerCase();
                                        const spCode = (program.sp_code || '').toLowerCase();
                                        const degreeName = (program.degree?.degree_name || '').toLowerCase();
                                        const facultyName = (program.faculty?.faculty_name || '').toLowerCase();

                                        return spName.includes(query) ||
                                            spCode.includes(query) ||
                                            degreeName.includes(query) ||
                                            facultyName.includes(query);
                                    }).slice(0, 8);
                                    this.selectedIndex = -1;
                                    this.showSuggestions = this.isTyping && (this.filteredSuggestions.length > 0 || this.searchQuery.length >= 2);
                                } catch (error) {
                                    console.error('Error filtering suggestions:', error);
                                    this.filteredSuggestions = [];
                                    this.showSuggestions = false;
                                }
                            }, 300);
                        },

                        handleFocus() {
                            if (this.searchQuery.length >= 2 && this.isTyping) {
                                this.showSuggestions = true;
                            }
                        },

                        handleBlur() {
                            setTimeout(() => {
                                this.showSuggestions = false;
                                this.isTyping = false;
                            }, 250);
                        },

                        selectSuggestion(suggestion) {
                            try {
                                if (!suggestion || !suggestion.sp_name) return;

                                this.searchQuery = suggestion.sp_name;
                                this.showSuggestions = false;
                                this.isTyping = false;

                                const searchInput = document.getElementById('search');
                                if (searchInput) {
                                    searchInput.value = suggestion.sp_name;
                                }

                                setTimeout(() => {
                                    this.submitForm();
                                }, 100);
                            } catch (error) {
                                console.error('Error selecting suggestion:', error);
                            }
                        },

                        submitForm() {
                            try {
                                this.isTyping = false;
                                if (this.$refs.filterForm) {
                                    this.$refs.filterForm.submit();
                                } else {
                                    const form = document.getElementById('study-programs-filter-form');
                                    if (form) form.submit();
                                }
                            } catch (error) {
                                console.error('Error submitting form:', error);
                            }
                        },

                        focusNextSuggestion() {
                            if (this.filteredSuggestions.length > 0) {
                                this.selectedIndex = (this.selectedIndex + 1) % this.filteredSuggestions.length;
                            }
                        },

                        focusPrevSuggestion() {
                            if (this.filteredSuggestions.length > 0) {
                                this.selectedIndex = this.selectedIndex <= 0 ?
                                    this.filteredSuggestions.length - 1 :
                                    this.selectedIndex - 1;
                            }
                        },

                        getFacultyName(id) {
                            try {
                                const faculties = @json($faculties ?? []);
                                const faculty = faculties.find(f => f.id == id);
                                return faculty ? faculty.faculty_name : 'Semua Fakultas';
                            } catch (error) {
                                console.error('Error getting faculty name:', error);
                                return 'Semua Fakultas';
                            }
                        },

                        getDegreeName(id) {
                            try {
                                const degrees = @json($degrees ?? []);
                                const degree = degrees.find(d => d.id == id);
                                return degree ? degree.degree_name : 'Semua Jenjang';
                            } catch (error) {
                                console.error('Error getting degree name:', error);
                                return 'Semua Jenjang';
                            }
                        }
                    }
                }

                window.searchFilters = searchFilters;
            })();
        </script>
    @endpush
</x-app-layout>
