<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Manajemen Template Aktivitas</h2>
                        <p class="mt-1 text-sm text-gray-600">Kelola master data aktivitas yang dapat dipilih oleh staff</p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.activity-templates.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Template Aktivitas
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filter & Search -->
            <div class="mb-6 bg-white shadow-sm rounded-lg border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.activity-templates.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Aktivitas</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Cari nama aktivitas...">
                    </div>
                    <div class="w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                        <a href="{{ route('admin.activity-templates.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Aktivitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activityTemplates as $index => $template)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activityTemplates->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $template->activity_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ Str::limit($template->description, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($template->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.activity-templates.edit', $template) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.activity-templates.destroy', $template) }}"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus template aktivitas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada template aktivitas ditemukan</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat template aktivitas pertama Anda.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.activity-templates.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Template Aktivitas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($activityTemplates->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $activityTemplates->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
