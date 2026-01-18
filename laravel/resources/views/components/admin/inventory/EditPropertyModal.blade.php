<!-- Edit Property Modal -->
<div x-data="editPropertyModal()" 
     x-init="init()" 
     @keydown.escape.window="closeModal()"
     @open-edit-modal.window="openModal($event.detail)"
     @close-edit-modal.window="closeModal()"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
     style="display: none;"
     x-show="isOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
         @click="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-slate-900">Edit Listing</h2>
            <button @click="closeModal()" 
                    class="text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto p-6 space-y-6 flex-1">
            
            <!-- Property Preview Section -->
            <template x-if="property">
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="w-16 h-12 bg-cover bg-center rounded-md shrink-0" 
                         :style="`background-image: url('${property.image || '/images/placeholder-property.jpg'}')`"></div>
                    <div>
                        <h3 class="font-bold text-slate-800" x-text="property.title || 'Loading...'"></h3>
                        <p class="text-xs text-gray-500" x-text="`ID: #${property.id || 'N/A'} • Added ${formatDate(property.created_at)}`"></p>
                    </div>
                </div>
            </template>

            <!-- Status and Labels Section -->
            <div class="space-y-4">
                <!-- Listing Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Listing Status</label>
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <template x-for="status in statusOptions" :key="status.value">
                            <button @click="selectedStatus = status.value" 
                                    :class="selectedStatus === status.value ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all"
                                    x-text="status.label">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Public Badge and Featured Toggle -->
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Public Badge</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="label in labelOptions" :key="label.value">
                                <button @click="selectedLabel = label.value" 
                                        :class="selectedLabel === label.value ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                        class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors"
                                        x-text="label.label">
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="inline-flex items-center cursor-pointer gap-3">
                            <span class="text-sm font-medium text-slate-700">Featured Property</span>
                            <div class="relative">
                                <input x-model="isFeatured" 
                                       type="checkbox" 
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-blue"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Modify All Details Button -->
            <div class="pt-2">
                <button @click="editAllDetails()" 
                        class="w-full py-2.5 px-4 rounded-lg border border-royal-blue/30 text-royal-blue hover:bg-blue-50 font-medium text-sm flex items-center justify-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Modify All Details & Media
                </button>
            </div>

            <!-- Owner & Private Information Section -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="bg-amber-50/50 px-4 py-3 border-b border-amber-100/50 flex items-center justify-between cursor-pointer" 
                     @click="toggleOwnerSection()">
                    <div class="flex items-center gap-2 text-amber-900/80">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                        <h4 class="font-semibold text-sm">Owner & Private Information</h4>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 text-[20px] transition-transform" 
                          :class="ownerSectionExpanded ? 'rotate-180' : ''">expand_less</span>
                </div>
                
                <div x-show="ownerSectionExpanded" 
                     x-transition
                     class="p-4 bg-white grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500">Owner Name</label>
                        <input x-model="formData.owner_name" 
                               type="text" 
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                               placeholder="Enter owner name">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500">Owner Phone</label>
                        <input x-model="formData.owner_phone" 
                               type="text" 
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                               placeholder="Enter owner phone">
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500">Net Price (Private)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400">$</span>
                            <input x-model="formData.net_price" 
                                   type="text" 
                                   class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none font-mono"
                                   placeholder="420,000">
                        </div>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500">Private Notes</label>
                        <textarea x-model="formData.private_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none resize-none"
                                  placeholder="Add internal notes here..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 pt-2 border-t border-gray-50 flex items-center justify-end gap-3 bg-white">
            <button @click="closeModal()" 
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                Discard Changes
            </button>
            <button @click="saveChanges()" 
                    :disabled="saving"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-royal-blue hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!saving">Update Listing</span>
                <span x-show="saving" class="flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    Saving...
                </span>
            </button>
        </div>
    </div>
</div>

