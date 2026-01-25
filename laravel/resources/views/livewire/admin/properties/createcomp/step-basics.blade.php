<!-- Step 1: Basics -->
<div class="p-6 md:p-8 space-y-6" @if($currentStep !== 0) style="display: none;" @endif>
    <div class="space-y-4">
        <label class="block text-sm font-bold text-gray-700">Property Title</label>
        <input wire:model="title"
               @class([
                   'w-full rounded-lg shadow-sm py-3 px-4 text-sm transition-all',
                   'border-gray-300 focus:border-amber-500 focus:ring-amber-500' => !$errors->has('title'),
                   'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('title')
               ])
               placeholder="e.g., Luxury 3BHK in Downtown"
               type="text"
               required>
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-4">
        <label class="block text-sm font-bold text-gray-700">Property Type</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($propertyTypes as $type)
                <div wire:click="setPropertyType('{{ $type['value'] }}')"
                     class="border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all cursor-pointer {{ $type['value'] === $this->type ? 'border-royal-blue bg-blue-50/50 text-royal-blue' : 'border-gray-200 bg-white text-gray-500 hover:border-royal-blue hover:text-royal-blue' }}">
                    <input type="radio"
                           name="property_type"
                           value="{{ $type['value'] }}"
                           class="sr-only"
                           {{ $type['value'] === $this->type ? 'checked' : '' }}>
                    <span class="material-symbols-outlined">{{ $type['icon'] }}</span>
                    <span class="font-bold text-sm">{{ $type['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-700">Price</label>
            <div class="relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">₹</span>
                </div>
                <input wire:model="price"
                       @class([
                           'block w-full rounded-lg pl-7 py-3 pr-12 sm:text-sm',
                           'border-gray-300 focus:border-amber-500 focus:ring-amber-500' => !$errors->has('price'),
                           'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('price')
                       ])
                       placeholder="0.00"
                       type="number"
                       step="0.01">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="text-gray-500 sm:text-sm">INR</span>
                </div>
            </div>
        </div>
        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-700">Area Size</label>
            <div class="relative rounded-md shadow-sm">
                <input wire:model="area"
                       @class([
                           'block w-full rounded-lg py-3 px-4 pr-12 sm:text-sm',
                           'border-gray-300 focus:border-amber-500 focus:ring-amber-500' => !$errors->has('area'),
                           'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('area')
                       ])
                       placeholder="Total area"
                       type="number">
                @error('area')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="text-gray-500 sm:text-sm">sqft</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50/50">
            <span class="text-sm font-medium text-gray-700">Bedrooms</span>
            <div class="flex items-center gap-3">
                <button wire:click="decrementBedrooms"
                        type="button"
                        class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                    -
                </button>
                <input wire:model="bedrooms"
                       class="w-12 text-center border-none bg-transparent font-bold text-lg p-0 focus:ring-0 text-slate-900"
                       readonly
                       type="text">
                <button wire:click="incrementBedrooms"
                        type="button"
                        class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                    +
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50/50">
            <span class="text-sm font-medium text-gray-700">Bathrooms</span>
            <div class="flex items-center gap-3">
                <button wire:click="decrementBathrooms"
                        type="button"
                        class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                    -
                </button>
                <input wire:model="bathrooms"
                       class="w-12 text-center border-none bg-transparent font-bold text-lg p-0 focus:ring-0 text-slate-900"
                       readonly
                       type="text">
                <button wire:click="incrementBathrooms"
                        type="button"
                        class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                    +
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-bold text-gray-700">Description</label>
        <textarea wire:model="description"
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 px-4 text-sm"
                  placeholder="Describe the key features of the property..."
                  rows="4"></textarea>
    </div>

    <!-- Advanced Amenities Section -->
    <div class="space-y-3">
        <div class="flex justify-between items-end">
            <label class="block text-sm font-medium text-gray-700">Amenities</label>
            @if(count(array_filter($amenities, fn($a) => $a && trim($a))) > 0)
                <button wire:click="$set('amenities', [])"
                        type="button"
                        class="text-xs text-orange-600 font-medium hover:text-orange-700 transition-colors">
                    Clear All
                </button>
            @endif
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
                        type="button"
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

                @foreach($availableAmenities as $amenity)
                    @if(!in_array($amenity['name'], $amenities) && (empty($amenitiesSearch) || stripos($amenity['name'], $amenitiesSearch) !== false))
                        <button wire:click="addAmenity({{ json_encode($amenity['name']) }})"
                                type="button"
                                x-on:click="searchOpen = false; $wire.set('amenitiesSearch', '')"
                                class="w-full text-left px-4 py-2 hover:bg-orange-50 flex items-center justify-between group/item transition-colors">
                            <div class="flex items-center space-x-3">
                                <span class="material-symbols-outlined text-gray-400 group-hover/item:text-orange-500 text-sm">{{ $amenity['icon'] }}</span>
                                <span class="text-sm text-gray-900">{{ $amenity['name'] }}</span>
                            </div>
                            <span class="material-symbols-outlined text-orange-500 text-sm opacity-0 group-hover/item:opacity-100">add_circle</span>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Selected Amenities Container -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 min-h-[120px]">
            <p class="text-xs text-gray-500 mb-3">Selected amenities (drag to reorder if needed)</p>
            <div class="flex flex-wrap gap-2">
                @forelse($amenities as $amenityName)
                    @if($amenityName && trim($amenityName))
                        <div class="inline-flex items-center bg-white border border-gray-200 rounded px-3 py-1.5 shadow-sm group hover:border-orange-300 transition-colors">
                            @php
                                $amenityData = collect($availableAmenities)->firstWhere('name', $amenityName);
                                $icon = $amenityData ? $amenityData['icon'] : 'check';
                            @endphp
                            <span class="material-symbols-outlined text-orange-500 text-sm mr-2">{{ $icon }}</span>
                            <span class="text-sm text-gray-900 font-medium mr-2">{{ $amenityName }}</span>
                            <button wire:click="removeAmenity({{ json_encode($amenityName) }})"
                                    type="button"
                                    class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                    @endif
                @empty
                    <span class="text-sm text-gray-400 italic">No amenities selected</span>
                @endforelse
            </div>
        </div>

        <!-- Custom Amenities (for backward compatibility) -->
        @php
            $emptyAmenities = array_filter($amenities, fn($a) => !$a || !trim($a));
        @endphp
        @if(count($emptyAmenities) > 0)
            <div class="space-y-2">
                <div class="text-xs text-gray-500 mb-2">Custom amenities:</div>
                @foreach($amenities as $index => $amenity)
                    @if(!$amenity || !trim($amenity))
                        <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                            <span class="text-sm text-gray-700">{{ $amenity }}</span>
                            <button wire:click="removeAmenity({{ $index }})"
                                    type="button"
                                    class="text-red-500 hover:text-red-700 transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>