<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Template Aktivitas</h2>
            <a href="{{ route('admin.activity-templates.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.activity-templates.update', $activityTemplate) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Activity Name -->
                        <div>
                            <label for="activity_name" class="block text-sm font-medium text-gray-700">
                                Nama Aktivitas <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="activity_name"
                                id="activity_name"
                                value="{{ old('activity_name', $activityTemplate->activity_name) }}"
                                required
                                maxlength="255"
                                placeholder="Contoh: Etika medik/kolegal (2 SKS x 16 TM)"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('activity_name') border-red-500 @enderror">
                            @error('activity_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Deskripsi
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                placeholder="Deskripsi atau catatan tambahan (opsional)"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $activityTemplate->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                    name="is_active"
                                    id="is_active"
                                    value="1"
                                    {{ old('is_active', $activityTemplate->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aktif (tersedia untuk dipilih staff)</span>
                            </label>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.activity-templates.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
