@props(['title' => 'Featured Properties'])

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1" x-data="propertyListing()" x-init="loadProperties()">
    <div class="max-w-[1280px] mx-auto">
        <h3 class="text-lg sm:text-xl font-bold text-[#121317] mb-4 sm:mb-6">{{ $title }}</h3>
        
        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            <span class="ml-2 text-gray-600">Loading properties...</span>
        </div>

        <!-- Error State -->
        <div x-show="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <span class="material-symbols-outlined text-red-400 text-xl mr-2">error</span>
                <div>
                    <h4 class="text-red-800 font-medium">Error Loading Properties</h4>
                    <p class="text-red-600 text-sm" x-text="error"></p>
                </div>
            </div>
        </div>

        <!-- Properties Grid -->
        <div x-show="!loading && !error" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <template x-for="property in properties" :key="property.id">
                <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <!-- Property Badge -->
                        <div x-show="property.label_badge" class="absolute top-3 left-3 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"
                             :class="{
                                 'bg-blue-500': property.label === 'new',
                                 'bg-orange-500': property.label === 'popular', 
                                 'bg-teal-600': property.label === 'verified',
                                 'bg-gray-500': property.label === 'custom'
                             }"
                             :style="property.label === 'custom' ? `background-color: ${property.custom_label_color}` : ''">
                            <span x-text="property.label_badge ? property.label_badge.label : ''"></span>
                        </div>
                        
                        <!-- Favorite Button -->
                        <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[20px] block">favorite</span>
                            </button>
                        </div>
                        
                        <!-- Property Image -->
                        <img alt="Property image" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             :src="property.image || 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6JGLNEeRDULjyz7WFJ4ObKCZsQi74fcb4HMbuJJ5VwEvic_qApCHMX8vJ3Knawdusc02xCJkbMMw3C-ejtmLRxazLDraIoearbjj25VNyASPqtCfC0knpA8JNNVSKy8N5tTAcfsmro8U7Rw5LHMHKXZYoI93JICpm3AQNoW2T-CsfJTCVWbaOHWDSd2KDyz10TInVv0nJFeTbQCWYKnXKBCd7GVHrXGiZeWcgHrUY88JJDScTJ-mIIz7znDdPuVAbtllQISMOXo9j'">
                        <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
                    </div>
                    
                    <div class="p-5 flex flex-col gap-3 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight" x-text="property.price || 'Price TBD'"></h3>
                                <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors" x-text="property.title"></h4>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-1 text-[#666e85] text-sm">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                            <p class="truncate" x-text="property.location || 'Location TBD'"></p>
                        </div>
                        
                        <!-- Property Specs -->
                        <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
                            <template x-if="property.bedrooms && property.bedrooms > 0">
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
                                    <span x-text="`${property.bedrooms} Beds`"></span>
                                </div>
                            </template>
                            <template x-if="property.bathrooms && property.bathrooms > 0">
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
                                    <span x-text="`${property.bathrooms} Baths`"></span>
                                </div>
                            </template>
                            <template x-if="property.sqft && property.sqft !== 'N/A'">
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                                    <span x-text="property.sqft"></span>
                                </div>
                            </template>
                            <template x-if="!property.bedrooms || property.bedrooms === 0">
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">domain</span>
                                    <span x-text="property.type || 'Commercial'"></span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-1">
                            <a :href="`/property/${property.id}`" class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                                View Details
                            </a>
                            <button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                                <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                WhatsApp
                            </button>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <!-- No Properties State -->
        <div x-show="!loading && !error && properties.length === 0" class="text-center py-12">
            <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
            <h4 class="text-gray-600 font-medium text-lg">No Properties Available</h4>
            <p class="text-gray-500 text-sm mt-1">Check back later for new listings.</p>
        </div>
    </div>
</section>

<script>
function propertyListing() {
    return {
        properties: [],
        loading: true,
        error: null,
        
        async loadProperties() {
            try {
                this.loading = true;
                this.error = null;
                
                const response = await fetch('/api/properties');
                const data = await response.json();
                
                if (data.success) {
                    this.properties = data.data;
                } else {
                    throw new Error(data.message || 'Failed to fetch properties');
                }
            } catch (error) {
                console.error('Error loading properties:', error);
                this.error = error.message || 'Failed to load properties. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>