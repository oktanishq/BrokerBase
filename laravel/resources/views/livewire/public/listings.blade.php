<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
    <div class="max-w-[1280px] mx-auto">
        
        <!-- Sort Controls -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-[#121317]">{{ $title ?? 'Featured Properties' }}</h3>
            
            <!-- Sort Dropdown -->
            <div class="relative" x-data="{ sortOpen: false }">
                <button @click="sortOpen = !sortOpen"
                        class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">sort</span>
                    <span>
                        @switch(request()->get('sort', 'newest'))
                            @case('newest') Newest First @break
                            @case('price_low') Price: Low to High @break
                            @case('price_high') Price: High to Low @break
                            @case('popular') Most Popular @break
                            @default Newest First
                        @endswitch
                    </span>
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                
                <div x-show="sortOpen" x-transition x-cloak
                     class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-20">
                    <ul class="py-2">
                        <li>
                            <a href="?sort=newest"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 {{ request()->get('sort') === 'newest' || !request()->has('sort') ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                Newest First
                            </a>
                        </li>
                        <li>
                            <a href="?sort=price_low"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 {{ request()->get('sort') === 'price_low' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                Price: Low to High
                            </a>
                        </li>
                        <li>
                            <a href="?sort=price_high"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 {{ request()->get('sort') === 'price_high' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                Price: High to Low
                            </a>
                        </li>
                        <li>
                            <a href="?sort=popular"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 {{ request()->get('sort') === 'popular' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                Most Popular
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Error State -->
        @if(!empty($error))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <span class="material-symbols-outlined text-red-400 text-xl mr-2">error</span>
                    <div>
                        <h4 class="text-red-800 font-medium">Error Loading Properties</h4>
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @forelse($properties as $property)
                <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <!-- Property Badge -->
                        @php $badge = $this->getPropertyBadge($property) @endphp
                        @if($badge)
                            <div class="absolute top-3 left-3 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"
                                 style="background-color: {{ $badge['color'] }}">
                                {{ $badge['label'] }}
                            </div>
                        @endif

                        <!-- Favorite Button -->
                        <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[20px] block">favorite</span>
                            </button>
                        </div>

                        <!-- Property Image -->
                        <img alt="Property image"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             src="{{ $property['primary_image_path'] ? asset('storage/' . $property['primary_image_path']) : 'https://via.placeholder.com/400x300' }}">
                        <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
                    </div>

                    <div class="p-5 flex flex-col gap-3 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">{{ $property['price'] ? '₹' . number_format($property['price']) : 'Price TBD' }}</h3>
                                <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">{{ $property['title'] }}</h4>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 text-[#666e85] text-sm">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                            <p class="truncate">{{ $property['address'] ?? 'Location TBD' }}</p>
                        </div>

                        <!-- Property Specs -->
                        <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
                            @if($property['bedrooms'] && $property['bedrooms'] > 0)
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
                                    <span>{{ $property['bedrooms'] }} Beds</span>
                                </div>
                            @endif
                            @if($property['bathrooms'] && $property['bathrooms'] > 0)
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
                                    <span>{{ $property['bathrooms'] }} Baths</span>
                                </div>
                            @endif
                            @if($property['area_sqft'] && $property['area_sqft'] !== 'N/A')
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                                    <span>{{ number_format($property['area_sqft']) }} sqft</span>
                                </div>
                            @endif
                            @if(!$property['bedrooms'] || $property['bedrooms'] === 0)
                                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">domain</span>
                                    <span>{{ ucfirst($property['property_type'] ?? 'Commercial') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-1">
                            <a href="{{ url('/property/' . $property['id']) }}" class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                                View Details
                            </a>
                            <a href="{{ $this->getWhatsAppMessage($property) }}" target="_blank" rel="noopener noreferrer" class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                                <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
                    <h4 class="text-gray-600 font-medium text-lg">No Properties Available</h4>
                    <p class="text-gray-500 text-sm mt-1">Check back later for new listings.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="flex justify-center mt-8">
                <nav class="flex items-center gap-2">
                    {{ $properties->links() }}
                </nav>
            </div>
        @endif
    </div>
</section>
