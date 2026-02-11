@props(['properties' => [], 'settings' => []])

<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
    <div class="max-w-[1280px] mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Featured Properties</h2>
            <div class="flex gap-2">
                <button class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                </button>
                <button class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors bg-primary text-white hover:bg-blue-800">
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </button>
            </div>
        </div>
        <div class="flex overflow-x-auto gap-6 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 scrollbar-hide snap-x snap-mandatory">
            @forelse($properties as $property)
                @php
                    $badge = null;
                    if ($property['label_type'] && $property['label_type'] !== 'none') {
                        $badges = [
                            'new' => ['label' => 'New', 'color' => 'bg-blue-500'],
                            'popular' => ['label' => 'Popular', 'color' => 'bg-orange-500'],
                            'verified' => ['label' => 'Verified', 'color' => 'bg-teal-600'],
                            'featured' => ['label' => 'Featured', 'color' => 'bg-purple-500'],
                        ];
                        $badge = $badges[$property['label_type']] ?? null;
                        if ($property['label_type'] === 'custom' && $property['custom_label_color']) {
                            $badge = ['label' => 'Custom', 'color' => $property['custom_label_color']];
                        }
                    }
                    
                    $propertyType = $property['property_type'] ?? 'apartment';
                    
                    $propertyTypeIcons = [
                        'apartment' => 'apartment',
                        'office' => 'business',
                        'commercial' => 'domain',
                        'villa' => 'house',
                        'plot' => 'terrain',
                    ];
                    $propertyTypeIcon = $propertyTypeIcons[$propertyType] ?? 'domain';
                    
                    $showBed = !empty($property['bedrooms']) && $property['bedrooms'] > 0;
                    $showBath = !empty($property['bathrooms']) && $property['bathrooms'] > 0 && $property['property_type'] !== 'plot';
                    $showSqft = !empty($property['area_sqft']) && $property['area_sqft'] !== 'N/A';
                    $showPropertyType = !$showBed;
                    
                    $whatsappPhone = $settings['w_no'] ?? '';
                    $domain = request()->getHost();
                    $waMessage = "Hii i'm interested in\n*{$property['title']}*\nat {$property['address']}\nUID: {$property['id']}\nLink: {$domain}/property/{$property['id']}";
                    $waUrl = $whatsappPhone ? "https://wa.me/{$whatsappPhone}?text=" . urlencode($waMessage) : '#';
                @endphp
                <article class="snap-center shrink-0 w-80 sm:w-96 bg-white dark:bg-[#121620] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col group hover:shadow-lg transition-shadow duration-300">
                    <div class="relative h-56">
                        <img alt="{{ $property['title'] }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                             src="{{ $property['image'] ?? 'https://via.placeholder.com/400x300' }}">
                        
                        {{-- Badge --}}
                        @if($badge)
                            <div class="absolute top-3 left-3 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"
                                 style="background-color: {{ $badge['color'] }}">
                                {{ $badge['label'] }}
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[20px] block">favorite</span>
                            </button>
                        </div>
                        <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">
                                    {{ $property['price'] ? '₹' . number_format($property['price']) : 'Price TBD' }}
                                </h3>
                                <h3 class="text-[#121317] dark:text-white font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors truncate">
                                    {{ $property['title'] }}
                                </h3>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-4 mt-2">
                            <span class="material-symbols-outlined text-base mr-1">location_on</span>
                            {{ $property['address'] ?? 'Location TBD' }}
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 mb-4">
                            @if($showBed)
                                <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300 text-sm">
                                    <span class="material-symbols-outlined text-lg">bed</span>
                                    <span>{{ $property['bedrooms'] }} Beds</span>
                                </div>
                            @endif
                            @if($showBath)
                                <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300 text-sm">
                                    <span class="material-symbols-outlined text-lg">bathtub</span>
                                    <span>{{ $property['bathrooms'] }} Baths</span>
                                </div>
                            @endif
                            @if($showSqft)
                                <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300 text-sm">
                                    <span class="material-symbols-outlined text-lg">square_foot</span>
                                    <span>{{ number_format($property['area_sqft']) }} sqft</span>
                                </div>
                            @endif
                            @if($showPropertyType)
                                <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300 text-sm">
                                    <span class="material-symbols-outlined text-lg">{{ $propertyTypeIcon }}</span>
                                    <span>{{ ucfirst($propertyType) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-auto flex gap-3">
                            <a href="{{ url('/property/' . $property['id']) }}" class="flex-1 py-2 px-4 rounded-lg border border-primary text-primary font-medium hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors text-sm inline-flex items-center justify-center">
                                View Details
                            </a>
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-2 px-4 rounded-lg bg-whatsapp text-white font-medium hover:bg-green-600 transition-colors flex items-center justify-center gap-2 text-sm">
                                <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="snap-center shrink-0 w-80 sm:w-96 bg-white dark:bg-[#121620] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col p-6">
                    <div class="flex flex-col items-center justify-center text-center py-8">
                        <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
                        <h4 class="text-gray-600 dark:text-gray-400 font-medium text-lg">No Featured Properties</h4>
                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">Check back later for new listings.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
