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
        'sqft' => '1,500',
        'type' => 'Villa',
        'status' => 'available',
        'added_ago' => '2 days ago'
    ];
    
    // Use provided property data or fallback to sample
    $prop = $property ?? $sampleProperty;
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col md:flex-row w-full {{ $prop['status'] === 'sold' ? 'opacity-90 hover:opacity-100' : '' }}">
    <!-- Property Image -->
    <div class="relative w-full md:w-64 lg:w-72 h-48 md:h-auto shrink-0 overflow-hidden">
        <div class="w-full h-full bg-cover bg-center {{ $prop['status'] === 'sold' ? 'grayscale contrast-125' : '' }} group-hover:scale-105 transition-transform duration-500" 
             style="background-image: url('{{ $prop['image'] }}')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent md:hidden"></div>
        
        <!-- View Count -->
        <div class="absolute bottom-3 left-3 md:left-auto md:right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px]">{{ $prop['views'] > 0 ? 'visibility' : 'visibility_off' }}</span>
            <span>{{ $prop['views'] }}</span>
        </div>
    </div>

    <!-- Property Details -->
    <div class="p-5 flex flex-col flex-1 justify-between gap-3">
        <div class="flex flex-col gap-1">
            <!-- Status and Date -->
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusClass }}">
                    {{ ucfirst($prop['status']) }}
                </span>
                <span class="text-sm text-gray-400 hidden md:block">
                    @if($prop['status'] === 'sold')
                        Sold {{ $prop['sold_ago'] ?? '1 week ago' }}
                    @elseif($prop['status'] === 'draft')
                        Last edited {{ $prop['edited_ago'] ?? 'today' }}
                    @else
                        Added {{ $prop['added_ago'] ?? '2 days ago' }}
                    @endif
                </span>
            </div>

            <!-- Title and Price -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-1">
                <h3 class="text-xl font-bold text-slate-900 leading-tight truncate" title="{{ $prop['title'] }}">
                    {{ $prop['title'] }}
                </h3>
                <h3 class="text-xl font-bold {{ $priceClass }}">
                    {{ $prop['price'] }}
                </h3>
            </div>

            <!-- Location -->
            <div class="flex items-center gap-1 text-gray-500 text-sm">
                <span class="material-symbols-outlined text-[18px] text-red-500">location_on</span>
                <span>{{ $prop['location'] }}</span>
            </div>
        </div>

        <!-- Property Specs -->
        <div class="flex items-center flex-wrap gap-4 mt-2 pt-3 border-t border-gray-50">
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">
                    @if($prop['type'] === 'Office' || $prop['type'] === 'Commercial')
                        desk
                    @else
                        bed
                    @endif
                </span>
                <span>
                    @if($prop['type'] === 'Office' || $prop['type'] === 'Commercial')
                        {{ $prop['type'] }}
                    @else
                        {{ $prop['bedrooms'] ?? 3 }} Beds
                    @endif
                </span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">bathtub</span>
                <span>{{ $prop['bathrooms'] ?? 2 }} Baths</span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">square_foot</span>
                <span>{{ number_format($prop['sqft'] ?? 1500) }} sqft</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="px-4 py-3 md:py-0 md:px-6 bg-gray-50 md:bg-transparent md:border-l border-t md:border-t-0 border-gray-100 flex md:flex-col items-center justify-end md:justify-center gap-2 md:w-32 shrink-0">
        <!-- Share Button -->
        @if($prop['status'] !== 'draft')
            <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Share via WhatsApp">
                <span class="material-symbols-outlined text-[20px]">chat</span>
            </button>
        @else
            <button class="flex items-center justify-center size-9 rounded-full text-gray-300 cursor-not-allowed" title="Share disabled">
                <span class="material-symbols-outlined text-[20px]">chat</span>
            </button>
        @endif

        <!-- Edit Button -->
        <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Property">
            <span class="material-symbols-outlined text-[20px]">edit</span>
        </button>

        <!-- More Options / Delete Button -->
        @if($prop['status'] === 'draft')
            <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Draft">
                <span class="material-symbols-outlined text-[20px]">delete</span>
            </button>
        @else
            <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-slate-700 hover:bg-gray-100 transition-colors" title="More Options">
                <span class="material-symbols-outlined text-[20px]">more_vert</span>
            </button>
        @endif
    </div>
</div>