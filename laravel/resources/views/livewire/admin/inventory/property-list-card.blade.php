<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex md:flex-row w-full {{ $property->status === 'sold' ? 'opacity-90 hover:opacity-100' : '' }}">

    <!-- Property Image -->
    <div class="relative w-24 h-24 md:w-64 lg:w-72 shrink-0 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500 {{ $property->status === 'sold' ? 'grayscale contrast-125' : '' }}"
             style="background-image: url('{{ $property->image ?? '/images/placeholder-property.jpg' }}')">
        </div>

        <!-- View Count -->
        <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white px-2 py-0.5 rounded flex items-center gap-1 text-xs font-medium">
            <span class="material-symbols-outlined text-[14px]">{{ $property->views > 0 ? 'visibility' : 'visibility_off' }}</span>
            <span>{{ $property->views }}</span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-3 md:p-5 flex flex-col flex-1 justify-between gap-2">
        <div class="flex flex-col gap-1">
            <!-- Status and Date -->
            <div class="flex items-center justify-between">
                <span @class([
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold',
                    'bg-green-100 text-green-700 border-green-200' => $property->status === 'available',
                    'bg-red-100 text-red-700 border-red-200' => $property->status === 'sold',
                    'bg-gray-200 text-gray-600 border-gray-300' => !in_array($property->status, ['available', 'sold']),
                ])>
                    {{ ucfirst($property->status) }}
                </span>
                <span class="text-sm text-gray-400 hidden md:block">
                    @if($property->status === 'sold')
                        Sold recently
                    @elseif($property->status === 'draft')
                        Last edited recently
                    @else
                        Added recently
                    @endif
                </span>
            </div>

            <!-- Title and Price -->
            <div class="flex flex-row items-center justify-between gap-2 mt-1 min-w-0">
                <div class="w-0 flex-grow min-w-0">
                    <h3 class="text-xl font-bold text-slate-900 leading-tight truncate"
                        title="{{ $property->title }}">
                        {{ $property->title }}
                    </h3>
                </div>
                <h3 @class([
                    'text-xl font-bold shrink-0',
                    'text-amber-600' => $property->status === 'available',
                    'text-gray-400 line-through decoration-red-500 decoration-2' => $property->status === 'sold',
                    'text-gray-400 italic' => !in_array($property->status, ['available', 'sold']),
                ])>
                    ₹{{ number_format($property->price, 0) }}
                </h3>
            </div>

            <!-- Location -->
            <div class="flex items-center gap-1 text-gray-500 text-sm min-w-0">
                <span class="material-symbols-outlined text-[18px] text-red-500 shrink-0">location_on</span>
                <span class="w-0 flex-grow min-w-0 truncate">{{ $property->location ?? 'Location not specified' }}</span>
            </div>
        </div>

        <!-- Property Specs -->
        <div class="hidden md:flex items-center flex-wrap gap-4 mt-2 pt-3 border-t border-gray-50">
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">
                    @if($property->type === 'Office' || $property->type === 'Commercial')
                        desk
                    @else
                        bed
                    @endif
                </span>
                <span>
                    @if($property->type === 'Office' || $property->type === 'Commercial')
                        {{ $property->type }}
                    @else
                        {{ $property->bedrooms ?? 3 }} Beds
                    @endif
                </span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">bathtub</span>
                <span>{{ $property->bathrooms ?? 2 }} Baths</span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">square_foot</span>
                <span>{{ number_format($property->sqft ?? 1500) }} sqft</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col items-center justify-center gap-2 px-3 lg:px-6 border-l border-gray-100 shrink-0 bg-gray-50">
        <!-- Share Button -->
        <x-admin.inventory.property-share-button
            :property="$property"
            class="flex items-center justify-center size-10 rounded-full hover:bg-green-50"
            title="Copy WhatsApp share message" />

        <!-- Edit Button -->
        <button wire:click="openEditModal" class="flex items-center justify-center size-10 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Property">
            <span class="material-symbols-outlined text-[22px]">edit</span>
        </button>

        <!-- Delete Button -->
        <button wire:click="openDeleteModal" class="flex items-center justify-center size-10 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Property">
            <span class="material-symbols-outlined text-[22px]">delete</span>
        </button>
    </div>
</div>