<section id="featured" class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
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
                             src="{{ $property['image'] ?? 'https://via.placeholder.com/400x300' }}">
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
        @if($properties->count() > 0)
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-8">
                <!-- Desktop: Left side - Show dropdown and results info -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Items per page dropdown -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 whitespace-nowrap">Show:</label>
                        <div class="relative">
                            <select wire:model.live="perPage" 
                                    class="appearance-none pl-3 pr-8 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:royal-blue/20 focus:border-royal-blue transition-all min-w-[70px] cursor-pointer hover:border-gray-300">
                                <option value="6">6</option>
                                <option value="12">12</option>
                                <option value="18">18</option>
                                <option value="24">24</option>
                            </select>
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-lg pointer-events-none">expand_more</span>
                        </div>
                        <span class="text-sm text-gray-600">per page</span>
                    </div>
                    
                    <!-- Results info -->
                    <span class="text-sm text-gray-500">
                        Showing {{ $this->showingFrom }}-{{ $this->showingTo }} of {{ $this->showingCount }} properties
                    </span>
                </div>

                <!-- Desktop: Pagination controls -->
                <div class="hidden md:flex items-center gap-2">
                    <!-- Jump to page -->
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-500">Jump to:</span>
                        <input type="number" 
                               wire:model="jumpToPage"
                               wire:keydown.enter="jumpToPageAction()"
                               min="1" 
                               max="{{ $this->totalPages }}"
                               step="1"
                               oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value < 1) this.value = 1;"
                               placeholder="Page"
                               class="w-16 py-2 px-2 text-center text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:royal-blue/20 focus:border-royal-blue transition-all" />
                        <button wire:click="jumpToPageAction()" 
                               @disabled(!$this->jumpToPage || $this->jumpToPage < 1 || $this->jumpToPage > $this->totalPages)
                               class="px-3 py-2 text-sm font-medium text-white bg-royal-blue rounded-lg hover:bg-blue-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Go
                        </button>
                    </div>
                    
                    <!-- Page numbers -->
                    <div class="flex items-center gap-1">
                        <button wire:click="previousPage"
                                @disabled($properties->onFirstPage())
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $properties->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                            <span class="material-symbols-outlined text-lg mr-1">chevron_left</span>
                            <span>Prev</span>
                        </button>

                        @if($this->paginationWindow['showAll'])
                            @foreach($this->paginationWindow['pages'] as $page)
                                <button wire:click="gotoPage({{ $page }})"
                                        class="w-9 h-9 text-sm font-medium rounded-lg transition-colors {{ $properties->currentPage() === $page ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                        @else
                            @foreach($this->paginationWindow['pages'] as $page)
                                @if($page === '...')
                                    <span class="px-2 text-gray-400">...</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                            class="w-9 h-9 text-sm font-medium rounded-lg transition-colors {{ $properties->currentPage() === $page ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif

                        <button wire:click="nextPage"
                                @disabled(!$properties->hasMorePages())
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ !$properties->hasMorePages() ? 'text-gray-300 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800 shadow-sm' }}">
                            <span>Next</span>
                            <span class="material-symbols-outlined text-lg ml-1">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Mobile: Stacked layout -->
                <div class="flex flex-col gap-3 w-full md:hidden">
                    <!-- Row 1: Page numbers -->
                    <div class="flex items-center justify-center gap-1 overflow-x-auto py-1">
                        <button wire:click="previousPage"
                                @disabled($properties->onFirstPage())
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $properties->onFirstPage() ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </button>

                        @if($this->paginationWindow['showAll'])
                            @foreach($this->paginationWindow['pages'] as $page)
                                <button wire:click="gotoPage({{ $page }})"
                                        class="w-9 h-9 text-sm font-medium rounded-lg transition-colors {{ $properties->currentPage() === $page ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                        @else
                            @foreach($this->paginationWindow['pages'] as $page)
                                @if($page === '...')
                                    <span class="px-2 text-gray-400">...</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                            class="w-9 h-9 text-sm font-medium rounded-lg transition-colors {{ $properties->currentPage() === $page ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif

                        <button wire:click="nextPage"
                                @disabled(!$properties->hasMorePages())
                                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ !$properties->hasMorePages() ? 'text-gray-300 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800 shadow-sm' }}">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </button>
                    </div>

                    <!-- Row 2: Jump to and Show per page inline -->
                    <div class="flex items-center justify-between gap-2">
                        <!-- Jump to page -->
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-gray-500">Jump to:</span>
                            <input type="number" 
                                   wire:model="jumpToPage"
                                   wire:keydown.enter="jumpToPageAction()"
                                   min="1" 
                                   max="{{ $this->totalPages }}"
                                   step="1"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value < 1) this.value = 1;"
                                   placeholder="Page"
                                   class="w-16 py-2 px-2 text-center text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:royal-blue/20 focus:border-royal-blue transition-all" />
                            <button wire:click="jumpToPageAction()" 
                                    @disabled(!$this->jumpToPage || $this->jumpToPage < 1 || $this->jumpToPage > $this->totalPages)
                                    class="px-3 py-2 text-sm font-medium text-white bg-royal-blue rounded-lg hover:bg-blue-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Go
                            </button>
                        </div>
                        
                        <!-- Items per page dropdown -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600 whitespace-nowrap">Show:</label>
                            <div class="relative">
                                <select wire:model.live="perPage" 
                                        class="appearance-none pl-3 pr-8 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:royal-blue/20 focus:border-royal-blue transition-all min-w-[70px] cursor-pointer hover:border-gray-300">
                                    <option value="6">6</option>
                                    <option value="12">12</option>
                                    <option value="18">18</option>
                                    <option value="24">24</option>
                                </select>
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-lg pointer-events-none">expand_more</span>
                            </div>
                            <span class="text-sm text-gray-600">/page</span>
                        </div>
                    </div>

                    <!-- Row 3: Results info -->
                    <div class="text-center">
                        <span class="text-sm text-gray-500">
                            Showing {{ $this->showingFrom }}-{{ $this->showingTo }} of {{ $this->showingCount }} properties
                        </span>
                    </div>

                    <!-- Row 4: Page indicator -->
                    <div class="text-center">
                        <span class="text-sm text-gray-400">
                            Page {{ $properties->currentPage() }} of {{ $this->totalPages }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
