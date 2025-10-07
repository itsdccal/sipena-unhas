<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Kelola Akun</h2>
                    <p class="mt-1 text-sm text-gray-600">Tambah, Edit, dan Kelola Akun Pengguna</p>
                </div>
                <a href="{{ route('admin.accounts.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Akun
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-visible shadow-sm rounded-lg border border-gray-200 mb-6" x-data="searchFilters()">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.accounts.index') }}" x-ref="filterForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                        <!-- Search with Suggestions -->
                        <div class="md:col-span-2 relative">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Pengguna
                            </label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    x-model="searchQuery"
                                    @input="filterSuggestions()"
                                    @focus="handleFocus()"
                                    @blur="handleBlur()"
                                    @keydown.escape="closeSuggestions()"
                                    @keydown.arrow-down.prevent="focusNext()"
                                    @keydown.arrow-up.prevent="focusPrev()"
                                    placeholder="Cari nama atau NIP pengguna..."
                                    autocomplete="off"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 py-2.5 transition duration-150">
                                <button type="button" x-show="searchQuery.length > 0"
                                    @click="clearSearch()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Suggestions Dropdown -->
                                <div x-show="showSuggestions && filteredSuggestions.length > 0"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden z-50">

                                    <div class="sticky top-0 px-4 py-2.5 bg-white border-b-2 border-gray-300 text-xs text-gray-600 font-medium z-10">
                                        <span x-text="filteredSuggestions.length"></span> pengguna ditemukan
                                    </div>

                                    <div class="max-h-80 overflow-y-auto">
                                        <template x-for="(item, index) in filteredSuggestions" :key="item.id">
                                            <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition duration-150"
                                                :class="selectedIndex === index ? 'bg-blue-50' : ''"
                                                @click="selectSuggestion(item)">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="text-sm font-medium text-gray-900" x-text="item.name"></div>
                                                        <div class="text-xs text-gray-500">
                                                            <span x-text="item.nip"></span>
                                                            <span class="mx-1">•</span>
                                                            <span x-text="item.role" class="capitalize"></span>
                                                        </div>
                                                    </div>
                                                    <svg class="ml-2 h-4 w-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-medium">Tidak ada pengguna ditemukan</p>
                                        <p class="text-xs mt-1">Coba kata kunci yang berbeda</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select id="role" name="role" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>

                        <!-- Study Program Filter -->
                        <div>
                            <label for="study_program" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <div class="relative" x-data="{ open: false, selected: '{{ request('study_program') }}', query: '' }">
                                <button type="button" @click="open = !open"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm hover:border-gray-400">
                                    <span class="block truncate" x-text="selected ? getStudyProgramName(selected) : 'Semua Program Studi'"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="study_program" :value="selected">

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    class="absolute mt-2 w-full bg-white shadow-xl rounded-lg ring-1 ring-black ring-opacity-5 overflow-hidden z-50">

                                    <div class="sticky top-0 bg-white p-2 border-b-2 border-gray-300 z-10">
                                        <input type="text" x-model="query" placeholder="Cari program studi..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div class="max-h-60 overflow-y-auto py-1">
                                        <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50" @click="selected = ''; open = false">
                                            <span class="block truncate">Semua Program Studi</span>
                                            <span x-show="selected === ''" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>

                                        @foreach ($studyPrograms as $program)
                                            <div class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 hover:bg-blue-50"
                                                x-show="query === '' || '{{ strtolower($program->sp_name) }}'.includes(query.toLowerCase())"
                                                @click="selected = '{{ $program->id }}'; open = false">
                                                <div class="flex flex-col">
                                                    <span class="block text-sm text-gray-900 font-medium">{{ $program->sp_name }}</span>
                                                    <span class="block text-xs text-gray-500">{{ $program->sp_code }}</span>
                                                </div>
                                                <span x-show="selected === '{{ $program->id }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <template x-teleport="body">
                                    <script>
                                        function getStudyProgramName(id) {
                                            if (!id) return 'Semua Program Studi';
                                            const programs = @json($studyPrograms ?? []);
                                            const program = programs.find(p => p.id == id);
                                            return program ? program.sp_name : 'Semua Program Studi';
                                        }
                                    </script>
                                </template>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="md:col-span-5 flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Pengguna
                            </button>

                            <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center px-6 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>

                            <!-- Active Filter Badge -->
                            <div class="ml-auto" x-show="'{{ request('search') }}' || '{{ request('role') }}' || '{{ request('study_program') }}' || '{{ request('status') }}'">
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

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                @if ($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->nip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->studyProgram?->sp_name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $user->status ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.accounts.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengguna ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pengguna baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function searchFilters() {
                return {
                    searchQuery: '{{ request('search', '') }}',
                    showSuggestions: false,
                    suggestions: @json($allUsers ?? []),
                    filteredSuggestions: [],
                    searchTimeout: null,
                    selectedIndex: -1,
                    isTyping: false,

                    init() {
                        this.isTyping = false;
                    },

                    filterSuggestions() {
                        this.isTyping = true;

                        if (this.searchQuery.length < 2) {
                            this.filteredSuggestions = [];
                            this.showSuggestions = false;
                            return;
                        }

                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            const query = this.searchQuery.toLowerCase();
                            this.filteredSuggestions = this.suggestions.filter(user => {
                                return user && (
                                    (user.name || '').toLowerCase().includes(query) ||
                                    (user.nip || '').toLowerCase().includes(query) ||
                                    (user.role || '').toLowerCase().includes(query)
                                );
                            }).slice(0, 8);

                            this.selectedIndex = -1;
                            this.showSuggestions = this.isTyping && (this.filteredSuggestions.length > 0 || this.searchQuery.length >= 2);
                        }, 300);
                    },

                    handleFocus() {
                        if (this.searchQuery.length >= 2 && this.isTyping && this.filteredSuggestions.length > 0) {
                            this.showSuggestions = true;
                        }
                    },

                    handleBlur() {
                        setTimeout(() => {
                            this.showSuggestions = false;
                            this.isTyping = false;
                        }, 250);
                    },

                    selectSuggestion(item) {
                        this.searchQuery = item.name;
                        this.showSuggestions = false;
                        this.isTyping = false;
                        document.getElementById('search').value = item.name;
                        setTimeout(() => this.$refs.filterForm.submit(), 100);
                    },

                    clearSearch() {
                        this.searchQuery = '';
                        this.filteredSuggestions = [];
                        this.showSuggestions = false;
                        this.isTyping = false;
                    },

                    closeSuggestions() {
                        this.showSuggestions = false;
                        this.isTyping = false;
                    },

                    focusNext() {
                        if (this.filteredSuggestions.length > 0) {
                            this.selectedIndex = (this.selectedIndex + 1) % this.filteredSuggestions.length;
                        }
                    },

                    focusPrev() {
                        if (this.filteredSuggestions.length > 0) {
                            this.selectedIndex = this.selectedIndex <= 0 ? this.filteredSuggestions.length - 1 : this.selectedIndex - 1;
                        }
                    },

                    getStudyProgramName(id) {
                        if (!id) return 'Semua Program Studi';
                        const programs = @json($studyPrograms ?? []);
                        const program = programs.find(p => p.id == id);
                        return program ? program.sp_name : 'Semua Program Studi';
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
