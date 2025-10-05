<x-modal name="add-sub-activity-modal" maxWidth="2xl">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Add Sub Activity</h3>

        <form method="POST" :action="`/reports/activities/${currentActivityId}/sub-activities`">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Sub Activity Name <span class="text-red-500">*</span></label>
                    <input type="text" name="sub_activity_name" x-model="sub.name" required
                        class="w-full border-gray-300 rounded text-sm">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Volume <span class="text-red-500">*</span></label>
                        <input type="number" name="volume" x-model="sub.volume"
                            @input="calculateSub" step="0.01" required
                            class="w-full border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Unit</label>
                        <input type="text" name="unit_satuan" x-model="sub.unit"
                            class="w-full border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Unit Price <span class="text-red-500">*</span></label>
                        <input type="number" name="unit_price" x-model="sub.price"
                            @input="calculateSub" required
                            class="w-full border-gray-300 rounded text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Total</label>
                        <input type="text" :value="formatCurrency(sub.total)" readonly
                            class="w-full border-gray-300 bg-green-50 rounded text-sm font-semibold">
                        <input type="hidden" name="total" :value="sub.total">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Beban</label>
                        <input type="number" name="allocation" x-model="sub.allocation"
                            @input="calculateSub" class="w-full border-gray-300 rounded text-sm">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'add-sub-activity-modal')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    Add Sub Activity
                </button>
            </div>
        </form>
    </div>
</x-modal>
