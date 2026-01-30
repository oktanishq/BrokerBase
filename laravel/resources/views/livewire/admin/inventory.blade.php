<div x-data="{
    statusOpen: false,
    typeOpen: false,
    sortOpen: false,
    viewMode: $wire.viewMode,
    init() {
        // Load view mode from LocalStorage on init
        const savedView = localStorage.getItem('inventory_view');
        if (savedView && (savedView === 'list' || savedView === 'grid')) {
            this.viewMode = savedView;
            $wire.setViewMode(savedView);
        } else {
            this.viewMode = $wire.viewMode;
        }
        
        // Watch for view mode changes from Livewire
        $watch('$wire.viewMode', (value) => {
            this.viewMode = value;
            localStorage.setItem('inventory_view', value);
        });
    },
    closeDropdowns() {
        this.statusOpen = false;
        this.typeOpen = false;
        this.sortOpen = false;
    },
    toggleViewMode() {
        const newMode = this.viewMode === 'list' ? 'grid' : 'list';
        this.viewMode = newMode;
        localStorage.setItem('inventory_view', newMode);
        $wire.setViewMode(newMode);
    },
    getSortIcon(sortValue) {
        const icons = {
            'newest': 'schedule',
            'oldest': 'schedule',
            'updated_desc': 'edit',
            'price_asc': 'payments',
            'price_desc': 'payments',
            'title_asc': 'sort_by_alpha',
            'title_desc': 'sort_by_alpha',
            'size_asc': 'square_foot',
            'size_desc': 'square_foot'
        };
        return icons[sortValue] || 'sort';
    },
    getSortLabel() {
        const labels = {
            'newest': 'Newest',
            'oldest': 'Oldest',
            'updated_desc': 'Latest Updated',
            'price_asc': 'Price: Low to High',
            'price_desc': 'Price: High to Low',
            'title_asc': 'A to Z',
            'title_desc': 'Z to A',
            'size_asc': 'Small to Large',
            'size_desc': 'Large to Small'
        };
        return labels[$wire.sortBy] || 'Newest';
    }
}" @property-updated.window="$wire.call('$refresh')">
    <!-- Edit Property Modal -->
    @livewire('admin.inventory.edit-property-modal')

    <!-- Delete Confirmation Modal -->
    @livewire('admin.inventory.delete-confirmation-modal')

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">My Inventory</h1>
        <x-admin.AddPropertyModal />
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between sticky top-0 z-10 mb-8">
        <!-- Search Input -->
        <div class="relative w-full md:w-96 group">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <span class="material-symbols-outlined">search</span>
            </span>
            <input wire:model.live.debounce.300ms="searchTerm" class="w-full py-2.5 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                   placeholder="Search by id, title, location..." type="text"/>
        </div>

        <!-- Filters and View Toggle -->
        <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-3 justify-between md:justify-end">
            <div class="flex gap-3 w-full sm:w-auto" @click.away="closeDropdowns()">
                <!-- Status Filter -->
                <div class="relative">
                    <button @click="statusOpen = !statusOpen; typeOpen = false; sortOpen = false"
                            class="flex items-center justify-between w-full sm:w-44 px-4 py-2.5 rounded-xl border-2 border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 hover:border-slate-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-slate-700 font-semibold shadow-sm hover:shadow-md">
                        <span class="flex items-center space-x-2 text-sm">
                            <span class="text-slate-500">Status:</span>
                            <span x-text="$wire.statusFilter === 'all' ? 'All' : ($wire.statusFilter === 'available' ? 'Available' : ($wire.statusFilter === 'sold' ? 'Sold' : 'Draft'))"></span>
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="statusOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>

                    <div x-show="statusOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-1" class="absolute top-full left-0 mt-2 w-44 bg-white border-2 border-slate-200 rounded-xl shadow-xl shadow-slate-900/10 overflow-hidden z-20">
                        <ul class="py-2">
                            <li>
                                <button @click="$wire.set('statusFilter', 'all'); statusOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.statusFilter === 'all' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-slate-400 mr-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                        All
                                    </span>
                                    <span x-show="$wire.statusFilter === 'all'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('statusFilter', 'available'); statusOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.statusFilter === 'available' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-emerald-400 mr-3 shadow-sm shadow-emerald-400/50 group-hover:scale-110 transition-transform"></span>
                                        Available
                                    </span>
                                    <span x-show="$wire.statusFilter === 'available'" class="material-symbols-outlined text-emerald-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('statusFilter', 'sold'); statusOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.statusFilter === 'sold' ? 'bg-rose-50 text-rose-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-rose-400 mr-3 shadow-sm shadow-rose-400/50 group-hover:scale-110 transition-transform"></span>
                                        Sold
                                    </span>
                                    <span x-show="$wire.statusFilter === 'sold'" class="material-symbols-outlined text-rose-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('statusFilter', 'draft'); statusOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.statusFilter === 'draft' ? 'bg-slate-50 text-slate-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-slate-400 mr-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                        Draft
                                    </span>
                                    <span x-show="$wire.statusFilter === 'draft'" class="material-symbols-outlined text-slate-600 text-base">check</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="relative">
                    <button @click="typeOpen = !typeOpen; statusOpen = false; sortOpen = false"
                            class="flex items-center justify-between w-full sm:w-44 px-4 py-2.5 rounded-xl border-2 border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 hover:border-slate-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-slate-700 font-semibold shadow-sm hover:shadow-md">
                        <span class="flex items-center space-x-2 text-sm">
                            <span class="text-slate-500">Type:</span>
                            <span x-text="$wire.typeFilter === 'all' ? 'All' : ($wire.typeFilter.charAt(0).toUpperCase() + $wire.typeFilter.slice(1))"></span>
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="typeOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>

                    <div x-show="typeOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-1" class="absolute top-full left-0 mt-2 w-44 bg-white border-2 border-slate-200 rounded-xl shadow-xl shadow-slate-900/10 overflow-hidden z-20">
                        <ul class="py-2">
                            <li>
                                <button @click="$wire.set('typeFilter', 'all'); typeOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.typeFilter === 'all' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-slate-400 mr-3 shadow-sm group-hover:scale-110 transition-transform"></span>
                                        All
                                    </span>
                                    <span x-show="$wire.typeFilter === 'all'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('typeFilter', 'apartment'); typeOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.typeFilter === 'apartment' ? 'bg-purple-50 text-purple-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-purple-400 mr-3 shadow-sm shadow-purple-400/50 group-hover:scale-110 transition-transform"></span>
                                        Apartment
                                    </span>
                                    <span x-show="$wire.typeFilter === 'apartment'" class="material-symbols-outlined text-purple-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('typeFilter', 'villa'); typeOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.typeFilter === 'villa' ? 'bg-amber-50 text-amber-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-amber-400 mr-3 shadow-sm shadow-amber-400/50 group-hover:scale-110 transition-transform"></span>
                                        Villa
                                    </span>
                                    <span x-show="$wire.typeFilter === 'villa'" class="material-symbols-outlined text-amber-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('typeFilter', 'office'); typeOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.typeFilter === 'office' ? 'bg-cyan-50 text-cyan-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-cyan-400 mr-3 shadow-sm shadow-cyan-400/50 group-hover:scale-110 transition-transform"></span>
                                        Office
                                    </span>
                                    <span x-show="$wire.typeFilter === 'office'" class="material-symbols-outlined text-cyan-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('typeFilter', 'commercial'); typeOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.typeFilter === 'commercial' ? 'bg-pink-50 text-pink-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-pink-400 mr-3 shadow-sm shadow-pink-400/50 group-hover:scale-110 transition-transform"></span>
                                        Commercial
                                    </span>
                                    <span x-show="$wire.typeFilter === 'commercial'" class="material-symbols-outlined text-pink-600 text-base">check</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <!-- Mobile Icon-Only Version (shown when space is tight) -->
                    <button @click="sortOpen = !sortOpen; statusOpen = false; typeOpen = false"
                            class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg border-2 border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 hover:border-slate-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-slate-700 font-semibold shadow-sm hover:shadow-md">
                        <span class="material-symbols-outlined text-slate-400 text-lg" :class="sortOpen ? 'text-royal-blue' : ''">sort</span>
                    </button>

                    <!-- Desktop Full Version -->
                    <button @click="sortOpen = !sortOpen; statusOpen = false; typeOpen = false"
                            class="hidden md:flex items-center justify-between w-full sm:w-44 px-4 py-2.5 rounded-xl border-2 border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 hover:border-slate-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-slate-700 font-semibold shadow-sm hover:shadow-md">
                        <span class="flex items-center space-x-2 text-sm truncate">
                            <span class="text-slate-500 whitespace-nowrap">Sort:</span>
                            <span class="truncate" x-text="getSortLabel()"></span>
                        </span>
                        <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300 flex-shrink-0 ml-2" :class="sortOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>

                    <div x-show="sortOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-1" class="absolute top-full right-0 mt-2 w-56 bg-white border-2 border-slate-200 rounded-xl shadow-xl shadow-slate-900/10 overflow-hidden z-20">
                        <ul class="py-2">
                            <li>
                                <button @click="$wire.set('sortBy', 'newest'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'newest' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">schedule</span>
                                        Newest First
                                    </span>
                                    <span x-show="$wire.sortBy === 'newest'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'oldest'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'oldest' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">schedule</span>
                                        Oldest First
                                    </span>
                                    <span x-show="$wire.sortBy === 'oldest'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'updated_desc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'updated_desc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">edit</span>
                                        Latest Updated
                                    </span>
                                    <span x-show="$wire.sortBy === 'updated_desc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'price_asc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'price_asc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">payments</span>
                                        Price: Low to High
                                    </span>
                                    <span x-show="$wire.sortBy === 'price_asc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'price_desc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'price_desc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">payments</span>
                                        Price: High to Low
                                    </span>
                                    <span x-show="$wire.sortBy === 'price_desc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'title_asc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'title_asc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">sort_by_alpha</span>
                                        Title: A to Z
                                    </span>
                                    <span x-show="$wire.sortBy === 'title_asc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'title_desc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'title_desc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">sort_by_alpha</span>
                                        Title: Z to A
                                    </span>
                                    <span x-show="$wire.sortBy === 'title_desc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'size_asc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'size_asc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">square_foot</span>
                                        Size: Small to Large
                                    </span>
                                    <span x-show="$wire.sortBy === 'size_asc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.set('sortBy', 'size_desc'); sortOpen = false"
                                        class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                        :class="$wire.sortBy === 'size_desc' ? 'bg-blue-50 text-blue-700' : 'text-slate-700'">
                                    <span class="flex items-center">
                                        <span class="material-symbols-outlined text-slate-400 mr-3 text-lg">square_foot</span>
                                        Size: Large to Small
                                    </span>
                                    <span x-show="$wire.sortBy === 'size_desc'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- View Toggle (Desktop only - mobile has floating button) -->
            <div class="hidden md:flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200 ml-auto sm:ml-0">
                <button @click="toggleViewMode()"
                        class="p-1.5 rounded transition-all"
                        :class="viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                        title="Grid View">
                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                </button>
                <button @click="toggleViewMode()"
                        class="p-1.5 rounded transition-all"
                        :class="viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                        title="List View">
                    <span class="material-symbols-outlined text-[22px]">view_list</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Property List -->
    @if($this->properties->count() > 0)
        <div class="mt-8">
            <!-- List View -->
            @if($viewMode === 'list')
                <div class="flex flex-col gap-4">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-list-card', ['property' => $property], key('list-' . $property->id))
                    @endforeach
                </div>
            @endif

            <!-- Grid View -->
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-grid-card', ['property' => $property], key('grid-' . $property->id))
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- No Results Message -->
    @if($this->properties->count() === 0)
        <div class="text-center py-12 mt-8">
            <div class="flex flex-col items-center gap-4">
                <span class="material-symbols-outlined text-6xl text-gray-300">search_off</span>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                    <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    @if($this->properties->count() > 0)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4">
        <div class="flex items-center gap-4">
            <!-- Items per page dropdown -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-500">Show:</label>
                <select wire:model.live="perPage" class="py-1 px-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">per page</span>
            </div>

            <!-- Results info -->
            <span class="text-sm text-gray-500">{{ $this->showingCount }} properties found</span>
        </div>

        <!-- Pagination controls -->
        <div class="flex items-center gap-2">
            <button wire:click="previousPage"
                    @disabled($this->properties->onFirstPage())
                    class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg transition-colors {{ $this->properties->onFirstPage() ? 'text-gray-400 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50' }}">
                Previous
            </button>

            <!-- Page numbers -->
            <div class="flex items-center gap-1">
                @for ($page = 1; $page <= $this->totalPages; $page++)
                    <button wire:click="gotoPage({{ $page }})"
                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors {{ $this->properties->currentPage() === $page ? 'bg-royal-blue text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $page }}
                    </button>
                @endfor
            </div>

            <button wire:click="nextPage"
                    @disabled(!$this->properties->hasMorePages())
                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors {{ !$this->properties->hasMorePages() ? 'text-gray-400 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800' }}">
                Next
            </button>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all bg-green-500">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all bg-red-500">
            {{ session('error') }}
        </div>
    @endif

    <!-- Mobile Floating View Toggle -->
    <div x-show="!statusOpen && !typeOpen && !sortOpen"
         x-transition
         class="fixed bottom-6 right-6 z-40 md:hidden">
        <button @click="toggleViewMode()"
                class="flex items-center justify-center w-14 h-14 bg-royal-blue text-white rounded-full shadow-lg shadow-blue-900/30 hover:bg-blue-800 transition-all active:scale-95">
            <span class="material-symbols-outlined text-2xl" x-text="viewMode === 'grid' ? 'view_list' : 'grid_view'"></span>
        </button>
    </div>
</div>