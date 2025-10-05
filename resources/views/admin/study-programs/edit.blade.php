<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Study Program') }}
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
                    <form method="POST" action="{{ route('admin.study-programs.update', $studyProgram) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Study Program Code -->
                        <div>
                            <label for="sp_code" class="block text-sm font-medium text-gray-700">
                                Study Program Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="sp_code"
                                id="sp_code"
                                value="{{ old('sp_code', $studyProgram->sp_code) }}"
                                required
                                maxlength="10"
                                class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('sp_code') border-red-500 @enderror">
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
                                value="{{ old('sp_name', $studyProgram->sp_name) }}"
                                required
                                class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('sp_name') border-red-500 @enderror">
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
                                class="mt-1 block w-full rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('faculty_id') border-red-500 @enderror">
                                <option value="">Select Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        {{ old('faculty_id', $studyProgram->faculty_id) == $faculty->id ? 'selected' : '' }}>
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
                                class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('degree_id') border-red-500 @enderror">
                                <option value="">Select Degree</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->id }}"
                                        {{ old('degree_id', $studyProgram->degree_id) == $degree->id ? 'selected' : '' }}>
                                        {{ $degree->degree_code }} - {{ $degree->degree_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('degree_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        @if($studyProgram->users->count() > 0 || $studyProgram->reports->count() > 0)
                            <div class="rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-blue-700">
                                            This study program has {{ $studyProgram->users->count() }} user(s) and {{ $studyProgram->reports->count() }} report(s). Be careful when editing.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                Update Study Program
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
