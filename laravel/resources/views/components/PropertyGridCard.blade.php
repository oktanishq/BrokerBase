@props(['property'])

@php
    $statusClasses = [
        'available' => 'bg-green-100 text-green-700 border-green-200',
        'sold' => 'bg-red-100 text-red-700 border-red-200',
        'draft' => 'bg-gray-200 text-gray-600 border-gray-300'
    ];
    
    $priceClasses = [
        'available' => 'text-amber-600',
        'sold' => 'text-gray-400 line-through decoration-red-500 decoration-2',
        'draft' => 'text-gray-400 italic'
    ];
    
    $status = strtolower($property['status'] ?? 'available');
    $statusClass = $statusClasses[$status] ?? $statusClasses['available'];
    $priceClass = $priceClasses[$status] ?? $priceClasses['available'];
    
    // Sample property data for now - will be replaced with real data later
    $sampleProperty = [
        'id' => 1,
        'title' => 'Sunset Villa - Luxury Oceanfront Estate',
        'price' => '$ 450,000',
        'location' => 'Business Bay, Dubai',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f',
        'views' => 124,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'sqft' => 1500,
        'type' => 'Villa',
        'status' => 'available'
    ];
    
    // Use provided property data or fallback to sample
    $prop = $property ?? $sampleProperty;
    
    // Format specs for grid view
    $bedText = $prop['type'] === 'Office' || $prop['type'] === 'Commercial' ? $prop['type'] : ($prop['bedrooms'] ?? 3) . ' Bed';
    $bathText = ($prop['bathrooms'] ?? 2) . ' Bath';
    $sqftText = number_format($prop['sqft'] ?? 1500) . ' sqft';
    $specsText = $bedText . ' • ' . $bathText . ' • ' . $sqftText;
    
    // Truncate title for grid view
    $truncatedTitle = strlen($prop['title']) > 30 ? substr($prop['title'], 0, 30) . '...' : $prop['title'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col {{ $prop['status'] === 'sold' ? 'opacity-90 hover:opacity-100' : '' }}">
    <!-- Property Image -->
    <div class="relative aspect-video bg-gray-200 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center {{ $prop['status'] === 'sold' ? 'grayscale contrast-125' : '' }} group-hover:scale-105 transition-transform duration-500" 
             style="background-image: url('{{ $prop['image'] }}')">
        </div>
        
        <!-- Status Badge -->
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }} shadow-sm border">
                {{ ucfirst($prop['status']) }}
            </span>
        </div>
        
        <!-- View Count -->
        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px]">{{ $prop['views'] > 0 ? 'visibility' : 'visibility_off' }}</span>
            <span>{{ $prop['views'] }} Views</span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-5 flex flex-col gap-2 flex-1">
        <!-- Price -->
        <div class="flex justify-between items-start">
            <h3 class="text-2xl font-bold {{ $priceClass }}">{{ $prop['price'] }}</h3>
        </div>

        <!-- Title and Location -->
        <div>
            <h4 class="font-bold text-slate-900 text-lg leading-tight truncate" 
                title="{{ $prop['title'] }}"
                x-text="'{{ $truncatedTitle }}'">
                {{ $truncatedTitle }}
            </h4>
            <div class="flex items-center gap-1 text-gray-500 text-sm mt-1">
                <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                <span>{{ $prop['location'] }}</span>
            </div>
        </div>

        <!-- Specs -->
        <p class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-2 pt-3 border-t border-gray-50">
            {{ $specsText }}
        </p>
    </div>

    <!-- Action Buttons -->
    <div class="px-2 py-2 border-t border-gray-100 bg-gray-50/50 flex items-center justify-around">
        <!-- Share Button -->
        @if($prop['status'] !== 'draft')
            <button class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-green-600 transition-colors group/btn" title="Share via WhatsApp">
                <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">chat</span>
                <span class="text-[10px] font-medium">Share</span>
            </button>
        @else
            <button class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-300 cursor-not-allowed" title="Share disabled">
                <span class="material-symbols-outlined">chat</span>
                <span class="text-[10px] font-medium">Share</span>
            </button>
        @endif
        
        <div class="w-px h-6 bg-gray-200"></div>

        <!-- Edit Button -->
        <button class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-blue-600 transition-colors group/btn" title="Edit Property">
            <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">edit</span>
            <span class="text-[10px] font-medium">Edit</span>
        </button>

        <div class="w-px h-6 bg-gray-200"></div>

        <!-- More Options / Delete Button -->
        @if($prop['status'] === 'draft')
            <button class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-red-600 transition-colors group/btn" title="Delete Draft">
                <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">delete</span>
                <span class="text-[10px] font-medium">Delete</span>
            </button>
        @else
            <button class="flex-1 flex flex-col gap-1 py-1 justify-center items-center text-gray-400 hover:text-slate-700 transition-colors group/btn" title="More Options">
                <span class="material-symbols-outlined group-hover/btn:scale-110 transition-transform">more_horiz</span>
                <span class="text-[10px] font-medium">More</span>
            </button>
        @endif
    </div>
</div>