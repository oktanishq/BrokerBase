<section class="px-6 lg:px-10 py-10 pb-2 sticky top-[71px] z-40 bg-white shadow-sm border-b border-gray-100 pt-3">
    <div class="mb-3">
        <div class="flex gap-3">
            <!-- Filter Button -->
            <button wire:click="openFilters"
                    class="h-12 px-4 bg-[#f1f1f4] hover:bg-gray-200 text-[#121317] rounded-full flex items-center gap-2 text-sm font-medium transition-colors shrink-0">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                <span class="hidden sm:inline">More Filters</span>
                <span class="material-symbols-outlined text-[16px] transition-transform @if($showFilters) rotate-180 @endif">expand_more</span>
                @if($this->getActiveFilterCount() > 0)
                    <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                        {{ $this->getActiveFilterCount() }}
                    </span>
                @endif
            </button>

            <label class="relative flex-1 items-center">
                <span class="absolute left-4 text-[#666e85] material-symbols-outlined top-1/2 -translate-y-1/2">search</span>
                <input wire:model.live="search" class="w-full bg-[#f1f1f4] text-[#121317] placeholder:text-[#666e85] h-12 rounded-full pl-20 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 border-none text-base transition-all" placeholder="Search location or building..." type="text"/>
            </label>
        </div>
    </div>
    <div class="flex flex-col gap-3">
        <div class="flex gap-2 overflow-x-auto no-scrollbar mask-linear-fade pb-2">
            <button wire:click="setCategory('all')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-semibold shadow-md shadow-blue-900/10 flex-shrink-0 transition-colors',
                        'bg-primary text-white' => $category === 'all',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== 'all'
                    ])>
                All
            </button>
            <button wire:click="setCategory('sale')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-medium transition-colors flex-shrink-0',
                        'bg-primary text-white' => $category === 'sale',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== 'sale'
                    ])>
                For Sale
            </button>
            <button wire:click="setCategory('rent')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-medium transition-colors flex-shrink-0',
                        'bg-primary text-white' => $category === 'rent',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== 'rent'
                    ])>
                For Rent
            </button>
            <button wire:click="setCategory('2bhk')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-medium transition-colors flex-shrink-0',
                        'bg-primary text-white' => $category === '2bhk',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== '2bhk'
                    ])>
                2 BHK
            </button>
            <button wire:click="setCategory('3bhk')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-medium transition-colors flex-shrink-0',
                        'bg-primary text-white' => $category === '3bhk',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== '3bhk'
                    ])>
                3 BHK
            </button>
            <button wire:click="setCategory('commercial')"
                    @class([
                        'whitespace-nowrap h-9 px-5 rounded-full text-sm font-medium transition-colors flex-shrink-0',
                        'bg-primary text-white' => $category === 'commercial',
                        'bg-[#f1f1f4] text-[#121317] hover:bg-gray-200' => $category !== 'commercial'
                    ])>
                Commercial
            </button>
        </div>
    </div>

    <!-- Filter Modal -->
    @if($showFilters)
        <div class="fixed inset-0 z-[10]">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" wire:click="closeFilters"></div>

            <!-- Modal Content -->
            <div class="absolute bottom-0 left-0 right-0 md:inset-auto md:top-1/2 md:left-1/2 md:transform md:-translate-x-1/2 md:-translate-y-1/2 w-full md:w-[90vw] md:max-w-6xl lg:max-w-7xl h-[85vh] md:h-auto md:rounded-2xl bg-white shadow-2xl overflow-hidden">
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
                        <button wire:click="resetFilters"
                                class="hidden md:inline-flex px-3 py-1.5 text-xs font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                            Reset All
                        </button>
                        <button wire:click="closeFilters"
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
                            @foreach($filterCategories as $cat)
                                <button wire:click="selectCategory('{{ $cat['key'] }}')"
                                        @class([
                                            'w-full text-left p-2.5 sm:p-3 md:p-2.5 min-h-[44px] rounded-lg transition-colors flex items-center gap-2 sm:gap-2.5 md:gap-3 touch-manipulation',
                                            'bg-primary text-white shadow-md' => $selectedCategory === $cat['key'],
                                            'hover:bg-gray-100 text-[#121317]' => $selectedCategory !== $cat['key']
                                        ])>
                                    <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[18px] flex-shrink-0">{{ $cat['icon'] }}</span>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-xs sm:text-sm">{{ $cat['name'] }}</div>
                                        <div class="text-[10px] sm:text-xs opacity-75 truncate">{{ $this->getFilterCount($cat['key']) }} selected</div>
                                    </div>
                                    @if($this->getFilterCount($cat['key']) > 0)
                                        <span class="bg-white text-primary text-[10px] sm:text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-bold flex-shrink-0">
                                            {{ $this->getFilterCount($cat['key']) }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Panel - Filter Options -->
                    <div class="flex-1 overflow-y-auto bg-white min-w-0">
                        <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                            @if($selectedCategory === 'propertyType')
                                <div>
                                    <h4 class="text-sm sm:text-base md:text-xl font-bold text-[#121317] mb-2 sm:mb-3 md:mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">home</span>
                                        Property Type
                                    </h4>
                                    <div class="space-y-2 sm:space-y-3">
                                        @foreach($propertyTypes as $type)
                                            <label class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors min-h-[44px] touch-manipulation">
                                                <input type="radio" wire:model.live="filters.propertyType" value="{{ $type['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-xs sm:text-sm font-medium text-[#121317]">{{ $type['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'priceRange')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">attach_money</span>
                                        Price Range
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-[#121317] mb-2">Minimum Price</label>
                                            <select wire:model.live="filters.minPrice"
                                                    class="w-full h-12 px-4 bg-white border border-gray-200 rounded-lg text-[#121317] text-sm font-medium hover:border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                                                <option value="">No Minimum</option>
                                                @foreach($priceOptions as $price)
                                                    <option value="{{ $price['value'] }}">{{ $price['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-[#121317] mb-2">Maximum Price</label>
                                            <select wire:model.live="filters.maxPrice"
                                                    class="w-full h-12 px-4 bg-white border border-gray-200 rounded-lg text-[#121317] text-sm font-medium hover:border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                                                <option value="">No Maximum</option>
                                                @foreach($priceOptions as $price)
                                                    <option value="{{ $price['value'] }}">{{ $price['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'configuration')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">meeting_room</span>
                                        Configuration
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($configurations as $config)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="checkbox" wire:model.live="filters.configuration" value="{{ $config['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-sm font-medium text-[#121317]">{{ $config['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'carpetArea')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">square_foot</span>
                                        Carpet Area
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($carpetAreas as $area)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="radio" wire:model.live="filters.carpetArea" value="{{ $area['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-sm font-medium text-[#121317]">{{ $area['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'floorPreference')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">stairs</span>
                                        Floor Preference
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($floorOptions as $floor)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="radio" wire:model.live="filters.floorPreference" value="{{ $floor['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-sm font-medium text-[#121317]">{{ $floor['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'bathrooms')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">bathtub</span>
                                        Bathrooms
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($bathroomOptions as $bathroom)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="radio" wire:model.live="filters.bathrooms" value="{{ $bathroom['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-sm font-medium text-[#121317]">{{ $bathroom['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedCategory === 'furnishing')
                                <div>
                                    <h4 class="text-lg font-bold text-[#121317] mb-4 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">star</span>
                                        Furnishing
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($furnishingOptions as $furnish)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="radio" wire:model.live="filters.furnishing" value="{{ $furnish['value'] }}"
                                                       class="text-primary rounded focus:ring-primary/20">
                                                <span class="text-sm font-medium text-[#121317]">{{ $furnish['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 border-t border-gray-200 bg-white sticky bottom-0">
                    <button wire:click="resetCurrentCategory"
                            class="flex-1 px-4 sm:px-6 py-3 min-h-[44px] text-sm font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50 touch-manipulation">
                        Reset Category
                    </button>
                    <button wire:click="applyFilters"
                            class="flex-1 px-4 sm:px-6 py-3 min-h-[44px] bg-primary text-white rounded-lg font-bold text-sm hover:bg-blue-800 transition-colors touch-manipulation">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
