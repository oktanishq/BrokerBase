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
                <input wire:model.live.debounce.300ms="search" maxlength="100" class="w-full bg-[#f1f1f4] text-[#121317] placeholder:text-[#666e85] h-12 rounded-full pl-20 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 border-none text-base transition-all" placeholder="Search by ID, location, title or price..." type="text"/>
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

    <!-- Prevent body scroll when modal is open -->
    @if($showFilters)
        <style>
            body {
                overflow: hidden;
                touch-action: none;
                -webkit-overflow-scrolling: touch;
            }
            html {
                overflow: hidden;
                touch-action: none;
            }
        </style>
    @endif

    <!-- Filter Modal -->
    @if($showFilters)
        <div class="fixed inset-0 z-[10]">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" wire:click="closeFilters"></div>

            <!-- Modal Content -->
            <div class="absolute bottom-0 left-0 right-0 md:inset-auto md:top-1/2 md:left-1/2 md:transform md:-translate-x-1/2 md:-translate-y-1/2 w-full md:w-[95vw] md:max-w-4xl lg:max-w-5xl h-[90vh] md:h-[80vh] md:rounded-2xl bg-white shadow-2xl flex flex-col">
                <!-- Pull Handle for Mobile -->
                <div class="md:hidden flex justify-center pt-3 pb-2">
                    <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
                </div>

                <!-- Modal Header -->
                <div class="flex items-center justify-between p-3 sm:p-4 md:p-6 border-b border-gray-200 bg-white sticky top-0 z-[99999] md:rounded-2xl">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px] sm:text-[24px] text-primary">tune</span>
                        <div>
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-[#121317]">Filter Properties</h3>
                            <p class="text-xs sm:text-sm text-gray-600">Find your perfect property</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(count($savedFilters) > 0)
                            <div class="relative">
                                <button wire:click="$toggle('showSavedDropdown')"
                                        class="hidden md:flex px-3 py-2 text-sm font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50 items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">bookmark</span>
                                    Saved Filters
                                    <span class="material-symbols-outlined text-[14px]">expand_more</span>
                                </button>
                                @if($showSavedDropdown ?? false)
                                    <div class="absolute right-0 top-full mt-1 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-[100000]">
                                        <div class="p-2">
                                            <div class="text-xs font-medium text-gray-500 mb-2 px-2">Saved Filters</div>
                                            @foreach($savedFilters as $name => $filterData)
                                                <button wire:click="loadSavedFilter('{{ $name }}')"
                                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded flex items-center justify-between">
                                                    <span>{{ $name }}</span>
                                                    <button wire:click.stop="deleteSavedFilter('{{ $name }}')"
                                                            class="text-red-500 hover:text-red-700 p-1">
                                                        <span class="material-symbols-outlined text-[14px]">delete</span>
                                                    </button>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <button wire:click="saveCurrentFilters"
                                class="hidden md:flex px-3 py-2 text-sm font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50 items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">bookmark_add</span>
                            Save Filter
                        </button>
                        <button wire:click="resetFilters"
                                class="px-3 py-2 text-sm font-medium text-[#666e85] hover:text-[#121317] transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                            Reset All
                        </button>
                        <button wire:click="closeFilters"
                                class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>

                <!-- Applied Filters Summary -->
                @php $appliedFilters = $this->getAppliedFiltersSummary() @endphp
                @if(count($appliedFilters) > 0)
                    <div class="px-3 sm:px-4 md:px-6 py-3 bg-blue-50 border-b border-blue-100 ">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-[16px] text-blue-600">filter_list</span>
                            <span class="text-sm font-medium text-blue-900">Applied Filters</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($appliedFilters as $filter)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-blue-200 rounded-full text-xs font-medium text-blue-800">
                                    {{ $filter['label'] }}
                                    <button wire:click="removeFilter('{{ $filter['key'] }}')"
                                            class="ml-1 text-blue-600 hover:text-blue-800">
                                        <span class="material-symbols-outlined text-[12px]">close</span>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Modal Body - Clean Accordion Layout -->
                <div class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="p-3 sm:p-4 space-y-2">
                        @foreach($filterCategories as $cat)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Accordion Header -->
                                <button wire:click="toggleCategory('{{ $cat['key'] }}')"
                                        class="w-full flex items-center justify-between p-3 sm:p-4 bg-white hover:bg-gray-50 transition-colors border-b border-gray-100 touch-manipulation min-h-[48px]">
                                    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                        <span class="material-symbols-outlined text-[18px] sm:text-[20px] text-primary flex-shrink-0">{{ $cat['icon'] }}</span>
                                        <div class="text-left min-w-0 flex-1">
                                            <h4 class="font-semibold text-[#121317] text-sm sm:text-base truncate">{{ $cat['name'] }}</h4>
                                            @if($this->getFilterCount($cat['key']) > 0)
                                                <p class="text-xs text-primary font-medium">{{ $this->getFilterCount($cat['key']) }} selected</p>
                                            @else
                                                <p class="text-xs text-gray-500 hidden sm:block">{{ $cat['description'] ?? 'Select your preferences' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-[18px] sm:text-[20px] transition-transform flex-shrink-0 ml-2"
                                          @class(['rotate-180' => in_array($cat['key'], $expandedCategories)])>
                                        expand_more
                                    </span>
                                </button>

                                <!-- Filter Content -->
                                @if(in_array($cat['key'], $expandedCategories))
                                    <div class="p-3 sm:p-4 bg-white">
                                            @if(in_array($cat['key'], ['propertyType', 'configuration', 'carpetArea', 'floorPreference', 'bathrooms', 'furnishing']))
                                                <!-- Search for filterable categories -->
                                                <div class="mb-3">
                                                    <input wire:model.live="filterSearches.{{ $cat['key'] }}" maxlength="100"
                                                           type="text"
                                                           placeholder="Search {{ strtolower($cat['name']) }}..."
                                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'propertyType')
                                                <div class="grid grid-cols-1 gap-2">
                                                    @foreach($this->getFilteredOptions('propertyType', $propertyTypes) as $type)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="radio" wire:model.live="filters.propertyType" value="{{ $type['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $type['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'priceRange')
                                                <div class="space-y-4">
                                                    <div class="space-y-3">
                                                        <div>
                                                            <label class="block text-sm font-medium text-[#121317] mb-2">Minimum Price (₹)</label>
                                                            <input type="number"
                                                                   wire:model.live="filters.minPrice"
                                                                   placeholder="No minimum"
                                                                   min="0"
                                                                   step="100000"
                                                                   maxlength="12"
                                                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-[#121317] mb-2">Maximum Price (₹)</label>
                                                            <input type="number"
                                                                   wire:model.live="filters.maxPrice"
                                                                   placeholder="No maximum"
                                                                   min="0"
                                                                   step="100000"
                                                                   maxlength="12"
                                                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                                        </div>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Enter amounts in rupees (e.g., 5000000 for ₹50 Lacs)
                                                    </div>
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'configuration')
                                                <div class="grid grid-cols-1 gap-2">
                                                    @foreach($this->getFilteredOptions('configuration', $configurations) as $config)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="checkbox" wire:model.live="filters.configuration" value="{{ $config['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $config['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'carpetArea')
                                                <div class="space-y-3">
                                                    @foreach($this->getFilteredOptions('carpetArea', $carpetAreas) as $area)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="radio" wire:model.live="filters.carpetArea" value="{{ $area['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $area['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'floorPreference')
                                                <div class="space-y-3">
                                                    @foreach($this->getFilteredOptions('floorPreference', $floorOptions) as $floor)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="radio" wire:model.live="filters.floorPreference" value="{{ $floor['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $floor['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'bathrooms')
                                                <div class="space-y-3">
                                                    @foreach($this->getFilteredOptions('bathrooms', $bathroomOptions) as $bathroom)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="radio" wire:model.live="filters.bathrooms" value="{{ $bathroom['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $bathroom['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($cat['key'] === 'furnishing')
                                                <div class="space-y-3">
                                                    @foreach($this->getFilteredOptions('furnishing', $furnishingOptions) as $furnish)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                            <input type="radio" wire:model.live="filters.furnishing" value="{{ $furnish['value'] }}"
                                                                   class="text-primary rounded focus:ring-primary/20">
                                                            <span class="text-sm font-medium text-[#121317]">{{ $furnish['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                <!-- Modal Footer - Always Visible at Bottom -->
                <div class="mt-auto border-t border-gray-200 bg-white shadow-lg md:rounded-2xl">
                    <div class="flex flex-col sm:flex-row gap-3 p-4 sm:p-6">
                        <button wire:click="applyAndStay"
                                class="flex-1 px-6 py-4 min-h-[52px] bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 active:bg-blue-800 transition-colors touch-manipulation shadow-sm">
                            Apply & Continue Filtering
                        </button>
                        <button wire:click="applyAndClose"
                                class="flex-1 px-6 py-4 min-h-[52px] bg-primary text-white rounded-lg font-bold text-sm hover:bg-blue-800 active:bg-blue-900 transition-colors touch-manipulation shadow-sm">
                            Apply & Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Save Filter Dialog -->
    @if($showSaveDialog)
        <div class="fixed inset-0 z-[100000] flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-50" wire:click="$set('showSaveDialog', false)"></div>
            <div class="relative bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-bold text-[#121317] mb-4">Save Filter</h3>
                <input wire:model="savedFilterName"
                       maxlength="50"
                       type="text"
                       placeholder="Enter filter name..."
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg mb-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <div class="flex gap-3">
                    <button wire:click="$set('showSaveDialog', false)"
                            class="flex-1 px-4 py-2 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="saveFilter"
                            class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
