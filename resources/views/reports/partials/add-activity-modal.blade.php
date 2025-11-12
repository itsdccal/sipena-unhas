<x-modal name="add-activity-modal" :show="false">
    <div class="p-6" x-data="{
        ...activityAutocomplete(),
        init() {
            this.activityTemplates = @json($activityTemplates ?? []);
        }
    }">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Activity</h2>

        <form id="addActivityForm" @submit.prevent="submitActivity()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label for="activity_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Activity <span class="text-red-500">*</span>
                    </label>
                    <div class="relative" @click.away="showSuggestions = false">
                        <input type="text" id="activity_name" name="activity_name"
                            x-model="activity.activity_name"
                            @input="filterSuggestions()"
                            @focus="showSuggestions = true"
                            @blur="setTimeout(() => showSuggestions = false, 250)"
                            @keydown.escape="showSuggestions = false"
                            @keydown.arrow-down.prevent="focusNextSuggestion()"
                            @keydown.arrow-up.prevent="focusPrevSuggestion()"
                            @keydown.enter.prevent="if(filteredSuggestions.length > 0 && selectedIndex >= 0) selectSuggestion(filteredSuggestions[selectedIndex])"
                            required
                            autocomplete="off"
                            placeholder="Ketik untuk mencari aktivitas..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        <!-- Clear Button -->
                        <button type="button" x-show="activity.activity_name && activity.activity_name.length > 0"
                            @click="activity.activity_name = ''; filteredSuggestions = []"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Suggestions Dropdown -->
                        <div x-show="showSuggestions && filteredSuggestions.length > 0"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden z-50">

                            <!-- Suggestion Count Header -->
                            <div class="sticky top-0 px-3 py-2 bg-gray-50 border-b border-gray-200 text-xs text-gray-600 font-medium">
                                <span x-text="filteredSuggestions.length"></span> aktivitas ditemukan
                            </div>

                            <!-- Scrollable Content -->
                            <div class="max-h-60 overflow-y-auto">
                                <template x-for="(suggestion, index) in filteredSuggestions" :key="suggestion.id || index">
                                    <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition"
                                        :class="{ 'bg-blue-50': index === selectedIndex }"
                                        @click.stop="selectSuggestion(suggestion)"
                                        @mouseenter="selectedIndex = index">
                                        <div class="font-medium text-sm text-gray-900" x-text="suggestion.activity_name"></div>
                                        <div class="text-xs text-gray-500 mt-0.5" x-show="suggestion.description" x-text="suggestion.description"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- No Results -->
                        <div x-show="showSuggestions && activity.activity_name && activity.activity_name.length >= 2 && filteredSuggestions.length === 0"
                            x-transition
                            class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-xl p-3 z-50">
                            <div class="text-center text-gray-500">
                                <p class="text-sm">Tidak ada aktivitas ditemukan</p>
                                <p class="text-xs mt-1">Anda bisa mengetik manual atau hubungi admin untuk menambahkan</p>
                            </div>
                        </div>
                    </div>
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
                        Keterangan
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

@push('scripts')
<script>
function activityAutocomplete() {
    return {
        activityTemplates: [],
        showSuggestions: false,
        filteredSuggestions: [],
        selectedIndex: -1,

        filterSuggestions() {
            const query = (this.activity.activity_name || '').toLowerCase();

            if (query.length < 2) {
                this.filteredSuggestions = [];
                this.showSuggestions = false;
                return;
            }

            this.filteredSuggestions = this.activityTemplates.filter(template => {
                if (!template || !template.activity_name) return false;
                const activityName = template.activity_name.toLowerCase();
                const description = (template.description || '').toLowerCase();
                return activityName.includes(query) || description.includes(query);
            }).slice(0, 10);

            this.selectedIndex = -1;
            this.showSuggestions = this.filteredSuggestions.length > 0 || query.length >= 2;
        },

        selectSuggestion(suggestion) {
            if (!suggestion || !suggestion.activity_name) return;
            this.activity.activity_name = suggestion.activity_name;
            this.showSuggestions = false;
            this.filteredSuggestions = [];
            this.selectedIndex = -1;
        },

        focusNextSuggestion() {
            if (this.filteredSuggestions.length > 0) {
                this.selectedIndex = (this.selectedIndex + 1) % this.filteredSuggestions.length;
            }
        },

        focusPrevSuggestion() {
            if (this.filteredSuggestions.length > 0) {
                this.selectedIndex = this.selectedIndex <= 0 ?
                    this.filteredSuggestions.length - 1 :
                    this.selectedIndex - 1;
            }
        }
    }
}
</script>
@endpush
