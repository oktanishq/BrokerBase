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

    <div class="space-y-4">
        <label class="block text-sm font-bold text-gray-700">Amenities</label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($availableAmenities as $amenity)
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input wire:model="amenities"
                           value="{{ $amenity }}"
                           class="h-5 w-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500"
                           type="checkbox">
                    <span class="text-sm text-gray-700">{{ $amenity }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>