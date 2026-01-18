@props([
    'currentFilters' => [],
    'onApply' => 'function(filters) { console.log("Apply filters:", filters); }',
    'onReset' => 'function() { console.log("Reset filters"); }',
    'customStyle' => ''
])

<div 
    x-data="advancedFilters({
        currentFilters: @json($currentFilters),
        onApply: {{ $onApply }},
        onReset: {{ $onReset }}
    })"
    class="{{ $customStyle }}"
    style="{{ $customStyle }}"
>
    <!-- Filter Button -->
    <button 
        @click="openFilters()"
        class="h-12 px-4 bg-[#f1f1f4] hover:bg-gray-200 text-[#121317] rounded-full flex items-center gap-2 text-sm font-medium transition-colors shrink-0"
        x-ref="filterButton"
    >
        <span class="material-symbols-outlined text-[18px]">tune</span>
        <span class="hidden sm:inline">More Filters</span>
        <span class="material-symbols-outlined text-[16px] transition-transform" :class="showFilters ? 'rotate-180' : ''">expand_more</span>
        <span x-show="activeFilterCount > 0" 
              class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"
              x-text="activeFilterCount">
        </span>
    </button>

    <!-- Filter Modal -->
    <div 
        x-show="showFilters" 
        class="fixed inset-0 z-[10]"
        x-transition
        style="display: none;"
    >
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" 
             @click="closeFilters()"></div>
        
        <!-- Modal Content -->
        <div class="absolute bottom-0 left-0 right-0 md:inset-auto md:top-1/2 md:left-1/2 md:transform md:-translate-x-1/2 md:-translate-y-1/2 w-full md:w-[90vw] md:max-w-6xl lg:max-w-7xl h-[85vh] md:h-auto md:rounded-2xl bg-white shadow-2xl overflow-hidden"
             x-transition
             x-ref="modalContent">
            
            <!-- Pull Handle for Mobile -->
            <div class="md:hidden flex justify-center pt-3 pb-2">
                <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
            </div>
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-2 sm:p-3 md:p-6 border-b border-gray-200 bg-white sticky top-0 z-[99998]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] sm:text-[18px] md:text-[20px] text-primary">tune</span>
                    <h3 class="text-sm sm:text-base md:text-xl font-bold text-[#121317]">Filter Properties</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="resetAllFilters()" 
                            class="hidden md:inline-flex px-3 py-1.5 text-xs font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                        Reset All
                    </button>
                    <button @click="closeFilters()" 
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex h-screen md:h-[70vh] overflow-hidden">
                <!-- Left Sidebar - Filter Categories -->
                <div class="w-56 sm:w-64 md:w-80 lg:w-96 bg-gray-50 border-r border-gray-200 shrink-0 overflow-y-auto">
                    <div class="p-1.5 sm:p-2 md:p-4 space-y-1 sm:space-y-2">
                        <template x-for="category in filterCategories" :key="category.key">
                            <button 
                                @click="selectCategory(category.key)"
                                class="w-full text-left p-2.5 sm:p-3 md:p-2.5 min-h-[44px] rounded-lg transition-colors flex items-center gap-2 sm:gap-2.5 md:gap-3 touch-manipulation"
                                :class="selectedCategory === category.key ? 'bg-primary text-white shadow-md' : 'hover:bg-gray-100 text-[#121317]'"
                            >
                                <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[18px] flex-shrink-0" x-text="category.icon"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-xs sm:text-sm" x-text="category.name"></div>
                                    <div class="text-[10px] sm:text-xs opacity-75 truncate" x-text="getFilterCount(category.key) + ' selected'"></div>
                                </div>
                                <span x-show="getFilterCount(category.key) > 0" 
                                      class="bg-white text-primary text-[10px] sm:text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-bold flex-shrink-0"
                                      x-text="getFilterCount(category.key)">
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right Panel - Filter Options -->
                <div class="flex-1 overflow-y-auto bg-white min-w-0">
                    <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                        <template x-if="selectedCategory === 'propertyType'">
                            <div>
                                <h4 class="text-sm sm:text-base md:text-xl font-bold text-[#121317] mb-2 sm:mb-3 md:mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">home</span>
                                    Property Type
                                </h4>
                                <div class="space-y-2 sm:space-y-3">
                                    <template x-for="type in propertyTypes" :key="type.value">
                                        <label class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors min-h-[44px] touch-manipulation">
                                            <input type="radio" 
                                                   name="propertyType" 
                                                   :value="type.value"
                                                   x-model="filters.propertyType"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-xs sm:text-sm font-medium text-[#121317]" x-text="type.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'priceRange'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">attach_money</span>
                                    Price Range
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-[#121317] mb-2">Minimum Price</label>
                                        <select x-model="filters.minPrice" 
                                                class="w-full h-12 px-4 bg-white border border-gray-200 rounded-lg text-[#121317] text-sm font-medium hover:border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                                            <option value="">No Minimum</option>
                                            <template x-for="price in priceOptions" :key="price.value">
                                                <option :value="price.value" x-text="price.label"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#121317] mb-2">Maximum Price</label>
                                        <select x-model="filters.maxPrice" 
                                                class="w-full h-12 px-4 bg-white border border-gray-200 rounded-lg text-[#121317] text-sm font-medium hover:border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                                            <option value="">No Maximum</option>
                                            <template x-for="price in priceOptions" :key="price.value">
                                                <option :value="price.value" x-text="price.label"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'configuration'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">meeting_room</span>
                                    Configuration
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="config in configurations" :key="config.value">
                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="checkbox" 
                                                   :value="config.value"
                                                   x-model="filters.configuration"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-sm font-medium text-[#121317]" x-text="config.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'carpetArea'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">square_foot</span>
                                    Carpet Area
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="area in carpetAreas" :key="area.value">
                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="carpetArea" 
                                                   :value="area.value"
                                                   x-model="filters.carpetArea"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-sm font-medium text-[#121317]" x-text="area.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'floorPreference'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">stairs</span>
                                    Floor Preference
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="floor in floorOptions" :key="floor.value">
                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="floorPreference" 
                                                   :value="floor.value"
                                                   x-model="filters.floorPreference"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-sm font-medium text-[#121317]" x-text="floor.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'bathrooms'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">bathtub</span>
                                    Bathrooms
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="bathroom in bathroomOptions" :key="bathroom.value">
                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="bathrooms" 
                                                   :value="bathroom.value"
                                                   x-model="filters.bathrooms"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-sm font-medium text-[#121317]" x-text="bathroom.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="selectedCategory === 'furnishing'">
                            <div>
                                <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">star</span>
                                    Furnishing
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="furnish in furnishingOptions" :key="furnish.value">
                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="furnishing" 
                                                   :value="furnish.value"
                                                   x-model="filters.furnishing"
                                                   class="text-primary rounded focus:ring-primary/20">
                                            <span class="text-sm font-medium text-[#121317]" x-text="furnish.label"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 border-t border-gray-200 bg-white sticky bottom-0">
                <button @click="resetCurrentCategory()" 
                        class="flex-1 px-4 sm:px-6 py-3 min-h-[44px] text-sm font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50 touch-manipulation">
                    Reset Category
                </button>
                <button @click="applyFilters()" 
                        class="flex-1 px-4 sm:px-6 py-3 min-h-[44px] bg-primary text-white rounded-lg font-bold text-sm hover:bg-blue-800 transition-colors touch-manipulation">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>