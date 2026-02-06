<div>
    <!-- Edit Property Modal -->
    @if($isOpen)
        <div x-data
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"
                 wire:click="closeModal"></div>

            <!-- Modal Content -->
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-slate-900">Edit Listing</h2>
                    <button wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto flex-1 flex flex-col">

                <!-- Session Error Messages -->
                @if(session('error'))
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-lg px-4 py-3 flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-500">error</span>
                        <span class="text-sm text-red-700">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Session Success Messages -->
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-500">check_circle</span>
                        <span class="text-sm text-green-700">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Property Preview Section -->
                @if($property)
                    <div class="p-6 pb-4">
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-16 h-12 bg-cover bg-center rounded-md shrink-0"
                                 style="background-image: url('{{ $property['image'] ?? '/images/placeholder-property.jpg' }}')"></div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $property['title'] ?? 'Loading...' }}</h3>
                                <p class="text-xs text-gray-500 mt-1">ID: #{{ $property['id'] ?? 'N/A' }} • Added recently</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tab Navigation -->
                <div x-data="{ scrollLeft: 0 }" class="relative px-4 md:px-6 pb-4">
                    <!-- Left Shadow Hint (Mobile only) -->
                    <div class="absolute left-4 top-1 bottom-1 w-8 bg-gradient-to-r from-gray-100 to-transparent pointer-events-none z-10 md:hidden" :class="scrollLeft > 0 ? 'opacity-100' : 'opacity-0'"></div>
                    
                    <!-- Right Shadow Hint (Mobile only) -->
                    <div class="absolute right-4 top-1 bottom-1 w-8 bg-gradient-to-l from-gray-100 to-transparent pointer-events-none z-10 md:hidden" :class="scrollLeft < $el.scrollWidth - $el.clientWidth - 16 ? 'opacity-100' : 'opacity-0'"></div>
                    
                    <!-- Tabs Container -->
                    <div class="flex overflow-x-auto md:overflow-visible justify-evenly md:justify-between gap-1 bg-gray-100 p-1 rounded-lg scrollbar-hide snap-x md:snap-none" 
                         style="scrollbar-width: none; -ms-overflow-style: none;"
                         x-on:scroll="scrollLeft = $el.scrollLeft">
                        <style>
                            .scrollbar-hide::-webkit-scrollbar { display: none; }
                        </style>
                        <button wire:click="setTab('overview')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'overview' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Overview
                        </button>
                        <button wire:click="setTab('basic-info')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'basic-info' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Basic Info
                        </button>
                        <button wire:click="setTab('details')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'details' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Details
                        </button>
                        <button wire:click="setTab('image')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'image' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Image
                        </button>
                        <button wire:click="setTab('location')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'location' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Location
                        </button>
                        <button wire:click="setTab('private')"
                                class="flex-shrink-0 md:flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all whitespace-nowrap snap-center md:snap-none {{ $currentTab === 'private' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Private
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="flex-1 px-6 pb-6">

                    <!-- Overview Tab -->
                    <div x-show="$wire.currentTab === 'overview'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-6">

                        <!-- Status and Labels Section -->
                        <div class="space-y-4">
                            <!-- Listing Status -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Listing Status</label>
                                <div class="flex bg-gray-100 p-1 rounded-lg">
                                    <button wire:click="setStatus('available')"
                                            class="{{ $status === 'available' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }} flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                                        Available
                                    </button>
                                    <button wire:click="setStatus('sold')"
                                            class="{{ $status === 'sold' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }} flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                                        Sold
                                    </button>
                                    <button wire:click="setStatus('draft')"
                                            class="{{ $status === 'draft' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }} flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                                        Draft
                                    </button>
                                </div>
                            </div>

                            <!-- Public Badge and Featured Toggle -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Public Badge</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click="setLabelType('none')"
                                                class="{{ $label_type === 'none' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }} px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                            None
                                        </button>
                                        <button wire:click="setLabelType('new')"
                                                class="{{ $label_type === 'new' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }} px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                            New Arrival
                                        </button>
                                        <button wire:click="setLabelType('popular')"
                                                class="{{ $label_type === 'popular' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }} px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                            Popular
                                        </button>
                                        <button wire:click="setLabelType('verified')"
                                                class="{{ $label_type === 'verified' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }} px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                            Verified
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="inline-flex items-center cursor-pointer gap-3">
                                        <span class="text-sm font-medium text-slate-700">Featured Property</span>
                                        <div class="relative">
                                            <input wire:model.live="is_featured"
                                                   type="checkbox"
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-blue"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info Tab -->
                    <div x-show="$wire.currentTab === 'basic-info'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-xs font-medium text-gray-500">Title</label>
                                <input wire:model.live="title" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="Property title">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <div class="flex justify-between items-center">
                                    <label class="text-xs font-medium text-gray-500">Description</label>
                                    <span class="text-xs text-gray-400" x-text="'Characters: ' + ($wire.description ? $wire.description.length : 0)"></span>
                                </div>
                                <textarea wire:model.live="description" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none resize-none" placeholder="Property description"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Property Type</label>
                                <div x-data="{ typeOpen: false }" class="relative">
                                    <button @click="typeOpen = !typeOpen"
                                            class="flex items-center justify-between w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none transition-all">
                                        <span x-text="$wire.property_type === 'apartment' ? 'Apartment' : ($wire.property_type === 'villa' ? 'Villa' : ($wire.property_type === 'plot' ? 'Plot' : ($wire.property_type === 'commercial' ? 'Commercial' : ($wire.property_type === 'office' ? 'Office' : 'Select Type'))))"></span>
                                        <span class="material-symbols-outlined text-gray-400 text-lg transition-transform duration-300" :class="typeOpen ? 'rotate-180' : ''">expand_more</span>
                                    </button>

                                    <div x-show="typeOpen" @click.away="typeOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-1" class="absolute top-full left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-20">
                                        <ul class="py-1">
                                            <li>
                                                <button @click="$wire.set('property_type', 'apartment'); typeOpen = false"
                                                        class="w-full text-left px-3 py-2 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors"
                                                        :class="$wire.property_type === 'apartment' ? 'bg-blue-50 text-blue-700' : 'text-gray-700'">
                                                    <span>Apartment</span>
                                                    <span x-show="$wire.property_type === 'apartment'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button @click="$wire.set('property_type', 'villa'); typeOpen = false"
                                                        class="w-full text-left px-3 py-2 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors"
                                                        :class="$wire.property_type === 'villa' ? 'bg-blue-50 text-blue-700' : 'text-gray-700'">
                                                    <span>Villa</span>
                                                    <span x-show="$wire.property_type === 'villa'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button @click="$wire.set('property_type', 'plot'); typeOpen = false"
                                                        class="w-full text-left px-3 py-2 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors"
                                                        :class="$wire.property_type === 'plot' ? 'bg-blue-50 text-blue-700' : 'text-gray-700'">
                                                    <span>Plot</span>
                                                    <span x-show="$wire.property_type === 'plot'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button @click="$wire.set('property_type', 'commercial'); typeOpen = false"
                                                        class="w-full text-left px-3 py-2 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors"
                                                        :class="$wire.property_type === 'commercial' ? 'bg-blue-50 text-blue-700' : 'text-gray-700'">
                                                    <span>Commercial</span>
                                                    <span x-show="$wire.property_type === 'commercial'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button @click="$wire.set('property_type', 'office'); typeOpen = false"
                                                        class="w-full text-left px-3 py-2 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors"
                                                        :class="$wire.property_type === 'office' ? 'bg-blue-50 text-blue-700' : 'text-gray-700'">
                                                    <span>Office</span>
                                                    <span x-show="$wire.property_type === 'office'" class="material-symbols-outlined text-blue-600 text-base">check</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @error('property_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400">₹</span>
                                    <input wire:model.live="price"
                                           x-data="{ 
                                                formatPrice(event) { 
                                                    event.target.value = event.target.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ','); 
                                                },
                                                get isEmpty() {
                                                    return !$wire.price || $wire.price === '';
                                                }
                                           }"
                                           @input="formatPrice($event)"
                                           type="text"
                                           class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none font-mono placeholder-gray-300"
                                           :placeholder="isEmpty ? 'TBD' : ''">
                                </div>
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Area (sqft)</label>
                                <input wire:model.live="area_sqft" type="number" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="1200">
                                @error('area_sqft') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Details Tab -->
                    <div x-show="$wire.currentTab === 'details'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Bedrooms</label>
                                <input wire:model.live="bedrooms" type="number" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="3">
                                @error('bedrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Bathrooms</label>
                                <input wire:model.live="bathrooms" type="number" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="2">
                                @error('bathrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Advanced Amenities Section -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-end">
                                <label class="block text-sm font-medium text-gray-700">Amenities</label>
                                <button @click="$wire.set('amenities', [])"
                                        x-show="$wire.amenities.filter(a => a && a.trim()).length > 0"
                                        class="text-xs text-orange-600 font-medium hover:text-orange-700 transition-colors">
                                    Clear All
                                </button>
                            </div>

                            <!-- Search and Add Amenities -->
                            <div x-data="{ searchOpen: false }" @click.away="searchOpen = false" class="relative group">
                                <div class="flex items-center bg-white border border-gray-200 rounded-lg px-3 py-2.5 focus-within:ring-2 focus-within:ring-orange-500 focus-within:border-transparent transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-gray-400 mr-2 text-base">search</span>
                                    <input wire:model.live="amenitiesSearch"
                                           @focus="searchOpen = true"
                                           @keydown.escape="searchOpen = false; $wire.set('amenitiesSearch', '')"
                                           type="text"
                                           class="w-full bg-transparent border-none outline-none text-gray-900 placeholder-gray-400 text-sm"
                                           placeholder="Search amenities (e.g. Pool, Gym, WiFi)...">
                                    <button wire:click="addAmenity"
                                            class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-3 py-1.5 rounded font-medium transition-colors ml-2">
                                        Add
                                    </button>
                                </div>

                                <!-- Dropdown with filtered results -->
                                <div x-show="searchOpen && $wire.availableAmenities.filter(a => a.name.toLowerCase().includes($wire.amenitiesSearch.toLowerCase()) && !$wire.amenities.includes(a.name)).length > 0"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-20 max-h-60 overflow-y-auto">

                                    <template x-for="amenity in $wire.availableAmenities.filter(a => a.name.toLowerCase().includes($wire.amenitiesSearch.toLowerCase()) && !$wire.amenities.includes(a.name))" :key="amenity.name">
                                        <button @click="$wire.addAmenity(amenity.name); searchOpen = false; $wire.set('amenitiesSearch', '')"
                                                class="w-full text-left px-4 py-2 hover:bg-orange-50 flex items-center justify-between group/item transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <span class="material-symbols-outlined text-gray-400 group-hover/item:text-orange-500 text-sm" x-text="amenity.icon"></span>
                                                <span class="text-sm text-gray-900" x-text="amenity.name"></span>
                                            </div>
                                            <span class="material-symbols-outlined text-orange-500 text-sm opacity-0 group-hover/item:opacity-100">add_circle</span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Selected Amenities Container -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 min-h-[120px]">
                                <p class="text-xs text-gray-500 mb-3">Selected amenities (drag to reorder if needed)</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="amenityName in $wire.amenities" :key="amenityName">
                                        <template x-if="amenityName && amenityName.trim()">
                                            <div class="inline-flex items-center bg-white border border-gray-200 rounded px-3 py-1.5 shadow-sm group hover:border-orange-300 transition-colors">
                                                <span class="material-symbols-outlined text-orange-500 text-sm mr-2"
                                                      x-text="$wire.availableAmenities.find(a => a.name === amenityName)?.icon || 'check'"></span>
                                                <span class="text-sm text-gray-900 font-medium mr-2" x-text="amenityName"></span>
                                                <button @click="$wire.removeAmenity(amenityName)"
                                                        class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none">
                                                    <span class="material-symbols-outlined text-sm">close</span>
                                                </button>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="$wire.amenities.filter(a => a && a.trim()).length === 0">
                                        <span class="text-sm text-gray-400 italic">No amenities selected</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Custom Amenities (for backward compatibility) -->
                            <div class="space-y-2" x-show="$wire.amenities.filter(a => !a || !a.trim()).length > 0">
                                <div class="text-xs text-gray-500 mb-2">Custom amenities:</div>
                                <template x-for="(amenity, index) in $wire.amenities" :key="index">
                                    <div x-show="!amenity || !amenity.trim()" class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                                        <span class="text-sm text-gray-700" x-text="amenity"></span>
                                        <button @click="$wire.removeAmenity(index)"
                                                class="text-red-500 hover:text-red-700 transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Image Tab -->
                    <div x-show="$wire.currentTab === 'image'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-4">

                        <!-- Section Header -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-700">Property Images</h3>
                            <span class="text-xs text-gray-400">First image will be primary</span>
                        </div>

                        <!-- Drag & Drop Upload Area -->
                        <div x-data="{
                                isDragging: false,
                                handleDrop(event) {
                                    event.preventDefault();
                                    this.isDragging = false;
                                    
                                    const files = Array.from(event.dataTransfer.files).filter(file =>
                                        file.type.startsWith('image/')
                                    );
                                    
                                    if (files.length > 0) {
                                        const dt = new DataTransfer();
                                        files.forEach(file => dt.items.add(file));
                                        
                                        const input = document.getElementById('edit-property-images');
                                        input.files = dt.files;
                                        input.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                },
                                handleDragOver(event) {
                                    event.preventDefault();
                                },
                                handleDragEnter(event) {
                                    event.preventDefault();
                                    this.isDragging = true;
                                },
                                handleDragLeave(event) {
                                    event.preventDefault();
                                    this.isDragging = false;
                                }
                            }"
                            @drop="handleDrop($event)"
                            @dragover="handleDragOver($event)"
                            @dragenter="handleDragEnter($event)"
                            @dragleave="handleDragLeave($event)"
                            :class="isDragging ? 'border-blue-400 bg-blue-100' : 'border-blue-200 bg-blue-50/50'"
                            class="border-2 border-dashed rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-colors">
                            <div class="size-10 rounded-full bg-white text-royal-blue shadow-sm flex items-center justify-center mb-2">
                                <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                            </div>
                            <p class="text-royal-blue font-medium text-sm">Drag photos here or <label for="edit-property-images" class="underline decoration-amber-500 decoration-2 underline-offset-2 cursor-pointer">Browse</label></p>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP • Max 10MB • 1920x1080px</p>
                            <input wire:model="newImageFiles"
                                    id="edit-property-images"
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    class="hidden">
                        </div>

                        <!-- Sortable Image Grid -->
                        <div x-data="{
                                sortable: null,
                                init() {
                                    this.initSortable();
                                    this.$watch('$wire.allImages', () => {
                                        this.$nextTick(() => this.initSortable());
                                    });
                                },
                                initSortable() {
                                    if (this.sortable) {
                                        this.sortable.destroy();
                                    }
                                    this.sortable = new Sortable(this.$el, {
                                        animation: 150,
                                        ghostClass: 'sortable-ghost',
                                        chosenClass: 'sortable-chosen',
                                        dragClass: 'sortable-drag',
                                        filter: '.ignore-drag',
                                        onEnd: (evt) => {
                                            const newOrder = [];
                                            this.$el.querySelectorAll('[data-image-uuid]').forEach(el => {
                                                newOrder.push(parseInt(el.dataset.imageUuid));
                                            });
                                            @this.reorderImages(newOrder);
                                        }
                                    });
                                }
                            }"
                            class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">

                            {{-- All Images (Combined - Existing + New) --}}
                            @foreach($allImages as $index => $imageItem)
                                <div class="relative group rounded-lg overflow-hidden cursor-grab active:cursor-grabbing touch-none
                                            {{ $index === 0 ? 'ring-2 ring-royal-blue' : 'bg-gray-100' }}"
                                     data-image-uuid="{{ $index }}"
                                     x-data="{ 
                                         confirmDelete() {
                                             if(confirm('Delete this image?')) {
                                                 $wire.removeImage({{ $index }});
                                             }
                                         }
                                     }">
                                    
                                    <!-- Image Container - 4:5 Ratio with object-cover -->
                                    <div class="aspect-[4/5] w-full relative">
                                        @if($imageItem['type'] === 'existing')
                                            <img src="{{ $imageItem['data']['url'] ?? asset('storage/' . $imageItem['data']['path']) }}"
                                                 alt="Property Image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ $imageItem['data']['temporary_url'] }}"
                                                 alt="New Image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        @endif
                                        
                                        <!-- Primary Badge (only on first image) -->
                                        @if($index === 0)
                                            <div class="absolute top-2 left-2 bg-royal-blue text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm z-10">
                                                Primary
                                            </div>
                                        @endif

                                        <!-- New Image Badge (for newly uploaded images) -->
                                        @if($imageItem['type'] === 'new')
                                            <div class="absolute top-2 left-2 {{ $index === 0 ? 'bg-royal-blue' : 'bg-green-500' }} text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm z-10">
                                                {{ $index === 0 ? 'Primary' : 'New' }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Delete Button - Always visible on mobile, hover on desktop -->
                                    <button type="button"
                                            class="ignore-drag absolute top-2 right-2 bg-white/90 p-1.5 rounded-full hover:text-red-600 text-gray-500 shadow-sm z-20
                                                   opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all duration-200 cursor-pointer"
                                            title="Delete image"
                                            @click="confirmDelete()">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>

                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none"></div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Empty State -->
                        @if(empty($allImages))
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-5xl text-gray-300">photo_library</span>
                                <p class="text-gray-500 mt-2 text-sm">No images uploaded yet</p>
                                <p class="text-xs text-gray-400">Drag and drop or browse to add images</p>
                            </div>
                        @endif

                        <!-- Image Order Info -->
                        @if(count($allImages) > 1)
                            <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Drag images to reorder. The first image will be the primary/cover image.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Location Tab -->
                    <div x-show="$wire.currentTab === 'location'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-xs font-medium text-gray-500">Address</label>
                                <input wire:model.live="address" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="Full address">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Latitude</label>
                                <input wire:model.live="latitude" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="40.7128">
                                @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Longitude</label>
                                <input wire:model.live="longitude" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="-74.0060">
                                @error('longitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <div class="flex items-end gap-3">
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-500">Maps Embed URL</label>
                                        <input wire:model.live="maps_embed_url" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="https://maps.google.com/...">
                                        @error('maps_embed_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <button wire:click="loadMap"
                                            type="button"
                                            class="px-4 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors font-medium text-sm whitespace-nowrap">
                                        Load Map
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Map Preview -->
                        <div x-show="$wire.maps_embed_url" class="relative h-64 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden">
                            <iframe :src="$wire.maps_embed_url"
                                    class="w-full h-full border-0"
                                    allowfullscreen
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                    <!-- Private Tab -->
                    <div x-show="$wire.currentTab === 'private'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-4"
                         class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Owner Name</label>
                                <input wire:model.live="owner_name"
                                       type="text"
                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                                       placeholder="Enter owner name">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Owner Phone</label>
                                <input wire:model.live="owner_phone"
                                       type="text"
                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                                       placeholder="Enter owner phone">
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-xs font-medium text-gray-500">Net Price (Private)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400">₹</span>
                                    <input wire:model.live="net_price"
                                           x-data="{ formatPrice(event) { event.target.value = event.target.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ','); } }"
                                           @input="formatPrice($event)"
                                           type="text"
                                           class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none font-mono"
                                           placeholder="420,000">
                                </div>
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-xs font-medium text-gray-500">Private Notes</label>
                                <textarea wire:model.live="private_notes"
                                          rows="4"
                                          class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none resize-none"
                                          placeholder="Add internal notes here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 pt-2 border-t border-gray-50 flex items-center justify-end gap-3 bg-white">
                <button wire:click="closeModal"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                    Discard Changes
                </button>
                <button wire:click="saveChanges"
                        x-bind:disabled="$wire.saving"
                        class="px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-royal-blue hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!$wire.saving">Update Listing</span>
                    <span x-show="$wire.saving" class="flex items-center gap-2">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- SortableJS Styles for Image Grid -->
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background-color: #dbeafe !important;
        }
        .sortable-chosen {
            box-shadow: 0 0 0 2px #3b82f6;
        }
        .sortable-drag {
            opacity: 0.5;
        }
    </style>
</div>
