<div x-data="{
    async shareProperty() {
        const message = `🏠 *{{ addslashes($property->title) }}*
📍 Location: {{ addslashes($property->location ?? 'Not specified') }}
💰 Price: {{ $property->price }}
🏢 Type: {{ $property->type }} ({{ $property->bedrooms ?? 0 }} Bed, {{ $property->bathrooms ?? 0 }} Bath)
📐 Area: {{ number_format($property->sqft ?? 0) }} sqft

🔗 Link: {{ url('/property/' . $property->id) }}
🆔 Property ID: {{ $property->id }}`;

        try {
            await navigator.clipboard.writeText(message);
            this.showNotification('WhatsApp message copied to clipboard!', 'success');
        } catch (err) {
            console.error('Failed to copy:', err);
            this.showNotification('Failed to copy message. Please try again.', 'error');
        }
    },

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white font-medium shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col md:flex-row w-full {{ $property->status === 'sold' ? 'opacity-90 hover:opacity-100' : '' }}">

    <!-- Property Image -->
    <div class="relative w-full md:w-64 lg:w-72 h-48 md:h-auto shrink-0 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center {{ $property->status === 'sold' ? 'grayscale contrast-125' : '' }}"
             style="background-image: url('{{ $property->image ?? '/images/placeholder-property.jpg' }}')"
             class="group-hover:scale-105 transition-transform duration-500">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent md:hidden"></div>

        <!-- View Count -->
        <div class="absolute bottom-3 left-3 md:left-auto md:right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px]">{{ $property->views > 0 ? 'visibility' : 'visibility_off' }}</span>
            <span>{{ $property->views }}</span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-5 flex flex-col flex-1 justify-between gap-3">
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-1">
                <h3 class="text-xl font-bold text-slate-900 leading-tight truncate"
                    title="{{ $property->title }}">
                    {{ $property->title }}
                </h3>
                <h3 @class([
                    'text-xl font-bold',
                    'text-amber-600' => $property->status === 'available',
                    'text-gray-400 line-through decoration-red-500 decoration-2' => $property->status === 'sold',
                    'text-gray-400 italic' => !in_array($property->status, ['available', 'sold']),
                ])>
                    {{ $property->price }}
                </h3>
            </div>

            <!-- Location -->
            <div class="flex items-center gap-1 text-gray-500 text-sm">
                <span class="material-symbols-outlined text-[18px] text-red-500">location_on</span>
                <span>{{ $property->location ?? 'Location not specified' }}</span>
            </div>
        </div>

        <!-- Property Specs -->
        <div class="flex items-center flex-wrap gap-4 mt-2 pt-3 border-t border-gray-50">
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
    <div class="px-4 py-3 md:py-0 md:px-6 bg-gray-50 md:bg-transparent md:border-l border-t md:border-t-0 border-gray-100 flex md:flex-col items-center justify-end md:justify-center gap-2 md:w-32 shrink-0">
        <!-- Share Button -->
        @if($property->status !== 'draft')
            <button @click="shareProperty()" class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Copy WhatsApp share message">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
            </button>
        @else
            <button class="flex items-center justify-center size-9 rounded-full text-gray-300 cursor-not-allowed" title="Share disabled">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
            </button>
        @endif

        <!-- Edit Button -->
        <button wire:click="openEditModal" class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Property">
            <span class="material-symbols-outlined text-[20px]">edit</span>
        </button>

        <!-- Delete Button -->
        <button wire:click="openDeleteModal" class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Property">
            <span class="material-symbols-outlined text-[20px]">delete</span>
        </button>
    </div>
</div>