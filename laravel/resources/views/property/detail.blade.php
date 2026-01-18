<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $property->name ?? 'Property Detail' }} - <span x-text="settings.agency_name || 'Loading...'"</span></title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3e6ae5",
                        "primary-dark": "#2d52b5",
                        "gold": "#C5A059",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111521",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "1rem", "lg": "0.75rem", "xl": "1rem", "2xl": "1.5rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>.no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#121317] font-display min-h-screen flex flex-col" x-data="propertyData()" x-init="init()">
<main class="w-full bg-white min-h-screen flex flex-col relative pb-20 lg:pb-12 mx-auto">
<x-public.site-header />

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 sm:px-6 lg:px-10 mt-6 items-start">
    <!-- Left Column (8 cols) - Main Content -->
    <div class="lg:col-span-8">
        <!-- Hero Section / Image Slideshow -->
        <div class="relative w-full h-[500px] bg-gray-100 dark:bg-gray-800 group rounded-2xl overflow-hidden shadow-lg">
            <div class="w-full h-full bg-cover bg-center transition-transform duration-700 hover:scale-105" data-alt="{{ $property->name }} - Property image" style="background-image: url('{{ $property->image_url ?? asset('images/no-image.jpg') }}')"></div>
            <div class="absolute top-6 left-6">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-500 text-white shadow-md uppercase tracking-wider">
                    {{ $property->status ?? 'For Sale' }}
                </span>
            </div>
            <div class="absolute bottom-6 right-6">
                <button class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-black/60 hover:bg-black/70 backdrop-blur-md text-white transition-colors">
                    <span class="material-symbols-outlined text-[16px] mr-2">photo_camera</span>
                    Show all photos ({{ count($property->all_images ?? []) }})
                </button>
            </div>
            <button class="absolute left-4 top-1/2 -translate-y-1/2 size-12 bg-white/90 rounded-full flex items-center justify-center text-gray-800 opacity-0 group-hover:opacity-100 transition-all hover:bg-white shadow-lg">
                <span class="material-symbols-outlined text-xl">chevron_left</span>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 size-12 bg-white/90 rounded-full flex items-center justify-center text-gray-800 opacity-0 group-hover:opacity-100 transition-all hover:bg-white shadow-lg">
                <span class="material-symbols-outlined text-xl">chevron_right</span>
            </button>
        </div>

        <!-- Property Details Card (White Background) -->
        <div class="bg-white dark:bg-background-dark rounded-2xl p-6 lg:p-8 shadow-black border border-gray-100 dark:border-gray-800 mt-6">
            <!-- Property Details Section -->
            <div>
                <!-- Mobile: Price merged with title (hide "Price" label) -->
                <div class="lg:hidden">
                    <h1 class="text-gold text-[32px] font-bold leading-tight tracking-tight mb-3">$ {{ number_format($property->price ?? 0) }}</h1>
                    <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-snug mb-3">{{ $property->name ?? 'Property' }}</h2>
                    <div class="flex items-start gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary mt-0.5 text-[20px]">location_on</span>
                        <p class="text-base font-normal">{{ $property->location ?? 'Location not specified' }}</p>
                    </div>
                </div>

                <!-- Desktop: Title and Location only (price in sidebar) -->
                <div class="hidden lg:block mb-8">
                    <h1 class="text-gray-900 dark:text-white text-3xl lg:text-4xl font-extrabold leading-tight mb-3">{{ $property->name ?? 'Property' }}</h1>
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary text-[22px]">location_on</span>
                        <p class="text-lg font-medium">{{ $property->location ?? 'Location not specified' }}</p>
                    </div>
                </div>

                <!-- Property Specs Grid -->
                <div class="mt-6">
                    <!-- Mobile: 4-col centered grid -->
                    <div class="grid grid-cols-4 gap-2 lg:flex lg:flex-wrap lg:gap-4">
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">bed</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->beds ?? 0 }} Beds</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">bathtub</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->baths ?? 0 }} Baths</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">square_foot</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ number_format($property->sqft ?? 0) }} Sqft</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">apartment</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->type_label ?? 'N/A' }} Flr</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-100 dark:bg-gray-800 my-8"></div>

            <!-- About Property Section -->
            <div class="mb-10">
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-4">About this property</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-300 text-base leading-relaxed">
                        {!! $property->description ?? 'No description available for this property.' !!}
                    </p>
                    <a class="inline-flex items-center gap-1 mt-3 text-primary font-semibold text-sm hover:underline cursor-pointer">
                        Read full description <span class="material-symbols-outlined text-[16px]">arrow_downward</span>
                    </a>
                </div>
            </div>

            <!-- Amenities Section -->
            <div class="mb-10">
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-6">Amenities</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6">
                    @if($property->amenities && count($property->amenities) > 0)
                        @foreach($property->amenities as $amenity)
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
                            <span class="text-base">{{ $amenity }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-gray-500 text-[20px]">info</span>
                            <span class="text-base">No amenities listed</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location Section -->
            <div>
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-4">Location</h3>
                @if($property->maps_embed_url)
                    <div class="rounded-2xl overflow-hidden bg-gray-200 dark:bg-gray-800 mb-4 relative group h-[300px] border border-gray-200 dark:border-gray-700">
                        <iframe 
                            src="{{ $property->maps_embed_url }}" 
                            class="w-full h-full border-0"
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            data-alt="Map view showing the location of {{ $property->name }}">
                        </iframe>
                        <div class="absolute bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-auto">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($property->location) }}" target="_blank" class="w-full md:w-auto px-6 py-3 bg-white text-gray-900 rounded-xl font-bold shadow-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">directions</span>
                                Get Directions
                            </a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl overflow-hidden bg-gray-200 dark:bg-gray-800 mb-4 relative group h-[300px] border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-gray-400 text-6xl mb-2 block">location_on</span>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">{{ $property->location ?? 'Location not specified' }}</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Map not available</p>
                        </div>
                        @if($property->location)
                        <div class="absolute bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-auto">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($property->location) }}" target="_blank" class="w-full md:w-auto px-6 py-3 bg-white text-gray-900 rounded-xl font-bold shadow-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">directions</span>
                                Get Directions
                            </a>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column (4 cols) - Sidebar (Price & Actions) -->
    <div class="lg:col-span-4">
        <!-- Desktop: Full sidebar with price and buttons -->
        <div class="bg-white dark:bg-background-dark rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 hidden lg:block">
            <div class="mb-6">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Price</p>
                <h1 class="text-gold text-4xl font-extrabold leading-tight tracking-tight">$ {{ number_format($property->price ?? 0) }}</h1>
                <p class="text-xs text-gray-400 mt-1">Plus taxes & fees</p>
            </div>
            <div class="w-full h-px bg-gray-100 dark:bg-gray-800 my-6"></div>
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-12 rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('images/agent-default.jpg') }}')"></div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Listing Agent</p>
                        <p class="font-bold text-gray-900 dark:text-white" x-text="settings.agency_name || 'Loading...'"></p>
                    </div>
                </div>
                <a :href="'tel:' + getCleanedPhoneNumber()" class="w-full flex items-center justify-center gap-2 h-14 rounded-xl border-2 border-primary bg-white hover:bg-gray-50 text-primary font-bold text-lg transition-transform active:scale-[0.98] shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">call</span>
                    Call Now
                </a>
                <a :href="getWhatsAppMessage()" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2 h-14 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-lg transition-transform active:scale-[0.98] shadow-md shadow-green-500/20">
                    <i class="fa-brands fa-whatsapp text-[24px]"></i>
                    WhatsApp
                </a>
                <p class="text-center text-xs text-gray-400 pt-2">By contacting, you agree to our Terms of Service.</p>
            </div>
        </div>

        <!-- Financing Card (Desktop only) -->
        <div class="mt-6 p-6 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 hidden lg:block">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary text-3xl">real_estate_agent</span>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">Need help financing?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Get pre-approved for a mortgage today.</p>
                    <a class="text-sm font-bold text-primary hover:underline" href="#">Check Rates →</a>
                </div>
            </div>
        </div>

        <!-- Mobile: Compact agent info (no price, no buttons) -->
        <div class="lg:hidden mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('images/agent-default.jpg') }}')"></div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Listed by</p>
                    <p class="font-bold text-gray-900 dark:text-white" x-text="settings.agency_name || 'Loading...'"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile: Sticky Bottom Action Bar -->
