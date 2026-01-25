<!-- Step 2: Location -->
<div class="p-6 md:p-8 space-y-6" @if($currentStep !== 1) style="display: none;" @endif>
    <div class="flex items-center gap-2 mb-2">
        <div class="size-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">2</div>
        <h3 class="text-lg font-bold text-slate-900">Location Details</h3>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-bold text-gray-700">Address <span class="text-xs text-gray-500">(Optional)</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </div>
            <input wire:model="address"
                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 px-4 text-sm"
                   placeholder="Enter property address..."
                   type="text">
        </div>
    </div>

    <!-- Lat/Lon fields + Reload button in same row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-500 uppercase">Latitude (Optional)</label>
            <input wire:model="latitude"
                   @class([
                       'w-full rounded-lg py-2 px-3 text-sm',
                       'border-gray-300 focus:border-amber-500 focus:ring-amber-500' => !$errors->has('latitude'),
                       'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('latitude')
                   ])
                   placeholder="e.g. 25.2048"
                   type="text">
            @error('latitude')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-500 uppercase">Longitude (Optional)</label>
            <input wire:model="longitude"
                   @class([
                       'w-full rounded-lg py-2 px-3 text-sm',
                       'border-gray-300 focus:border-amber-500 focus:ring-amber-500' => !$errors->has('longitude'),
                       'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('longitude')
                   ])
                   placeholder="e.g. 55.2708"
                   type="text">
            @error('longitude')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-500 uppercase invisible">Reload Map</label>
            <button wire:click="loadMap"
                    @if($isLoadingMap) disabled @endif
                    type="button"
                    class="w-full px-3 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors font-medium flex items-center justify-center gap-2 h-10 disabled:opacity-50">
                @if($isLoadingMap)
                    <span class="flex items-center gap-2">
                        <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                        Loading...
                    </span>
                @else
                    {{ $mapsEmbedUrl ? 'Reload' : 'Load' }}
                @endif
            </button>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <!-- Google Maps Embed Area -->
        @if($mapsEmbedUrl)
            <div class="relative h-64 bg-gray-100 rounded-lg border border-gray-300 overflow-hidden">
                <iframe src="{{ $mapsEmbedUrl }}"
                        class="w-full h-full border-0"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        @endif

        <!-- Loading State -->
        @if($isLoadingMap)
            <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg border border-gray-300">
                <div class="flex items-center gap-3">
                    <span class="animate-spin rounded-full h-6 w-6 border-b-2 border-royal-blue"></span>
                    <span class="text-gray-600 font-medium">Loading map...</span>
                </div>
            </div>
        @endif
    </div>
</div>