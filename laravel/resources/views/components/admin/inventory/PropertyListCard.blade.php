{{-- This component expects Alpine.js property data, no @props needed --}}

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col md:flex-row w-full" 
     x-data="{ property: property }"
     x-init="
         // Listen for property deleted events
         $root.addEventListener('property-deleted', (event) => {
             if (event.detail.propertyId === property.id) {
                 $el.remove();
             }
         });
     "
     :class="property.status === 'sold' ? 'opacity-90 hover:opacity-100' : ''">
     
    <!-- Property Image -->
    <div class="relative w-full md:w-64 lg:w-72 h-48 md:h-auto shrink-0 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center" 
             :class="property.status === 'sold' ? 'grayscale contrast-125' : ''" 
             x-bind:style="`background-image: url('${property.image || '/images/placeholder-property.jpg'}')`"
             class="group-hover:scale-105 transition-transform duration-500">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent md:hidden"></div>
        
        <!-- View Count -->
        <div class="absolute bottom-3 left-3 md:left-auto md:right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px]" x-text="property.views > 0 ? 'visibility' : 'visibility_off'"></span>
            <span x-text="property.views"></span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-5 flex flex-col flex-1 justify-between gap-3">
        <div class="flex flex-col gap-1">
            <!-- Status and Date -->
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"
                      :class="{
                          'bg-green-100 text-green-700 border-green-200': property.status === 'available',
                          'bg-red-100 text-red-700 border-red-200': property.status === 'sold',
                          'bg-gray-200 text-gray-600 border-gray-300': property.status === 'draft'
                      }"
                      x-text="property.status.charAt(0).toUpperCase() + property.status.slice(1)">
                </span>
                <span class="text-sm text-gray-400 hidden md:block" 
                      x-text="property.status === 'sold' ? 
                              `Sold recently` : 
                              property.status === 'draft' ? 
                              `Last edited recently` : 
                              `Added recently`">
                </span>
            </div>

            <!-- Title and Price -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-1">
                <h3 class="text-xl font-bold text-slate-900 leading-tight truncate" 
                    x-bind:title="property.title"
                    x-text="property.title">
                </h3>
                <h3 class="text-xl font-bold" 
                    :class="{
                        'text-amber-600': property.status === 'available',
                        'text-gray-400 line-through decoration-red-500 decoration-2': property.status === 'sold',
                        'text-gray-400 italic': property.status === 'draft'
                    }"
                    x-text="property.price">
                </h3>
            </div>

            <!-- Location -->
            <div class="flex items-center gap-1 text-gray-500 text-sm">
                <span class="material-symbols-outlined text-[18px] text-red-500">location_on</span>
                <span x-text="property.location || 'Location not specified'"></span>
            </div>
        </div>

        <!-- Property Specs -->
        <div class="flex items-center flex-wrap gap-4 mt-2 pt-3 border-t border-gray-50">
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]" 
                      x-text="property.type === 'Office' || property.type === 'Commercial' ? 'desk' : 'bed'">
                </span>
                <span x-text="property.type === 'Office' || property.type === 'Commercial' ? 
                               property.type : 
                               `${property.bedrooms || 3} Beds`">
                </span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">bathtub</span>
                <span x-text="`${property.bathrooms || 2} Baths`"></span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">square_foot</span>
                <span x-text="`${Number(property.sqft || 1500).toLocaleString()} sqft`"></span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="px-4 py-3 md:py-0 md:px-6 bg-gray-50 md:bg-transparent md:border-l border-t md:border-t-0 border-gray-100 flex md:flex-col items-center justify-end md:justify-center gap-2 md:w-32 shrink-0">
        <!-- Share Button -->
        <template x-if="property.status !== 'draft'">
            <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Share via WhatsApp">
                <span class="material-symbols-outlined text-[20px]">chat</span>
            </button>
        </template>
        <template x-if="property.status === 'draft'">
            <button class="flex items-center justify-center size-9 rounded-full text-gray-300 cursor-not-allowed" title="Share disabled">
                <span class="material-symbols-outlined text-[20px]">chat</span>
            </button>
        </template>

        <!-- Edit Button -->
        <button @click="$dispatch('open-edit-modal', property)" class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Property">
            <span class="material-symbols-outlined text-[20px]">edit</span>
        </button>

        <!-- Delete Button (for all properties) -->
        <button 
            @click="triggerDeleteModal()" 
            x-data="{ triggerDeleteModal() { 
                if (!window.deletePropertyModalState) {
                    window.deletePropertyModalState = {
                        showDeleteModal: false,
                        propertyToDelete: null,
                        isDeleting: false
                    };
                }
                window.deletePropertyModalState.propertyToDelete = property;
                window.deletePropertyModalState.showDeleteModal = true;
                document.body.style.overflow = 'hidden';
            }}"
            class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" 
            title="Delete Property"
        >
            <span class="material-symbols-outlined text-[20px]">delete</span>
        </button>
    </div>
</div>