<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Study Program') }}
            </h2>
            <a href="{{ route('admin.study-programs.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Study Programs
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.study-programs.store') }}" class="space-y-6">
                        @csrf

                        <!-- Study Program Code -->
                        <div>
                            <label for="sp_code" class="block text-sm font-medium text-gray-700">
                                Study Program Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="sp_code"
                                id="sp_code"
                                value="{{ old('sp_code') }}"
                                required
                                placeholder="e.g., KED-001"
                                maxlength="10"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('sp_code') border-red-500 @enderror">
                            @error('sp_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Unique code for this study program (max 10 characters)</p>
                        </div>

                        <!-- Study Program Name -->
                        <div>
                            <label for="sp_name" class="block text-sm font-medium text-gray-700">
                                Study Program Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="sp_name"
                                id="sp_name"
                                value="{{ old('sp_name') }}"
                                required
                                placeholder="e.g., Pendidikan Dokter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('sp_name') border-red-500 @enderror">
                            @error('sp_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Faculty -->
                        <div>
                            <label for="faculty_id" class="block text-sm font-medium text-gray-700">
                                Faculty <span class="text-red-500">*</span>
                            </label>
                            <select name="faculty_id"
                                id="faculty_id"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('faculty_id') border-red-500 @enderror">
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->faculty_code }} - {{ $faculty->faculty_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Degree -->
                        <div>
                            <label for="degree_id" class="block text-sm font-medium text-gray-700">
                                Degree Level <span class="text-red-500">*</span>
                            </label>
                            <select name="degree_id"
                                id="degree_id"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('degree_id') border-red-500 @enderror">
                                <option value="">Select Degree</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                                        {{ $degree->degree_code }} - {{ $degree->degree_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('degree_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.study-programs.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Create Study Program
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
