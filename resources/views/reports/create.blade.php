<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Report') }}
            </h2>
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="reportForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('reports.store') }}" @submit="calculateTotals">
                @csrf

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Basic Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Study Program -->
                            <div>
                                <label for="study_program_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Study Program <span class="text-red-500">*</span>
                                </label>
                                <select id="study_program_id" name="study_program_id" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('study_program_id') border-red-500 @enderror"
                                    @if(auth()->user()->role === 'staff' && auth()->user()->study_program_id) disabled @endif>
                                    <option value="">Select Study Program</option>
                                    @foreach($studyPrograms as $program)
                                        <option value="{{ $program->id }}"
                                            {{ old('study_program_id', auth()->user()->study_program_id) == $program->id ? 'selected' : '' }}>
                                            {{ $program->sp_code }} - {{ $program->sp_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(auth()->user()->role === 'staff' && auth()->user()->study_program_id)
                                    <input type="hidden" name="study_program_id" value="{{ auth()->user()->study_program_id }}">
                                @endif
                                @error('study_program_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester (with Academic Year) -->
                            <div>
                                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester & Academic Year <span class="text-red-500">*</span>
                                </label>
                                <select id="semester_id" name="semester_id" required
                                    x-model="selectedSemester"
                                    @change="updateAcademicYear"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('semester_id') border-red-500 @enderror">
                                    <option value="">Select Semester</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            data-academic-year="{{ $semester->academic_year }}"
                                            {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->semester_name }} - {{ $semester->academic_year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('semester_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Details -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <svg class="w-5 h-5 inline-block mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                Activity Details
                            </h3>
                            <button type="button" @click="addActivity"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Activity
                            </button>
                        </div>

                        <div class="space-y-6">
                            <template x-for="(activity, index) in activities" :key="activity.id">
                                <div class="border-2 border-blue-300 rounded-lg bg-gradient-to-br from-blue-50 to-white shadow-md">
                                    <!-- Activity Header -->
                                    <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center justify-center w-8 h-8 bg-white text-blue-600 rounded-full text-sm font-bold" x-text="index + 1"></span>
                                            <h4 class="text-base font-semibold">MAIN ACTIVITY</h4>
                                        </div>
                                        <button type="button" @click="removeActivity(index)" x-show="activities.length > 1"
                                            class="text-white hover:bg-blue-700 p-2 rounded-md transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-6 space-y-4">
                                        <!-- Activity Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Activity Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                :name="'activities[' + index + '][activity_name]'"
                                                x-model="activity.activity_name"
                                                required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                placeholder="Enter main activity name">
                                        </div>

                                        <!-- Unit Selection -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Unit <span class="text-red-500">*</span>
                                                </label>
                                                <select :name="'activities[' + index + '][unit_id]'"
                                                    x-model="activity.unit_id"
                                                    @change="onUnitChange(index)"
                                                    required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                    <option value="">Select Unit</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            data-fields='@json($unit->suggested_fields ?? [])'>
                                                            {{ $unit->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Volume <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number"
                                                    :name="'activities[' + index + '][volume]'"
                                                    x-model.number="activity.volume"
                                                    @input="calculateActivityTotal(index)"
                                                    step="0.01"
                                                    min="0"
                                                    required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    placeholder="0.00">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Harga Satuan (Rp) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number"
                                                    :name="'activities[' + index + '][unit_price]'"
                                                    x-model.number="activity.unit_price"
                                                    @input="calculateActivityTotal(index)"
                                                    step="1"
                                                    min="0"
                                                    required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Total (Rp)</label>
                                                <input type="text"
                                                    :value="formatCurrency(activity.total)"
                                                    readonly
                                                    class="block w-full rounded-md border-gray-300 bg-blue-50 shadow-sm sm:text-sm font-bold text-blue-700">
                                                <input type="hidden"
                                                    :name="'activities[' + index + '][total]'"
                                                    :value="activity.total">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Beban</label>
                                                <input type="number"
                                                    :name="'activities[' + index + '][allocation]'"
                                                    x-model.number="activity.allocation"
                                                    @input="calculateActivityUnitCost(index)"
                                                    step="1"
                                                    min="0"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    placeholder="0">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Rp)</label>
                                                <input type="text"
                                                    :value="formatCurrency(activity.unit_cost)"
                                                    readonly
                                                    class="block w-full rounded-md border-gray-300 bg-green-50 shadow-sm sm:text-sm font-bold text-green-700">
                                                <input type="hidden"
                                                    :name="'activities[' + index + '][unit_cost]'"
                                                    :value="activity.unit_cost">
                                            </div>
                                        </div>

                                        <!-- Hidden fields -->
                                        <input type="hidden" :name="'activities[' + index + '][calculation_type]'" :value="activity.calculation_type">

                                        <!-- Dynamic Component Fields -->
                                        <template x-if="activity.components.length > 0">
                                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                                <div class="flex items-center justify-between mb-3">
                                                    <label class="block text-sm font-medium text-gray-700">Component Values</label>
                                                    <button type="button" @click="addComponent(index)"
                                                        class="inline-flex items-center px-2 py-1 bg-yellow-600 border border-transparent rounded-md text-xs text-white hover:bg-yellow-700 transition">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        Add
                                                    </button>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <template x-for="(component, compIndex) in activity.components" :key="compIndex">
                                                        <div class="bg-white p-3 rounded-md border border-yellow-300 relative">
                                                            <button type="button"
                                                                @click="removeComponent(index, compIndex)"
                                                                x-show="activity.components.length > 1"
                                                                class="absolute top-1 right-1 text-red-600 hover:text-red-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>

                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Name</label>
                                                            <input type="text"
                                                                :name="'activities[' + index + '][components][' + compIndex + '][name]'"
                                                                x-model="component.name"
                                                                required
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-xs mb-2"
                                                                placeholder="e.g., SKS">

                                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Value</label>
                                                            <input type="number"
                                                                :name="'activities[' + index + '][components][' + compIndex + '][value]'"
                                                                x-model.number="component.value"
                                                                @input="calculateVolumeFromComponents(index)"
                                                                step="0.01"
                                                                min="0"
                                                                required
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 text-sm font-semibold"
                                                                placeholder="0.00">
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Add Component Button (if no components yet) -->
                                        <div x-show="activity.components.length === 0 && activity.unit_id" class="text-center py-4 border-2 border-dashed border-gray-300 rounded-lg">
                                            <button type="button" @click="addComponent(index)"
                                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Add Component
                                            </button>
                                        </div>

                                        <!-- SUB ACTIVITIES Section -->
                                        <div class="mt-6 border-t-2 border-gray-300 pt-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 uppercase">
                                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    Sub Activities
                                                </h5>
                                                <button type="button" @click="addSubActivity(index)"
                                                    class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Add Sub Activity
                                                </button>
                                            </div>

                                            <!-- Sub Activities List -->
                                            <div class="space-y-3">
                                                <template x-for="(subActivity, subIndex) in activity.sub_activities" :key="subActivity.id">
                                                    <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                                                        <div class="flex items-center justify-between mb-3">
                                                            <span class="text-xs font-semibold text-green-700 uppercase">Sub Activity #<span x-text="subIndex + 1"></span></span>
                                                            <button type="button" @click="removeSubActivity(index, subIndex)"
                                                                class="text-red-600 hover:text-red-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                                            <!-- Sub Activity Name -->
                                                            <div class="lg:col-span-2">
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                                                                <input type="text"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][sub_activity_name]'"
                                                                    x-model="subActivity.sub_activity_name"
                                                                    required
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                                                    placeholder="Sub activity name">
                                                            </div>

                                                            <!-- Volume -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Volume <span class="text-red-500">*</span></label>
                                                                <input type="number"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][volume]'"
                                                                    x-model.number="subActivity.volume"
                                                                    @input="calculateSubActivityTotal(index, subIndex)"
                                                                    step="0.01"
                                                                    min="0"
                                                                    required
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                                                    placeholder="0.00">
                                                            </div>

                                                            <!-- Unit Satuan -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                                                <input type="text"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][unit_satuan]'"
                                                                    x-model="subActivity.unit_satuan"
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                                                    placeholder="e.g., OJ">
                                                            </div>

                                                            <!-- Unit Price -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Harga Satuan <span class="text-red-500">*</span></label>
                                                                <input type="number"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][unit_price]'"
                                                                    x-model.number="subActivity.unit_price"
                                                                    @input="calculateSubActivityTotal(index, subIndex)"
                                                                    step="1"
                                                                    min="0"
                                                                    required
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                                                    placeholder="0">
                                                            </div>

                                                            <!-- Total -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Total</label>
                                                                <input type="text"
                                                                    :value="formatCurrency(subActivity.total)"
                                                                    readonly
                                                                    class="block w-full rounded-md border-gray-300 bg-green-100 shadow-sm text-sm font-bold text-green-700">
                                                                <input type="hidden"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][total]'"
                                                                    :value="subActivity.total">
                                                            </div>

                                                            <!-- Allocation -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Beban</label>
                                                                <input type="number"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][allocation]'"
                                                                    x-model.number="subActivity.allocation"
                                                                    @input="calculateSubActivityUnitCost(index, subIndex)"
                                                                    step="1"
                                                                    min="0"
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                                                    placeholder="0">
                                                            </div>

                                                            <!-- Unit Cost -->
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-700 mb-1">Unit Cost</label>
                                                                <input type="text"
                                                                    :value="formatCurrency(subActivity.unit_cost)"
                                                                    readonly
                                                                    class="block w-full rounded-md border-gray-300 bg-green-100 shadow-sm text-sm font-bold text-green-700">
                                                                <input type="hidden"
                                                                    :name="'activities[' + index + '][sub_activities][' + subIndex + '][unit_cost]'"
                                                                    :value="subActivity.unit_cost">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Empty State -->
                                                <div x-show="activity.sub_activities.length === 0" class="text-center py-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                                    <p class="text-sm text-gray-500">No sub activities yet. Click "Add Sub Activity" to create one.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <div x-show="activities.length === 0" class="text-center py-16 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">No activities added yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Click "Add Activity" button above to get started.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 overflow-hidden shadow-lg rounded-lg border-2 border-blue-800 mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Grand Total</h3>
                                    <p class="text-xs text-blue-200">Total keseluruhan dari semua aktivitas</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-bold text-white" x-text="formatCurrency(grandTotal)">Rp 0</span>
                                <p class="text-xs text-blue-200 mt-1"><span x-text="activities.length"></span> aktivitas</p>
                            </div>
                        </div>
                        <input type="hidden" name="grand_total" :value="grandTotal">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 bg-white p-6 rounded-lg border border-gray-200">
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function reportForm() {
            return {
                activities: [],
                grandTotal: 0,
                selectedSemester: '',
                academicYear: '',
                units: @json($units),

                init() {
                    this.addActivity();
                    this.updateAcademicYear();
                },

                updateAcademicYear() {
                    const semesterSelect = document.getElementById('semester_id');
                    if (semesterSelect) {
                        const selectedOption = semesterSelect.options[semesterSelect.selectedIndex];
                        this.academicYear = selectedOption.getAttribute('data-academic-year') || '';
                    }
                },

                addActivity() {
                    this.activities.push({
                        id: Date.now(),
                        activity_name: '',
                        unit_id: '',
                        calculation_type: 'manual',
                        components: [],
                        volume: 0,
                        unit_price: 0,
                        total: 0,
                        allocation: 0,
                        unit_cost: 0,
                        notes: '',
                        sub_activities: []
                    });
                },

                removeActivity(index) {
                    if (confirm('Remove this activity and all its sub-activities?')) {
                        this.activities.splice(index, 1);
                        this.calculateGrandTotal();
                    }
                },

                addSubActivity(activityIndex) {
                    this.activities[activityIndex].sub_activities.push({
                        id: Date.now(),
                        sub_activity_name: '',
                        volume: 0,
                        unit_satuan: '',
                        unit_price: 0,
                        total: 0,
                        allocation: 0,
                        unit_cost: 0
                    });
                },

                removeSubActivity(activityIndex, subIndex) {
                    this.activities[activityIndex].sub_activities.splice(subIndex, 1);
                    this.calculateGrandTotal();
                },

                onUnitChange(index) {
                    const activity = this.activities[index];
                    const unit = this.units.find(u => u.id == activity.unit_id);

                    if (unit && unit.suggested_fields && unit.suggested_fields.length > 0) {
                        activity.calculation_type = 'multiply';
                        activity.components = unit.suggested_fields.map(field => ({
                            name: field,
                            value: 0
                        }));
                    } else {
                        activity.calculation_type = 'manual';
                        activity.components = [];
                    }
                },

                addComponent(index) {
                    this.activities[index].components.push({
                        name: '',
                        value: 0
                    });
                    this.activities[index].calculation_type = 'multiply';
                },

                removeComponent(activityIndex, componentIndex) {
                    this.activities[activityIndex].components.splice(componentIndex, 1);
                    if (this.activities[activityIndex].components.length === 0) {
                        this.activities[activityIndex].calculation_type = 'manual';
                    }
                    this.calculateVolumeFromComponents(activityIndex);
                },

                calculateVolumeFromComponents(index) {
                    const activity = this.activities[index];
                    if (activity.components.length > 0) {
                        activity.volume = activity.components.reduce((product, component) => {
                            return product * (component.value || 0);
                        }, 1);
                    }
                    this.calculateActivityTotal(index);
                },

                calculateActivityTotal(index) {
                    const activity = this.activities[index];
                    activity.total = activity.volume * activity.unit_price;
                    this.calculateActivityUnitCost(index);
                },

                calculateActivityUnitCost(index) {
                    const activity = this.activities[index];
                    if (activity.allocation > 0) {
                        activity.unit_cost = activity.total / activity.allocation;
                    } else {
                        activity.unit_cost = 0;
                    }
                    this.calculateGrandTotal();
                },

                calculateSubActivityTotal(activityIndex, subIndex) {
                    const subActivity = this.activities[activityIndex].sub_activities[subIndex];
                    subActivity.total = subActivity.volume * subActivity.unit_price;
                    this.calculateSubActivityUnitCost(activityIndex, subIndex);
                },

                calculateSubActivityUnitCost(activityIndex, subIndex) {
                    const subActivity = this.activities[activityIndex].sub_activities[subIndex];
                    if (subActivity.allocation > 0) {
                        subActivity.unit_cost = subActivity.total / subActivity.allocation;
                    } else {
                        subActivity.unit_cost = 0;
                    }
                    this.calculateGrandTotal();
                },

                calculateGrandTotal() {
                    this.grandTotal = this.activities.reduce((sum, activity) => {
                        const activityTotal = activity.total || 0;
                        const subActivitiesTotal = activity.sub_activities.reduce((subSum, subActivity) => {
                            return subSum + (subActivity.total || 0);
                        }, 0);
                        return sum + activityTotal + subActivitiesTotal;
                    }, 0);
                },

                calculateTotals(e) {
                    this.calculateGrandTotal();
                },

                formatCurrency(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value || 0);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
