<x-modal name="add-activity-modal" maxWidth="2xl">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Add New Activity</h3>

        <form method="POST" :action="`/reports/${currentReportId}/activities`">
            @csrf

            <!-- Activity Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Activity Namedsdsdsd <span class="text-red-500">*</span>
                </label>
                <input type="text" name="activity_name" x-model="activity.activity_name" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <!-- Unit, Volume, Price -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                    <select name="unit_id" x-model="activity.unit_id" required class="w-full border-gray-300 rounded text-sm">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volume <span class="text-red-500">*</span></label>
                    <input type="number" name="volume" x-model="activity.volume"
                        @input="calculateActivity" step="0.01" required
                        class="w-full border-gray-300 rounded text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan <span class="text-red-500">*</span></label>
                    <input type="number" name="unit_price" x-model="activity.unit_price"
                        @input="calculateActivity" required
                        class="w-full border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Total, Beban, Unit Cost -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total (Rp)</label>
                    <input type="text" :value="formatCurrency(activity.total)" readonly
                        class="w-full border-gray-300 bg-blue-50 rounded text-sm font-semibold">
                    <input type="hidden" name="total" :value="activity.total">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beban</label>
                    <input type="number" name="allocation" x-model="activity.allocation"
                        @input="calculateActivity" class="w-full border-gray-300 rounded text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Rp)</label>
                    <input type="text" :value="formatCurrency(activity.unit_cost)" readonly
                        class="w-full border-gray-300 bg-green-50 rounded text-sm font-semibold">
                    <input type="hidden" name="unit_cost" :value="activity.unit_cost">
                </div>
            </div>

            <input type="hidden" name="calculation_type" value="manual">

            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'add-activity-modal')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Add Activity
                </button>
            </div>
        </form>
    </div>
</x-modal>
