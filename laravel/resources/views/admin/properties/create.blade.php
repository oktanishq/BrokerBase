@extends('layouts.admin')

@section('title', 'Add New Property - BrokerBase')

@section('head')
<style>
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
</style>
@endsection

@section('header-content')
<nav aria-label="Breadcrumb" class="hidden sm:flex">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-royal-blue transition-colors">Home</a>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <a href="{{ route('admin.inventory') }}" class="ml-1 hover:text-royal-blue transition-colors">Properties</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <span class="ml-1 font-medium text-gray-700">Add New</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div x-data="propertyCreationForm()" class="max-w-4xl mx-auto flex flex-col gap-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-slate-900 text-2xl md:text-3xl font-black leading-tight tracking-tight">Add New Property</h1>
        <button @click="showExitModal = true" class="text-sm font-medium text-gray-500 hover:text-royal-blue underline decoration-1 underline-offset-2">
            Cancel & Exit
        </button>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative">
        <!-- Step Indicator -->
        <div class="bg-white border-b border-gray-100 px-6 py-4">
            <!-- Mobile Step Indicator -->
            <div class="md:hidden flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-royal-blue">Step <span x-text="currentStep + 1"></span> of 4</span>
                <span class="text-xs text-gray-400" x-text="stepTitles[currentStep]"></span>
            </div>
            <div class="md:hidden w-full bg-gray-200 rounded-full h-1.5 mb-2">
                <div class="bg-royal-blue h-1.5 rounded-full transition-all duration-300" 
                     :style="`width: ${((currentStep + 1) / 4) * 100}%`"></div>
            </div>

            <!-- Desktop Step Indicator -->
            <div class="hidden md:flex items-center justify-between w-full relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -z-10 transform -translate-y-1/2"></div>
                
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex flex-col items-center gap-2 bg-white px-2 z-10">
                        <div :class="[
                            'flex items-center justify-center size-8 rounded-full ring-4 ring-white shadow-sm',
                            index < currentStep ? 'bg-royal-blue text-white' : 
                            index === currentStep ? 'bg-royal-blue text-white' : 
                            'bg-white border-2 border-gray-300 text-gray-400'
                        ]">
                            <span x-show="index < currentStep" class="material-symbols-outlined text-sm font-bold">check</span>
                            <span x-show="index >= currentStep" class="text-sm font-medium" x-text="index + 1"></span>
                        </div>
                        <span :class="[
                            'text-xs font-bold uppercase tracking-wider',
                            index <= currentStep ? 'text-royal-blue' : 'text-gray-400'
                        ]" x-text="stepTitles[index]"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="divide-y divide-gray-100">
            @csrf
            
            <!-- Step 1: Basics -->
            <div x-show="currentStep === 0" class="p-6 md:p-8 space-y-6">
                <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700">Property Title</label>
                    <input x-model="formData.title" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 px-4 text-sm transition-all" 
                           placeholder="e.g., Luxury 3BHK in Downtown" 
                           type="text"
                           required>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700">Property Type</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <template x-for="type in propertyTypes" :key="type.value">
                            <label class="cursor-pointer group">
                                <input x-model="formData.type" 
                                       :value="type.value" 
                                       class="peer sr-only" 
                                       name="property_type" 
                                       type="radio">
                                <div :class="[
                                    'border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all',
                                    formData.type === type.value 
                                        ? 'border-royal-blue bg-blue-50/50 text-royal-blue' 
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-royal-blue hover:text-royal-blue peer-checked:border-royal-blue peer-checked:text-royal-blue peer-checked:bg-blue-50'
                                ]">
                                    <span class="material-symbols-outlined" x-text="type.icon"></span>
                                    <span class="font-bold text-sm" x-text="type.label"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Price</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input x-model="formData.price" 
                                   class="block w-full rounded-lg border-gray-300 pl-7 py-3 pr-12 focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                   placeholder="0.00" 
                                   type="number"
                                   step="0.01"
                                   :class="validationErrors.price ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''">
                            <p x-show="validationErrors.price" class="mt-1 text-sm text-red-600" x-text="validationErrors.price"></p>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 sm:text-sm">USD</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Area Size</label>
                        <div class="relative rounded-md shadow-sm">
                            <input x-model="formData.area" 
                                   class="block w-full rounded-lg border-gray-300 py-3 px-4 pr-12 focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                   placeholder="Total area" 
                                   type="number"
                                   :class="validationErrors.area ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''">
                            <p x-show="validationErrors.area" class="mt-1 text-sm text-red-600" x-text="validationErrors.area"></p>
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
                            <button @click="decrementBedrooms()" 
                                    type="button" 
                                    class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                                -
                            </button>
                            <input x-model="formData.bedrooms" 
                                   class="w-12 text-center border-none bg-transparent font-bold text-lg p-0 focus:ring-0 text-slate-900" 
                                   readonly 
                                   type="text" 
                                   value="3">
                            <button @click="incrementBedrooms()" 
                                    type="button" 
                                    class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                                +
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-gray-50/50">
                        <span class="text-sm font-medium text-gray-700">Bathrooms</span>
                        <div class="flex items-center gap-3">
                            <button @click="decrementBathrooms()" 
                                    type="button" 
                                    class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                                -
                            </button>
                            <input x-model="formData.bathrooms" 
                                   class="w-12 text-center border-none bg-transparent font-bold text-lg p-0 focus:ring-0 text-slate-900" 
                                   readonly 
                                   type="text" 
                                   value="2">
                            <button @click="incrementBathrooms()" 
                                    type="button" 
                                    class="size-8 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-gray-600">
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Description</label>
                    <textarea x-model="formData.description" 
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 px-4 text-sm" 
                              placeholder="Describe the key features of the property..." 
                              rows="4"></textarea>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700">Amenities</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="amenity in amenities" :key="amenity">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input x-model="formData.amenities" 
                                       :value="amenity" 
                                       class="h-5 w-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500" 
                                       type="checkbox">
                                <span class="text-sm text-gray-700" x-text="amenity"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Step 2: Location -->
            <div x-show="currentStep === 1" class="p-6 md:p-8 space-y-6">
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
                        <input x-model="formData.address" 
                               class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 px-4 text-sm" 
                               placeholder="Enter property address..." 
                               type="text">
                    </div>
                </div>

                <!-- Lat/Lon fields + Reload button in same row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Latitude (Optional)</label>
                        <input x-model="formData.latitude" 
                               class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-amber-500 focus:ring-amber-500" 
                               placeholder="e.g. 25.2048" 
                               type="text">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Longitude (Optional)</label>
                        <input x-model="formData.longitude" 
                               class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-amber-500 focus:ring-amber-500" 
                               placeholder="e.g. 55.2708" 
                               type="text">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase invisible">Reload Map</label>
                        <button @click="loadMap()" 
                                type="button" 
                                class="w-full px-3 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors font-medium flex items-center justify-center gap-2 h-10">
                            <span class="material-symbols-outlined text-sm">refresh</span>
                            <span class="hidden sm:inline" x-text="formData.mapsEmbedUrl ? 'Reload' : 'Load'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <!-- Google Maps Embed Area -->
                    <div x-show="formData.mapsEmbedUrl" class="relative h-64 bg-gray-100 rounded-lg border border-gray-300 overflow-hidden">
                        <iframe x-ref="mapFrame"
                                :src="formData.mapsEmbedUrl"
                                class="w-full h-full border-0"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    
                    <!-- Loading State -->
                    <div x-show="isLoadingMap" class="flex items-center justify-center h-64 bg-gray-100 rounded-lg border border-gray-300">
                        <div class="flex items-center gap-3">
                            <span class="animate-spin rounded-full h-6 w-6 border-b-2 border-royal-blue"></span>
                            <span class="text-gray-600 font-medium">Loading map...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Media Gallery -->
            <div x-show="currentStep === 2" class="p-6 md:p-8 space-y-6">
                <div class="flex items-center gap-2 mb-2">
                    <div class="size-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">3</div>
                    <h3 class="text-lg font-bold text-slate-900">Media Gallery</h3>
                </div>

                <div @drop="handleDrop($event)" 
                     @dragover.prevent 
                     @dragenter.prevent 
                     class="border-2 border-dashed border-blue-200 bg-blue-50/50 rounded-xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-blue-50 transition-colors">
                    <div class="size-12 rounded-full bg-white text-royal-blue shadow-sm flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                    </div>
                    <p class="text-royal-blue font-medium">Drag photos here or <span @click="$refs.fileInput.click()" class="underline decoration-amber-500 decoration-2 underline-offset-2">Browse</span></p>
                    <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, WEBP</p>
                    <input x-ref="fileInput" 
                           @change="handleFileSelect($event)" 
                           type="file" 
                           multiple 
                           accept="image/*" 
                           class="hidden">
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <template x-for="(image, index) in formData.images" :key="index">
                        <div class="relative aspect-video rounded-lg overflow-hidden border border-gray-200 group">
                            <img :src="image.url" 
                                 :alt="`Property ${index + 1}`" 
                                 class="w-full h-full object-cover">
                            <div x-show="index === 0" class="absolute top-2 left-2 bg-royal-blue text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                Cover Image
                            </div>
                            <button @click="removeImage(index)" 
                                    type="button" 
                                    class="absolute top-2 right-2 bg-white/90 p-1 rounded hover:text-red-600 text-gray-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900">Watermark Photos</span>
                        <span class="text-xs text-gray-500">Apply Dealer Logo Watermark automatically?</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input x-model="formData.watermark" 
                               class="sr-only peer" 
                               type="checkbox" 
                               value="true">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>
            </div>

            <!-- Step 4: Private Vault -->
            <div x-show="currentStep === 3" class="p-6 md:p-8 space-y-6">
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
                            <input x-model="formData.ownerName" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm" 
                                   type="text">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Owner Phone</label>
                            <input x-model="formData.ownerPhone" 
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
                            <input x-model="formData.netPrice" 
                                   class="block w-full rounded-lg border-gray-300 pl-7 py-2.5 pr-12 focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                   placeholder="0.00" 
                                   type="number" 
                                   step="0.01">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Private Notes</label>
                        <textarea x-model="formData.privateNotes" 
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm bg-white" 
                                  placeholder="Negotiation details, access codes, etc." 
                                  rows="3"></textarea>
                    </div>
                </div>
            </div>
        </form>

        <!-- Sticky Bottom Navigation -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between z-20">
                <button @click="saveDraft()" 
                        :disabled="isSubmitting"
                        type="button" 
                        class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50">
                    Save as Draft
                </button>
            <div class="flex items-center gap-3">
                <button @click="previousStep()" 
                        x-show="currentStep > 0" 
                        :disabled="isSubmitting"
                        type="button" 
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-blue transition-all disabled:opacity-50">
                    Back
                </button>
                <button @click="nextStep()" 
                        x-show="currentStep < 3" 
                        :disabled="isSubmitting"
                        type="button" 
                        class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Next</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                        Processing...
                    </span>
                </button>
                <button @click="submitForm()" 
                        x-show="currentStep === 3" 
                        :disabled="isSubmitting"
                        type="button" 
                        class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Publish Live</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div x-show="showExitModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showExitModal = false"></div>
        
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showExitModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md bg-white rounded-xl shadow-xl transform transition-all"
                 style="display: none;">
                
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
                        <button @click="saveDraft(); showExitModal = false; window.location.href = '{{ route('admin.inventory') }}'" 
                                type="button" 
                                class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium transition-colors">
                            Save Draft & Exit
                        </button>
                        <button @click="showExitModal = false" 
                                type="button" 
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                            Cancel
                        </button>
                    </div>
                    
                    <button @click="window.location.href = '{{ route('admin.inventory') }}'" 
                            type="button" 
                            class="w-full mt-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                        Don't Save & Exit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function propertyCreationForm() {
    return {
        currentStep: 0,
        showExitModal: false,
        isSubmitting: false,
        validationErrors: {}, // Store validation errors
        
        steps: ['Basics', 'Location', 'Media', 'Vault'],
        stepTitles: ['Basics', 'Location', 'Media', 'Private Vault'],
        
        propertyTypes: [
            { value: 'apartment', label: 'Apartment', icon: 'apartment' },
            { value: 'villa', label: 'Villa', icon: 'villa' },
            { value: 'plot', label: 'Plot', icon: 'landscape' },
            { value: 'commercial', label: 'Commercial', icon: 'storefront' }
        ],
        
        amenities: ['Swimming Pool', 'Gymnasium', 'Parking', '24/7 Security'],
        
        formData: {
            title: '',
            type: 'apartment',
            price: '',
            area: '',
            bedrooms: 3,
            bathrooms: 2,
            description: '',
            amenities: ['Swimming Pool', 'Parking'],
            address: '',
            latitude: '',
            longitude: '',
            mapsEmbedUrl: '', // Google Maps embed URL
            images: [],
            watermark: true,
            ownerName: '',
            ownerPhone: '',
            netPrice: '',
            privateNotes: ''
        },

        isLoadingMap: false,

        init() {
            // Load draft data from localStorage
            this.loadDraft();
            
            // Auto-save draft every 30 seconds
            setInterval(() => {
                this.autoSaveDraft();
            }, 30000);
            
            // Auto-load map if coordinates are pre-filled
            this.$nextTick(() => {
                if (this.formData.latitude && this.formData.longitude) {
                    this.loadMap();
                }
            });
        },

        // Navigation methods with validation
        nextStep() {
            // Validate current step before proceeding
            if (!this.validateCurrentStep()) {
                return; // Don't proceed if validation fails
            }
            
            if (this.currentStep < 3) {
                this.currentStep++;
                this.saveDraft();
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.saveDraft();
            }
        },

        // Validate current step
        validateCurrentStep() {
            this.validationErrors = {}; // Clear previous errors
            
            switch (this.currentStep) {
                case 0: // Basics step
                    if (!this.formData.title.trim()) {
                        this.validationErrors.title = 'Property title is required';
                        return false;
                    }
                    if (!this.formData.type) {
                        this.validationErrors.type = 'Property type is required';
                        return false;
                    }
                    break;
                    
                case 1: // Location step
                    // Address is optional - no validation needed
                    // Validate format if coordinates provided
                    if (this.formData.latitude && isNaN(parseFloat(this.formData.latitude))) {
                        this.validationErrors.latitude = 'Invalid latitude format';
                        return false;
                    }
                    if (this.formData.longitude && isNaN(parseFloat(this.formData.longitude))) {
                        this.validationErrors.longitude = 'Invalid longitude format';
                        return false;
                    }
                    break;
                    
                case 2: // Media step
                    // Images are optional for now
                    break;
                    
                case 3: // Vault step
                    // Private fields are optional
                    break;
            }
            
            return true;
        },

        // Counter methods
        incrementBedrooms() {
            this.formData.bedrooms++;
            this.saveDraft();
        },

        decrementBedrooms() {
            if (this.formData.bedrooms > 0) {
                this.formData.bedrooms--;
                this.saveDraft();
            }
        },

        incrementBathrooms() {
            this.formData.bathrooms++;
            this.saveDraft();
        },

        decrementBathrooms() {
            if (this.formData.bathrooms > 0) {
                this.formData.bathrooms--;
                this.saveDraft();
            }
        },

        // File handling
        handleDrop(event) {
            event.preventDefault();
            const files = Array.from(event.dataTransfer.files);
            this.processFiles(files);
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.processFiles(files);
            
            // Clear the file input to allow re-uploading the same file
            event.target.value = '';
        },

        processFiles(files) {
            // Limit maximum number of images (e.g., 10 images)
            const maxImages = 10;
            const availableSlots = maxImages - this.formData.images.length;
            
            if (availableSlots <= 0) {
                alert(`Maximum ${maxImages} images allowed`);
                return;
            }
            
            const filesToProcess = files.slice(0, availableSlots);
            
            filesToProcess.forEach(file => {
                if (file.type.startsWith('image/')) {
                    // Check file size (max 10MB)
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    if (file.size > maxSize) {
                        alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                        return;
                    }
                    
                    // Check if file already exists in the current selection
                    const fileExists = this.formData.images.some(img => 
                        img.name === file.name && img.size === file.size
                    );
                    
                    if (!fileExists) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.formData.images.push({
                                file: file,
                                url: e.target.result,
                                name: file.name,
                                size: file.size
                            });
                            this.saveDraft();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        console.log('File already added:', file.name);
                    }
                }
            });
            
            // Show warning if files were truncated
            if (files.length > filesToProcess.length) {
                alert(`Only ${filesToProcess.length} files processed. Maximum ${maxImages} images allowed.`);
            }
        },

        removeImage(index) {
            this.formData.images.splice(index, 1);
            this.saveDraft();
        },

        // Map interaction
        addMapPin(event) {
            // This function is now replaced by loadMap()
            console.log('Map interaction disabled - use Retry to load instead');
        },

        // Load Google Maps embed
        loadMap() {
            // Check if coordinates are provided
            if (!this.formData.latitude || !this.formData.longitude) {
                alert('Please enter latitude and longitude to load the map');
                return;
            }
            
            // Validate coordinates
            const lat = parseFloat(this.formData.latitude);
            const lng = parseFloat(this.formData.longitude);
            
            if (isNaN(lat) || isNaN(lng)) {
                alert('Please enter valid latitude and longitude values');
                return;
            }
            
            // Validate coordinate ranges
            if (lat < -90 || lat > 90) {
                alert('Latitude must be between -90 and 90');
                return;
            }
            
            if (lng < -180 || lng > 180) {
                alert('Longitude must be between -180 and 180');
                return;
            }
            
            // Set loading state
            this.isLoadingMap = true;
            
            // Generate Google Maps embed URL with disabled controls
            const mapsUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed&t=&z=15&ie=UTF8&iwloc=&output=embed&maptype=satellite&source=embed&disableDefaultUI=true&zoomControl=false&mapTypeControl=false&streetViewControl=false&rotateControl=false&fullscreenControl=false&scrollwheel=false`;
            
            // Simulate loading time for better UX
            setTimeout(() => {
                this.formData.mapsEmbedUrl = mapsUrl;
                this.isLoadingMap = false;
                this.saveDraft(); // Save the map URL to draft
                console.log('Map loaded successfully:', mapsUrl);
            }, 1000);
        },

        // Draft saving
        saveDraft() {
            localStorage.setItem('propertyDraft', JSON.stringify(this.formData));
            console.log('Draft saved');
        },

        autoSaveDraft() {
            this.saveDraft();
        },

        loadDraft() {
            const draft = localStorage.getItem('propertyDraft');
            if (draft) {
                try {
                    const draftData = JSON.parse(draft);
                    this.formData = { ...this.formData, ...draftData };
                } catch (e) {
                    console.error('Error loading draft:', e);
                }
            }
        },

        // Form submission
        async submitForm() {
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            
            try {
                // Prepare FormData for multipart file upload
                const formData = new FormData();
                
                // Append text fields
                formData.append('title', this.formData.title);
                formData.append('property_type', this.formData.type);
                formData.append('price', this.formData.price || '');
                formData.append('area_sqft', this.formData.area || '');
                formData.append('bedrooms', this.formData.bedrooms);
                formData.append('bathrooms', this.formData.bathrooms);
                formData.append('description', this.formData.description || '');
                formData.append('amenities', JSON.stringify(this.formData.amenities));
                formData.append('address', this.formData.address || '');
                formData.append('latitude', this.formData.latitude || '');
                formData.append('longitude', this.formData.longitude || '');
                formData.append('maps_embed_url', this.formData.mapsEmbedUrl || '');
                formData.append('owner_name', this.formData.ownerName || '');
                formData.append('owner_phone', this.formData.ownerPhone || '');
                formData.append('net_price', this.formData.netPrice || '');
                formData.append('private_notes', this.formData.privateNotes || '');
                formData.append('watermark_enabled', this.formData.watermark ? '1' : '0');
                formData.append('status', 'draft'); // Save as draft first
                
                // Append image files
                if (this.formData.images && this.formData.images.length > 0) {
                    this.formData.images.forEach((image, index) => {
                        if (image.file) {
                            formData.append('images[]', image.file);
                        }
                    });
                }

                // Submit to API with FormData
                const response = await fetch('/api/admin/properties', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const contentType = response.headers.get('content-type');
                let result;
                
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    // If response is not JSON, get text and try to parse
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Server returned an error. Please check the console for details.');
                }

                if (result.success) {
                    // Success - clear draft and redirect
                    localStorage.removeItem('propertyDraft');
                    alert('Property created successfully!');
                    window.location.href = '/admin/inventory';
                } else {
                    // Handle validation errors
                    this.handleApiErrors(result.errors || {});
                    
                    // Show detailed error message
                    const errorMessage = result.message || 'Failed to save property. Please check the form for errors.';
                    alert('Error: ' + errorMessage);
                    console.error('API Error:', result);
                }
                
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('Network error. Please check your connection and try again.');
            } finally {
                this.isSubmitting = false;
            }
        },

        // Handle API validation errors
        handleApiErrors(errors) {
            // Clear previous errors
            this.validationErrors = {};
            
            // Map API errors to form fields
            const fieldMapping = {
                'title': 'title',
                'property_type': 'type',
                'price': 'price',
                'area_sqft': 'area',
                'address': 'address',
                'latitude': 'latitude',
                'longitude': 'longitude',
                'maps_embed_url': 'mapsEmbedUrl'
            };
            
            Object.keys(errors).forEach(field => {
                const formField = fieldMapping[field] || field;
                this.validationErrors[formField] = errors[field][0];
                console.log(`Validation error for ${field}: ${errors[field][0]}`);
            });
            
            // Scroll to first error field
            const firstErrorField = Object.keys(this.validationErrors)[0];
            if (firstErrorField) {
                const element = document.querySelector(`[x-model*="${firstErrorField}"]`);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    element.focus();
                }
            }
        },
    }
}
</script>
@endsection