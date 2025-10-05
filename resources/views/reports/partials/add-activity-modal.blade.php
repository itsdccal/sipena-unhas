<x-modal name="add-activity-modal" :show="false">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Activity</h2>

        <form id="addActivityForm" @submit.prevent="submitActivity()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label for="activity_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Activity <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="activity_name" name="activity_name" x-model="activity.activity_name"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select id="unit_id" name="unit_id" x-model="activity.unit_id" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="volume" class="block text-sm font-medium text-gray-700 mb-2">
                        Volume <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="volume" name="volume" x-model.number="activity.volume"
                        @input="calculateActivity()" step="0.01" min="0" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Satuan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="unit_price" name="unit_price" x-model.number="activity.unit_price"
                        @input="calculateActivity()" step="1" min="0" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="allocation" class="block text-sm font-medium text-gray-700 mb-2">
                        Beban
                    </label>
                    <input type="number" id="allocation" name="allocation" x-model.number="activity.allocation"
                        @input="calculateActivity()" step="1" min="0"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <input type="text" :value="formatCurrency(activity.total)" readonly
                        class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost</label>
                    <input type="text" :value="formatCurrency(activity.unit_cost)" readonly
                        class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea id="notes" name="notes" rows="3" x-model="activity.notes"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'add-activity-modal')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" :disabled="loading"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span x-show="!loading">Simpan</span>
                    <span x-show="loading">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>
