<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Create New Report</h2>
                    <p class="mt-1 text-sm text-gray-600">Fill in the form below to create a new report</p>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" x-data="reportForm()">
                @csrf

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Report Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('title') border-red-500 @enderror"
                                    placeholder="e.g., Semester 1 Cost Report 2024/2025">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Study Program -->
                            <div>
                                <label for="study_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Study Program <span class="text-red-500">*</span>
                                </label>
                                <select id="study_program_id" name="study_program_id" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('study_program_id') border-red-500 @enderror"
                                    @if(auth()->user()->isUser() && auth()->user()->study_program_id) disabled @endif>
                                    <option value="">Select Study Program</option>
                                    @foreach($studyPrograms as $program)
                                        <option value="{{ $program->id }}"
                                            {{ old('study_program_id', auth()->user()->study_program_id) == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(auth()->user()->isUser() && auth()->user()->study_program_id)
                                    <input type="hidden" name="study_program_id" value="{{ auth()->user()->study_program_id }}">
                                @endif
                                @error('study_program_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select id="semester_id" name="semester_id" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('semester_id') border-red-500 @enderror">
                                    <option value="">Select Semester</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('semester_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Period -->
                            <div class="md:col-span-2">
                                <label for="period" class="block text-sm font-medium text-gray-700 mb-2">
                                    Period <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="period" name="period" value="{{ old('period') }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('period') border-red-500 @enderror"
                                    placeholder="e.g., 2024/2025 Semester 1">
                                @error('period')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Details -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Activity Details</h3>
                            <button type="button" @click="addActivity"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Activity
                            </button>
                        </div>

                        @error('activities')
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror

                        <div class="space-y-4">
                            <template x-for="(activity, index) in activities" :key="index">
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700" x-text="'Activity #' + (index + 1)"></h4>
                                        <button type="button" @click="removeActivity(index)" x-show="activities.length > 1"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                        <!-- Activity Name -->
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Activity Name *</label>
                                            <input type="text" :name="'activities[' + index + '][activity]'" x-model="activity.activity" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                placeholder="e.g., Kuliah MDU/MKK">
                                        </div>

                                        <!-- Volume -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Volume *</label>
                                            <input type="number" step="0.01" :name="'activities[' + index + '][volume]'"
                                                x-model="activity.volume" @input="calculateTotal(index)" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        </div>

                                        <!-- Unit -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit *</label>
                                            <select :name="'activities[' + index + '][unit]'" x-model="activity.unit" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                <option value="KEG">KEG</option>
                                                <option value="SKS TM">SKS TM</option>
                                                <option value="OJ">OJ</option>
                                                <option value="JAM">JAM</option>
                                            </select>
                                        </div>

                                        <!-- Unit Price -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price *</label>
                                            <input type="number" step="0.01" :name="'activities[' + index + '][unit_price]'"
                                                x-model="activity.unit_price" @input="calculateTotal(index)" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        </div>

                                        <!-- Burden -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Burden</label>
                                            <input type="number" :name="'activities[' + index + '][burden]'"
                                                x-model="activity.burden" @input="calculateTotal(index)"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        </div>
                                    </div>

                                    <!-- Calculation -->
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700">Total:</span>
                                            <span class="font-bold text-gray-900" x-text="'Rp ' + formatNumber(activity.total)"></span>
                                        </div>
                                        <div class="flex justify-between text-sm mt-1" x-show="activity.burden > 0">
                                            <span class="font-medium text-gray-700">Unit Cost:</span>
                                            <span class="font-bold text-gray-900" x-text="'Rp ' + formatNumber(activity.unit_cost)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Grand Total -->
                        <div class="mt-6 pt-4 border-t-2 border-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="'Rp ' + formatNumber(grandTotal)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Documents (Optional) -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Supporting Documents (Optional)</h3>
                        <input type="file" name="documents[]" multiple accept=".pdf,.xlsx,.xls,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, Excel, Image. Max 10MB per file.</p>
                        @error('documents.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function reportForm() {
            return {
                activities: [
                    {
                        activity: '',
                        volume: 0,
                        unit: 'KEG',
                        unit_price: 0,
                        burden: 0,
                        total: 0,
                        unit_cost: 0
                    }
                ],

                get grandTotal() {
                    return this.activities.reduce((sum, activity) => sum + parseFloat(activity.total || 0), 0);
                },

                addActivity() {
                    this.activities.push({
                        activity: '',
                        volume: 0,
                        unit: 'KEG',
                        unit_price: 0,
                        burden: 0,
                        total: 0,
                        unit_cost: 0
                    });
                },

                removeActivity(index) {
                    if (this.activities.length > 1) {
                        this.activities.splice(index, 1);
                    }
                },

                calculateTotal(index) {
                    const activity = this.activities[index];
                    const volume = parseFloat(activity.volume) || 0;
                    const unitPrice = parseFloat(activity.unit_price) || 0;
                    const burden = parseInt(activity.burden) || 0;

                    activity.total = volume * unitPrice;
                    activity.unit_cost = burden > 0 ? activity.total / burden : 0;
                },

                formatNumber(value) {
                    return new Intl.NumberFormat('id-ID').format(value || 0);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
