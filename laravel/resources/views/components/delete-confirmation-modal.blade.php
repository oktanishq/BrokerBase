{{-- Delete Confirmation Modal Component --}}
<div
    data-modal="delete-property"
    x-data="deletePropertyModal()"
    x-show="showDeleteModal"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            aria-hidden="true"
            class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
            @click="closeModal()"
        ></div>
        <span aria-hidden="true" class="hidden sm:inline-block sm:align-middle sm:h-screen">​</span>
        
        {{-- Modal Container --}}
        <div
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            {{-- Modal Header & Content --}}
            <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                <div class="sm:flex sm:items-start">
                    {{-- Icon --}}
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-symbols-outlined text-red-600">delete</span>
                    </div>
                    
                    {{-- Text Content --}}
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                            Delete Property
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-2">
                                Pressing delete will remove the property permanently.
                            </p>
                            <template x-if="propertyToDelete">
                                <div class="bg-gray-50 rounded-lg p-3 border">
                                    <p class="text-sm font-semibold text-gray-900">
                                        <span x-text="`#${propertyToDelete.id}`"></span> - 
                                        <span x-text="propertyToDelete.title"></span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="propertyToDelete.price"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                {{-- Delete Button --}}
                <button
                    type="button"
                    @click="deleteProperty()"
                    :disabled="isDeleting"
                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!isDeleting">Delete Permanently</span>
                    <span x-show="isDeleting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Deleting...
                    </span>
                </button>
                
                {{-- Cancel Button --}}
                <button
                    type="button"
                    @click="closeModal()"
                    :disabled="isDeleting"
                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-blue sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Alpine.js Modal Logic --}}
<script>
function deletePropertyModal() {
    return {
        showDeleteModal: false,
        propertyToDelete: null,
        isDeleting: false,

        init() {
            // Initialize global state if not exists
            if (!window.deletePropertyModalState) {
                window.deletePropertyModalState = {
                    showDeleteModal: false,
                    propertyToDelete: null,
                    isDeleting: false
                };
            }

            // Sync with global state periodically
            setInterval(() => {
                this.showDeleteModal = window.deletePropertyModalState.showDeleteModal;
                this.propertyToDelete = window.deletePropertyModalState.propertyToDelete;
                this.isDeleting = window.deletePropertyModalState.isDeleting;
            }, 100);

            // Watch for local changes and update global state
            this.$watch('showDeleteModal', (value) => {
                window.deletePropertyModalState.showDeleteModal = value;
            });

            this.$watch('propertyToDelete', (value) => {
                window.deletePropertyModalState.propertyToDelete = value;
            });

            this.$watch('isDeleting', (value) => {
                window.deletePropertyModalState.isDeleting = value;
            });
        },

        closeModal() {
            this.showDeleteModal = false;
            this.propertyToDelete = null;
            this.isDeleting = false;
            document.body.style.overflow = 'auto';
        },

        async deleteProperty() {
            if (!this.propertyToDelete || this.isDeleting) return;
            
            this.isDeleting = true;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch(`/api/admin/properties/${this.propertyToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const propertyId = this.propertyToDelete.id;
                    
                    // Close modal
                    this.closeModal();
                    
                    // Dispatch event to remove property from UI
                    this.$dispatch('property-deleted', { propertyId });
                    
                    // Show success message
                    alert('Property deleted successfully');
                } else {
                    throw new Error('Failed to delete property');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Failed to delete property. Please try again.');
                this.isDeleting = false;
            }
        }
    }
}
</script>