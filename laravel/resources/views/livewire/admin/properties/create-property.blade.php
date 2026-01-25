<div class="max-w-4xl mx-auto flex flex-col gap-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-slate-900 text-2xl md:text-3xl font-black leading-tight tracking-tight">Add New Property</h1>
        <button wire:click="showExitModal" class="text-sm font-medium text-gray-500 hover:text-royal-blue underline decoration-1 underline-offset-2">
            Cancel & Exit
        </button>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative">
        <!-- Step Indicator -->
        <div class="bg-white border-b border-gray-100 px-6 py-4">
            <!-- Mobile Step Indicator -->
            <div class="md:hidden flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-royal-blue">Step <span>{{ $currentStep + 1 }}</span> of 4</span>
                <span class="text-xs text-gray-400">{{ $stepTitles[$currentStep] }}</span>
            </div>
            <div class="md:hidden w-full bg-gray-200 rounded-full h-1.5 mb-2">
                <div class="bg-royal-blue h-1.5 rounded-full transition-all duration-300"
                     style="width: {{ (($currentStep + 1) / 4) * 100 }}%"></div>
            </div>

            <!-- Desktop Step Indicator -->
            <div class="hidden md:flex items-center justify-between w-full relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -z-10 transform -translate-y-1/2"></div>

                @foreach($steps as $index => $step)
                    <div class="flex flex-col items-center gap-2 bg-white px-2 z-10">
                        <div @class([
                            'flex items-center justify-center size-8 rounded-full ring-4 ring-white shadow-sm',
                            'bg-royal-blue text-white' => $index <= $currentStep,
                            'bg-white border-2 border-gray-300 text-gray-400' => $index > $currentStep,
                        ])>
                            @if($index < $currentStep)
                                <span class="material-symbols-outlined text-sm font-bold">check</span>
                            @else
                                <span class="text-sm font-medium">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <span @class([
                            'text-xs font-bold uppercase tracking-wider',
                            'text-royal-blue' => $index <= $currentStep,
                            'text-gray-400' => $index > $currentStep
                        ])>
                            {{ $stepTitles[$index] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form -->
        <form>
            @csrf

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
                            <label class="cursor-pointer group">
                                <input wire:model="type"
                                       value="{{ $type['value'] }}"
                                       class="peer sr-only"
                                       name="property_type"
                                       type="radio">
                                <div @class([
                                    'border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all',
                                    'border-royal-blue bg-blue-50/50 text-royal-blue' => $type['value'] === $this->type,
                                    'border-gray-200 bg-white text-gray-500 hover:border-royal-blue hover:text-royal-blue peer-checked:border-royal-blue peer-checked:text-royal-blue peer-checked:bg-blue-50' => $type['value'] !== $this->type
                                ])>
                                    <span class="material-symbols-outlined">{{ $type['icon'] }}</span>
                                    <span class="font-bold text-sm">{{ $type['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Price</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
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
                                <span class="text-gray-500 sm:text-sm">USD</span>
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

            <!-- Step 3: Media Gallery -->
            <div class="p-6 md:p-8 space-y-6" @if($currentStep !== 2) style="display: none;" @endif>
                <div class="flex items-center gap-2 mb-2">
                    <div class="size-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">3</div>
                    <h3 class="text-lg font-bold text-slate-900">Media Gallery</h3>
                </div>

                <div class="border-2 border-dashed border-blue-200 bg-blue-50/50 rounded-xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-blue-50 transition-colors"
                     onclick="document.getElementById('images').click()">
                    <div class="size-12 rounded-full bg-white text-royal-blue shadow-sm flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                    </div>
                    <p class="text-royal-blue font-medium">
                        Drag photos here or <span class="underline decoration-amber-500 decoration-2 underline-offset-2">Browse</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, WEBP</p>
                    <input wire:model="images"
                           id="images"
                           type="file"
                           multiple
                           accept="image/*"
                           class="hidden">
                </div>

                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($images as $index => $image)
                        <div class="relative aspect-video rounded-lg overflow-hidden border border-gray-200 group">
                            <img src="{{ $image->temporaryUrl() }}"
                                 alt="Property {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                            @if($index === 0)
                                <div class="absolute top-2 left-2 bg-royal-blue text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                    Cover Image
                                </div>
                            @endif
                            <button wire:click="removeImage({{ $index }})"
                                    type="button"
                                    class="absolute top-2 right-2 bg-white/90 p-1 rounded hover:text-red-600 text-gray-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900">Watermark Photos</span>
                        <span class="text-xs text-gray-500">Apply Dealer Logo Watermark automatically?</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input wire:model="watermark"
                               class="sr-only peer"
                               type="checkbox">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>
            </div>

            <!-- Step 4: Private Vault -->
            <div class="p-6 md:p-8 space-y-6" @if($currentStep !== 3) style="display: none;" @endif>
                <div class="flex items-center gap-2 mb-2">
                    <div class="size-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">lock</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Private Vault <span class="text-sm font-normal text-gray-500 ml-2">(Visible only to you)</span></h3>
                </div>

                <div class="bg-amber-50 border border-amber-100 rounded-xl p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Owner Name</label>
                            <input wire:model="ownerName"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm"
                                   type="text">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Owner Phone</label>
                            <input wire:model="ownerPhone"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm"
                                   type="text">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Net Price (Bottom Line)</label>
                        <div class="relative rounded-md shadow-sm max-w-xs">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input wire:model="netPrice"
                                   class="block w-full rounded-lg border-gray-300 pl-7 py-2.5 pr-12 focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                   placeholder="0.00"
                                   type="number"
                                   step="0.01">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Private Notes</label>
                        <textarea wire:model="privateNotes"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm bg-white"
                                  placeholder="Negotiation details, access codes, etc."
                                  rows="3"></textarea>
                    </div>
                </div>
            </div>
        </form>

        <!-- Sticky Bottom Navigation -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between z-20">
            <button wire:click="saveAsDraft"
                    @if($isSavingDraft) disabled @endif
                    type="button"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50">
                @if($isSavingDraft)
                    <span class="flex items-center gap-2">
                        <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-500"></span>
                        Saving...
                    </span>
                @else
                    Save as Draft
                @endif
            </button>
            <div class="flex items-center gap-3">
                <button wire:click="previousStep"
                        @if($currentStep <= 0) disabled @endif
                        type="button"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-blue transition-all disabled:opacity-50">
                    Back
                </button>
                @if($currentStep < 3)
                    <button wire:click="nextStep"
                            @if($isProcessingStep) disabled @endif
                            type="button"
                            class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        @if($isProcessingStep)
                            <span class="flex items-center gap-2">
                                <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                                Processing...
                            </span>
                        @else
                            Next
                        @endif
                    </button>
                @else
                    <button wire:click="publishLive"
                            @if($isPublishing) disabled @endif
                            type="button"
                            class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        @if($isPublishing)
                            <span class="flex items-center gap-2">
                                <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                                Publishing...
                            </span>
                        @else
                            Publish Live
                        @endif
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    @if($showExitModal)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             style="background: rgba(0, 0, 0, 0.5);">

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl transform transition-all">

                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-amber-100 rounded-full">
                            <span class="material-symbols-outlined text-amber-600">warning</span>
                        </div>

                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-bold text-gray-900">Exit Property Creation?</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Your progress will be saved as a draft. You can continue later.
                            </p>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button wire:click="handleExit"
                                    type="button"
                                    class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium transition-colors">
                                Save Draft & Exit
                            </button>
                            <button wire:click="hideExitModal"
                                    type="button"
                                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                                Cancel
                            </button>
                        </div>

                        <button onclick="window.location.href = '{{ route('admin.inventory') }}'"
                                type="button"
                                class="w-full mt-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                            Don't Save & Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>