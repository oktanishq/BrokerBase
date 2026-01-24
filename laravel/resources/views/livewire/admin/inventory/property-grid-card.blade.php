<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col {{ $property->status === 'sold' ? 'opacity-90 hover:opacity-100' : '' }}">
    <!-- Property Image -->
    <div class="relative aspect-video bg-gray-200 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500 {{ $property->status === 'sold' ? 'grayscale contrast-125' : '' }}"
             style="background-image: url('{{ $property->image ?? '/images/placeholder-property.jpg' }}')">
        </div>

        <!-- Status Badge -->
        <div class="absolute top-3 left-3">
            <span @class([
                'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm border',
                'bg-green-100 text-green-700 border-green-200' => $property->status === 'available',
                'bg-red-100 text-red-700 border-red-200' => $property->status === 'sold',
                'bg-gray-200 text-gray-600 border-gray-300' => !in_array($property->status, ['available', 'sold']),
            ])>
                {{ ucfirst($property->status) }}
            </span>
        </div>

        <!-- View Count -->
        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px]">{{ $property->views > 0 ? 'visibility' : 'visibility_off' }}</span>
            <span>{{ $property->views }} Views</span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-5 flex flex-col gap-2 flex-1">
        <!-- Price -->
        <div class="flex justify-between items-start">
            <h3 @class([
                'text-2xl font-bold',
                'text-amber-600' => $property->status === 'available',
                'text-gray-400 line-through decoration-red-500 decoration-2' => $property->status === 'sold',
                'text-gray-400 italic' => !in_array($property->status, ['available', 'sold']),
            ])>
                ${{ number_format($property->price, 0) }}
            </h3>
        </div>

        <!-- Title and Location -->
        <div>
            <h4 class="font-bold text-slate-900 text-lg leading-tight truncate"
                title="{{ $property->title }}">
                {{ strlen($property->title) > 30 ? substr($property->title, 0, 30) . '...' : $property->title }}
            </h4>
            <div class="flex items-center gap-1 text-gray-500 text-sm mt-1">
                <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                <span>{{ $property->location ?? 'Location not specified' }}</span>
            </div>
        </div>

        <!-- Specs -->
        <div class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-2 pt-3 border-t border-gray-50">
            <span>
                @if($property->type === 'Office' || $property->type === 'Commercial')
                    {{ $property->type }}
                @else
                    {{ $property->bedrooms ?? 3 }} Bed
                @endif
            </span>
            <span> • </span>
            <span>{{ $property->bathrooms ?? 2 }} Bath</span>
            <span> • </span>
            <span>{{ number_format($property->sqft ?? 1500) }} sqft</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="px-2 py-2 border-t border-gray-100 bg-gray-50/50 flex items-center justify-around">
        <!-- Share Button -->
        <x-admin.inventory.property-share-button
            :property="$property"
            class="flex-1 flex flex-col gap-1 py-1 justify-center items-center group/btn"
            title="Copy WhatsApp share message">
            <span class="text-[10px] font-medium">Share</span>
        </x-admin.inventory.property-share-button>

        <div class="w-px h-6 bg-gray-200"></div>

        <!-- Edit Button -->
        <button wire:click="openEditModal" class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-blue-600 transition-colors group/btn" title="Edit Property">
            <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">edit</span>
            <span class="text-[10px] font-medium">Edit</span>
        </button>

        <div class="w-px h-6 bg-gray-200"></div>

        <!-- Delete Button -->
        <button wire:click="openDeleteModal" class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-red-600 transition-colors group/btn" title="Delete Property">
            <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">delete</span>
            <span class="text-[10px] font-medium">Delete</span>
        </button>
    </div>
</div>