<script>
// Ensure function is available globally before Alpine.js uses it
window.editPropertyModal = function() {
    return {
        isOpen: false,
        property: null,
        saving: false,
        ownerSectionExpanded: false,
        
        // Form data
        formData: {
            status: 'available',
            is_featured: false,
            label_type: 'none',
            custom_label_color: '#3B82F6',
            owner_name: '',
            owner_phone: '',
            net_price: '',
            private_notes: ''
        },
        
        // Options
        statusOptions: [
            { value: 'available', label: 'Available' },
            { value: 'sold', label: 'Sold' },
            { value: 'draft', label: 'Draft' },
            { value: 'booked', label: 'Booked' }
        ],
        
        labelOptions: [
            { value: 'none', label: 'None' },
            { value: 'new', label: 'New Arrival' },
            { value: 'popular', label: 'Popular' },
            { value: 'verified', label: 'Verified' }
        ],
        
        // Computed
        get selectedStatus() {
            return this.formData.status;
        },
        set selectedStatus(value) {
            this.formData.status = value;
        },
        
        get selectedLabel() {
            return this.formData.label_type;
        },
        set selectedLabel(value) {
            this.formData.label_type = value;
        },
        
        get isFeatured() {
            return this.formData.is_featured;
        },
        set isFeatured(value) {
            this.formData.is_featured = value;
        },
        
        // Methods
        init() {
            // Listen for custom events
            this.$root.addEventListener('open-edit-modal', (event) => {
                this.openModal(event.detail);
            });
            
            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.closeModal();
                }
            });
        },
        
        openModal(propertyData) {
            this.property = propertyData;
            this.populateFormData();
            this.isOpen = true;
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        },
        
        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = ''; // Restore scrolling
            this.resetForm();
        },
        
        populateFormData() {
            if (!this.property) return;
            
            this.formData = {
                status: this.property.status || 'available',
                is_featured: this.property.is_featured || false,
                label_type: this.property.label || 'none',
                custom_label_color: this.property.custom_label_color || '#3B82F6',
                owner_name: this.property.owner_name || '',
                owner_phone: this.property.owner_phone || '',
                net_price: this.property.net_price ? parseFloat(this.property.net_price).toLocaleString() : '',
                private_notes: this.property.private_notes || ''
            };
        },
        
        resetForm() {
            this.property = null;
            this.formData = {
                status: 'available',
                is_featured: false,
                label_type: 'none',
                custom_label_color: '#3B82F6',
                owner_name: '',
                owner_phone: '',
                net_price: '',
                private_notes: ''
            };
            this.ownerSectionExpanded = false;
        },
        
        toggleOwnerSection() {
            this.ownerSectionExpanded = !this.ownerSectionExpanded;
        },
        
        formatDate(dateString) {
            if (!dateString) return 'recently';
            
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 1) return '1 day ago';
            if (diffDays < 7) return `${diffDays} days ago`;
            if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks ago`;
            return `${Math.ceil(diffDays / 30)} months ago`;
        },
        
        editAllDetails() {
            // Navigate to full property editing page
            if (this.property && this.property.id) {
                window.location.href = `/admin/properties/${this.property.id}/edit`;
            }
        },
        
        async saveChanges() {
            if (!this.property || !this.property.id) return;
            
            this.saving = true;
            
            try {
                // Prepare data for API
                const updateData = {
                    status: this.formData.status,
                    is_featured: this.formData.is_featured,
                    label_type: this.formData.label_type,
                    custom_label_color: this.formData.custom_label_color,
                    owner_name: this.formData.owner_name,
                    owner_phone: this.formData.owner_phone,
                    net_price: this.formData.net_price.replace(/,/g, ''), // Remove formatting
                    private_notes: this.formData.private_notes
                };
                
                // Make API call
                const response = await fetch(`/api/admin/properties/${this.property.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    // Show success message
                    this.showNotification('Property updated successfully!', 'success');
                    
                    // Dispatch event to refresh inventory with updated data
                    this.$root.dispatchEvent(new CustomEvent('property-updated', {
                        detail: { 
                            propertyId: this.property.id, 
                            data: result.data 
                        }
                    }));
                    
                    // Close modal
                    this.closeModal();
                } else {
                    throw new Error(result.message || 'Failed to update property');
                }
            } catch (error) {
                console.error('Error updating property:', error);
                this.showNotification('Failed to update property. Please try again.', 'error');
            } finally {
                this.saving = false;
            }
        },
        
        showNotification(message, type = 'info') {
            // Simple notification - you can replace with a more sophisticated system
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    }
};
</script>