<div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white dark:bg-background-dark border-t border-gray-100 dark:border-gray-800 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] flex items-center px-4 gap-3 h-16">
    <a :href="'tel:' + getCleanedPhoneNumber()" class="flex-1 flex items-center justify-center gap-2 h-11 rounded-full border border-primary bg-white hover:bg-gray-50 text-primary font-bold text-base transition-transform active:scale-95 shadow-sm">
        <span class="material-symbols-outlined text-[20px]">call</span>
        Call Now
    </a>
    <a :href="getWhatsAppMessage()" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 h-11 rounded-full bg-green-500 hover:bg-green-600 text-white font-bold text-base transition-transform active:scale-95 shadow-md shadow-green-500/20">
        <i class="fa-brands fa-whatsapp text-[20px]"></i>
        WhatsApp
    </a>
</div>

</main>

<script>
function propertyData() {
    return {
        settings: {},
        whatsappLink: '',
        
        async init() {
            await this.loadSettings();
        },
        
        async loadSettings() {
            try {
                const response = await fetch('/api/settings');
                const data = await response.json();
                if (data.success) {
                    this.settings = data.data;
                    this.generateWhatsAppLink();
                }
            } catch (error) {
                console.error('Failed to load settings:', error);
            }
        },
        
        generateWhatsAppLink() {
            const phone = this.settings.w_no || '';
            const digits = phone.replace(/\D/g, '');
            this.whatsappLink = 'https://wa.me/' + digits;
        },
        
        getWhatsAppMessage() {
            const domain = window.location.origin.replace(/^https?:\/\//, '');
            const message = `Hii i'm interested in\n*${this.getPropertyName()}*\nat ${this.getPropertyLocation()}\nUID: ${this.getPropertyId()}\nLink: ${domain}/property/${this.getPropertyId()}`;
            const encodedMessage = encodeURIComponent(message);
            return `${this.whatsappLink}?text=${encodedMessage}`;
        },
        
        getCleanedPhoneNumber() {
            const phone = this.settings.w_no || '';
            // Keep only digits and + sign
            return phone.replace(/[^\d+]/g, '');
        },
        
        getPropertyName() {
            return document.querySelector('h1')?.textContent?.trim() || 'Property';
        },
        
        getPropertyLocation() {
            return document.querySelector('[data-alt="Property image"]')?.getAttribute('data-alt') || 'Location not specified';
        },
        
        getPropertyId() {
            return window.location.pathname.split('/').pop() || '1';
        }
    }
}
</script>

</body>
</html>