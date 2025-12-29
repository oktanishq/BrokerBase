<!-- Edit Property Modal -->
<div x-data="{
    open: false,
    property: null,
    selectedStatus: 'available',
    isFeatured: false,
    selectedLabel: 'none',
    customLabelColor: '#3B82F6',
    
    init() {
        // Listen for custom events to open modal
        this.$root.addEventListener('open-edit-modal', (event) => {
            this.openModal(event.detail);
        });
    },
    
    availableLabels: [
        { value: 'none', label: 'No Label', color: 'transparent' },
        { value: 'new', label: 'New Arrival', color: '#3B82F6' },
        { value: 'popular', label: 'Popular', color: '#F59E0B' },
        { value: 'verified', label: 'Verified', color: '#10B981' },
        { value: 'custom', label: 'Custom', color: '#custom' }
    ],
    
    openModal(propertyData) {
        this.property = propertyData;
        this.selectedStatus = propertyData.status || 'available';
        this.isFeatured = propertyData.isFeatured || false;
        this.selectedLabel = propertyData.label || 'none';
        this.customLabelColor = propertyData.customLabelColor || '#3B82F6';
        this.open = true;
    },
    
    saveChanges() {
        // For showcase purposes, just show a success message
        // In real implementation, this would make an API call
        alert('Property changes saved successfully!');
        this.open = false;
    },
    
    editDraft() {
        // Navigate to property creation page with draft data
        window.location.href = '{{ route('properties.create') }}';
        this.open = false;
    }
}" 
@keydown.escape.window="open = false">

    <!-- Modal Overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
         
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>
        
        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg bg-white rounded-xl shadow-xl transform transition-all"
                 style="display: none;">
                 
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Edit Property</h3>
                    <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <span class="material-symbols-outlined text-gray-400">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6" x-show="property">
                    <!-- Property Title (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Title</label>
                        <div class="p-3 bg-gray-50 rounded-lg border text-sm text-gray-600" x-text="property?.title || 'Loading...'"></div>
                    </div>

                    <!-- Status Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Property Status</label>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="status in [
                                { value: 'draft', label: 'Draft', color: 'bg-gray-100 text-gray-700 border-gray-300' },
                                { value: 'available', label: 'Available', color: 'bg-green-100 text-green-700 border-green-300' },
                                { value: 'booked', label: 'Booked', color: 'bg-yellow-100 text-yellow-700 border-yellow-300' },
                                { value: 'sold', label: 'Sold', color: 'bg-red-100 text-red-700 border-red-300' }
                            ]" :key="status.value">
                                <label class="cursor-pointer">
                                    <input x-model="selectedStatus" 
                                           :value="status.value" 
                                           type="radio" 
                                           class="peer sr-only">
                                    <div :class="[
                                        'border-2 rounded-lg p-3 text-center font-medium transition-all peer-checked:border-royal-blue peer-checked:bg-blue-50',
                                        status.color
                                    ]" x-text="status.label"></div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Featured Property Checkbox -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Featured Property</span>
                            <p class="text-xs text-gray-500 mt-1">Show this property in featured listings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input x-model="isFeatured" 
                                   type="checkbox" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-blue"></div>
                        </label>
                    </div>

                    <!-- Label Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Property Label</label>
                        <div class="space-y-3">
                            <template x-for="label in availableLabels" :key="label.value">
                                <label class="cursor-pointer flex items-center space-x-3">
                                    <input x-model="selectedLabel" 
                                           :value="label.value" 
                                           type="radio" 
                                           class="peer sr-only">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <div :class="[
                                            'w-4 h-4 rounded-full border-2 peer-checked:border-royal-blue',
                                            label.value === 'custom' ? 'border-gray-300' : 'border-transparent'
                                        ]" :style="label.value === 'custom' ? `background-color: ${customLabelColor}` : `background-color: ${label.color}`"></div>
                                        <span class="text-sm font-medium text-gray-700" x-text="label.label"></span>
                                        <span x-show="label.value === 'custom'" class="text-xs text-gray-500">(Custom Color)</span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Custom Color Picker (shown when custom label is selected) -->
                    <div x-show="selectedLabel === 'custom'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Label Color</label>
                        <div class="flex items-center space-x-3">
                            <input x-model="customLabelColor" 
                                   type="color" 
                                   class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input x-model="customLabelColor" 
                                   type="text" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   placeholder="#3B82F6">
                        </div>
                    </div>

                    <!-- Label Preview -->
                    <div x-show="selectedLabel !== 'none'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Preview</label>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border"
                                  :style="selectedLabel === 'custom' ? `background-color: ${customLabelColor}20; color: ${customLabelColor}; border-color: ${customLabelColor}40` : `background-color: ${availableLabels.find(l => l.value === selectedLabel)?.color}20; color: ${availableLabels.find(l => l.value === selectedLabel)?.color}; border-color: ${availableLabels.find(l => l.value === selectedLabel)?.color}40`"
                                  x-text="availableLabels.find(l => l.value === selectedLabel)?.label || 'Custom Label'">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <button @click="editDraft()" 
                            type="button" 
                            class="px-4 py-2 text-sm font-medium text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
                        Edit Draft
                    </button>
                    <div class="flex items-center space-x-3">
                        <button @click="open = false" 
                                type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="saveChanges()" 
                                type="button" 
                                class="px-4 py-2 text-sm font-bold text-white bg-royal-blue hover:bg-blue-800 rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>