<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Faculties Management') }}
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
                <a href="{{ route('admin.faculties.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah data Fakultas
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

            <div class="inline-flex rounded-md shadow-xs mb-5 shadow-sm" role="group">
                <a href="{{ route('admin.faculties.index') }}"
                    class="px-4 py-2 text-sm font-medium cursor-pointer text-blue-700 bg-white border border-gray-200 rounded-s-lg hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Fakultas
                </a>
                <a href="{{ route('admin.degrees.index') }}"
                    class="px-4 py-2 text-sm font-medium cursor-pointer text-gray-500 bg-gray-200 border-t border-b border-gray-200 hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Jenjang
                </a>
                <a href="{{ route('admin.study-programs.index') }}"
                    class="px-4 py-2 text-sm font-medium cursor-pointer text-gray-500 bg-gray-200 border border-gray-200 rounded-e-lg hover:bg-white hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                    Program Studi
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-visible shadow-sm rounded-lg border border-gray-200 mb-6"
                x-data="searchFilters()" @click.away="showSuggestions = false">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.faculties.index') }}" x-ref="filterForm"
                        id="faculties-filter-form" class="grid grid-cols-1 gap-4">

                        <!-- Search with Suggestions -->
                        <div class="relative">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Fakultas
                            </label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    x-model="searchQuery" @input="filterSuggestions()" @focus="showSuggestions = true"
                                    @blur="setTimeout(() => showSuggestions = false, 250)"
                                    @keydown.escape="showSuggestions = false"
                                    @keydown.arrow-down.prevent="focusNextSuggestion()"
                                    @keydown.arrow-up.prevent="focusPrevSuggestion()"
                                    placeholder="Cari nama atau kode fakultas..." autocomplete="off" spellcheck="false"
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
                                        <span x-text="filteredSuggestions.length"></span> fakultas ditemukan
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
                                                            x-text="suggestion.faculty_name || 'N/A'"></div>
                                                        <div
                                                            class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-medium"
                                                                x-text="suggestion.faculty_code || 'N/A'"></span>
                                                            <span
                                                                x-text="(suggestion.study_programs_count || 0) + ' Program Studi'"></span>
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
                                        <p class="text-sm font-medium">Tidak ada fakultas ditemukan</p>
                                        <p class="text-xs mt-1">Coba kata kunci yang berbeda</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Fakultas
                            </button>
                            <a href="{{ route('admin.faculties.index') }}"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filter
                            </a>

                            <!-- Active Filters Indicator -->
                            <div class="ml-auto flex items-center gap-2" x-show="'{{ request('search') }}'">
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

            <!-- Study faculties Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                @if ($faculties->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Fakultas</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Program Studi</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($faculties as $faculty)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $faculty->faculty_code }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $faculty->faculty_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $faculty->studyPrograms->count() }}
                                                program studi</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.faculties.edit', $faculty) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                @if ($faculty->studyPrograms->count() == 0)
                                                    <form method="POST"
                                                        action="{{ route('admin.faculties.destroy', $faculty) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this faculty?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">
                                                            Delete
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
                        {{ $faculties->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data fakultas yang ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulailah dengan membuat data fakultas baru.</p>
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
                        suggestions: @json($allFaculties ?? []),
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
                                    this.filteredSuggestions = this.suggestions.filter(faculty => {
                                        if (!faculty) return false;

                                        const query = this.searchQuery.toLowerCase();
                                        const facultyName = (faculty.faculty_name || '').toLowerCase();
                                        const facultyCode = (faculty.faculty_code || '').toLowerCase();

                                        return facultyName.includes(query) || facultyCode.includes(query);
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
                                if (!suggestion || !suggestion.faculty_name) return;

                                this.searchQuery = suggestion.faculty_name;
                                this.showSuggestions = false;
                                this.isTyping = false;

                                const searchInput = document.getElementById('search');
                                if (searchInput) {
                                    searchInput.value = suggestion.faculty_name;
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
                                    const form = document.getElementById('faculties-filter-form');
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
                        }
                    }
                }

                window.searchFilters = searchFilters;
            })();
        </script>
    @endpush
</x-app-layout>
