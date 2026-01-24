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
                <div class="px-6 pb-4">
                    <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                        <button wire:click="setTab('overview')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all {{ $currentTab === 'overview' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Overview
                        </button>
                        <button wire:click="setTab('basic-info')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all {{ $currentTab === 'basic-info' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Basic Info
                        </button>
                        <button wire:click="setTab('location')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all {{ $currentTab === 'location' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Location
                        </button>
                        <button wire:click="setTab('details')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all {{ $currentTab === 'details' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
                            Details
                        </button>
                        <button wire:click="setTab('private')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all {{ $currentTab === 'private' ? 'bg-white text-royal-blue shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
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
                                <select wire:model.live="property_type" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none">
                                    <option value="apartment">Apartment</option>
                                    <option value="villa">Villa</option>
                                    <option value="plot">Plot</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="office">Office</option>
                                </select>
                                @error('property_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500">Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400">$</span>
                                    <input wire:model.live="price"
                                           x-data="{ formatPrice(event) { event.target.value = event.target.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ','); } }"
                                           @input="formatPrice($event)"
                                           type="text"
                                           class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none font-mono"
                                           placeholder="420,000">
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
                                <label class="text-xs font-medium text-gray-500">Maps Embed URL</label>
                                <input wire:model.live="maps_embed_url" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="https://maps.google.com/...">
                                @error('maps_embed_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

                        <!-- Amenities Section -->
                        <div class="space-y-3">
                            <label class="text-xs font-medium text-gray-500">Amenities</label>
                            <div class="space-y-2">
                                @foreach($amenities as $index => $amenity)
                                    <div class="flex gap-2">
                                        <input wire:model.live="amenities.{{ $index }}" type="text" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none" placeholder="Amenity">
                                        <button wire:click="removeAmenity({{ $index }})" class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-colors">Remove</button>
                                    </div>
                                @endforeach
                                <button wire:click="addAmenity" class="w-full py-2 px-4 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600 transition-colors">Add Amenity</button>
                            </div>
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
                                    <span class="absolute left-3 top-2 text-gray-400">$</span>
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

                    <!-- Modify All Details Button -->
                    <div class="pt-6 border-t border-gray-100">
                        <button wire:click="editAllDetails"
                                class="w-full py-2.5 px-4 rounded-lg border border-royal-blue/30 text-royal-blue hover:bg-blue-50 font-medium text-sm flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            Modify All Details & Media
                        </button>
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
</div>