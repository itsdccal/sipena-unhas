<x-modal name="add-semester-modal" maxWidth="md">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Add New Semester</h3>
        <form method="POST" action="{{ route('reports.store') }}">
            @csrf

            @if($reports->isEmpty())
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Study Program *</label>
                <select name="study_program_id" required class="w-full border-gray-300 rounded">
                    <option value="">Select</option>
                    @foreach($studyPrograms as $program)
                        <option value="{{ $program->id }}"
                            {{ auth()->user()->study_program_id == $program->id ? 'selected' : '' }}>
                            {{ $program->sp_code }} - {{ $program->sp_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
                <input type="hidden" name="study_program_id" value="{{ $reports->first()->study_program_id }}">
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Semester *</label>
                <select name="semester_id" required class="w-full border-gray-300 rounded">
                    <option value="">Select</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->semester_name }} - {{ $semester->academic_year }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="grand_total" value="0">

            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'add-semester-modal')" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Add Semester
                </button>
            </div>
        </form>
    </div>
</x-modal>
