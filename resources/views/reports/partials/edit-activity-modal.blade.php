<x-modal name="edit-activity-modal" maxWidth="2xl">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Edit Activity</h3>

        <form method="POST" :action="`/reports/activities/${currentActivityId}`" x-show="currentActivityId">
            @csrf
            @method('PUT')

            <!-- Activity Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Activity Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="activity_name" x-model="editActivity.activity_name" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>

            <!-- Unit, Volume, Price -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                    <select name="unit_id" x-model="editActivity.unit_id" required class="w-full border-gray-300 rounded text-sm">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volume <span class="text-red-500">*</span></label>
                    <input type="number" name="volume" x-model="editActivity.volume"
                        @input="calculateEditActivity" step="0.01" required
                        class="w-full border-gray-300 rounded text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan <span class="text-red-500">*</span></label>
                    <input type="number" name="unit_price" x-model="editActivity.unit_price"
                        @input="calculateEditActivity" required
                        class="w-full border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Total, Beban, Unit Cost -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total (Rp)</label>
                    <input type="text" :value="formatCurrency(editActivity.total)" readonly
                        class="w-full border-gray-300 bg-blue-50 rounded text-sm font-semibold">
                    <input type="hidden" name="total" :value="editActivity.total">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Beban</label>
                    <input type="number" name="allocation" x-model="editActivity.allocation"
                        @input="calculateEditActivity" class="w-full border-gray-300 rounded text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Rp)</label>
                    <input type="text" :value="formatCurrency(editActivity.unit_cost)" readonly
                        class="w-full border-gray-300 bg-green-50 rounded text-sm font-semibold">
                    <input type="hidden" name="unit_cost" :value="editActivity.unit_cost">
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" x-model="editActivity.notes" rows="2"
                    class="w-full border-gray-300 rounded text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'edit-activity-modal')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Update Activity
                </button>
            </div>
        </form>
    </div>
</x-modal>
