<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $property->name ?? 'Property Detail' }} - <span x-text="settings.agency_name || 'Loading...'"</span></title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
<x-site-header />

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 sm:px-6 lg:px-10 mt-6 items-start">
    <!-- Left Column (8 cols) - Main Content -->
    <div class="lg:col-span-8">
        <!-- Hero Section / Image Slideshow -->
        <div class="relative w-full h-[500px] bg-gray-100 dark:bg-gray-800 group rounded-2xl overflow-hidden shadow-lg">
            <div class="w-full h-full bg-cover bg-center transition-transform duration-700 hover:scale-105" data-alt="{{ $property->image_alt ?? 'Property image' }}" style="background-image: url('{{ $property->image_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDUIUDPDXt4RSWELVs0pw2cOd9_NEcGMtImwP-LeEDVi10-dCSU0LKvkEOMRCHCT5eZZEERz1ONjnkV2zeDN7MagX11-cyg4UlO5JRXFAUx3DiGD7VFAGfKLXrKvkgAbC06Wd-C9SSpBPqto_WrEcL8zANFZfq4wk_FTSCBr3Kd65P0Do6-iz3E4oWxEUJN0jqDODd_R-tDtKnoVf46qRqJXhIG9jzeTiA32X6yeXhiVxq_mjrKIKX9du6Puf40bvcuKzaAgcyjSIai' }}"></div>
            <div class="absolute top-6 left-6">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-500 text-white shadow-md uppercase tracking-wider">
                    {{ $property->status ?? 'For Sale' }}
                </span>
            </div>
            <div class="absolute bottom-6 right-6">
                <button class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-black/60 hover:bg-black/70 backdrop-blur-md text-white transition-colors">
                    <span class="material-symbols-outlined text-[16px] mr-2">photo_camera</span>
                    Show all photos ({{ count($property->images ?? []) }})
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
                    <h1 class="text-gold text-[32px] font-bold leading-tight tracking-tight mb-3">$ {{ number_format($property->price ?? 850000) }}</h1>
                    <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-snug mb-3">{{ $property->name ?? 'Luxury 3BHK with Sea View' }}</h2>
                    <div class="flex items-start gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary mt-0.5 text-[20px]">location_on</span>
                        <p class="text-base font-normal">{{ $property->location ?? 'Palm Jumeirah, Dubai' }}</p>
                    </div>
                </div>

                <!-- Desktop: Title and Location only (price in sidebar) -->
                <div class="hidden lg:block mb-8">
                    <h1 class="text-gray-900 dark:text-white text-3xl lg:text-4xl font-extrabold leading-tight mb-3">{{ $property->name ?? 'Luxury 3BHK with Sea View' }}</h1>
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary text-[22px]">location_on</span>
                        <p class="text-lg font-medium">{{ $property->location ?? 'Palm Jumeirah, Dubai' }}</p>
                    </div>
                </div>

                <!-- Property Specs Grid -->
                <div class="mt-6">
                    <!-- Mobile: 4-col centered grid -->
                    <div class="grid grid-cols-4 gap-2 lg:flex lg:flex-wrap lg:gap-4">
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">bed</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->beds ?? 3 }} Beds</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">bathtub</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->baths ?? 2 }} Baths</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">square_foot</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ number_format($property->sqft ?? 1800) }} Sqft</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">apartment</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->floor ?? '5th' }} Flr</span>
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
                        {!! $property->description ?? 'Experience the pinnacle of luxury living in this exclusive apartment located in the heart of Palm Jumeirah. Offering breathtaking sea views, premium finishes throughout, and direct access to the beach. The open-plan living area is flooded with natural light, seamlessly connecting to a spacious terrace perfect for entertaining. The kitchen comes fully equipped with high-end appliances, while the bedrooms offer a serene retreat with en-suite bathrooms.' !!}
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
                    @foreach($property->amenities ?? ['Covered Parking', 'Swimming Pool', '24/7 Security', 'Gym & Spa', 'Concierge Service', 'Central A/C'] as $amenity)
                    <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                        <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
                        <span class="text-base">{{ $amenity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Location Section -->
            <div>
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-4">Location</h3>
                <div class="rounded-2xl overflow-hidden bg-gray-200 dark:bg-gray-800 mb-4 relative group h-[300px] border border-gray-200 dark:border-gray-700">
                    <div class="w-full h-full bg-cover bg-center opacity-90 group-hover:opacity-100 transition-opacity" data-alt="Map view showing the location of the property" data-location="{{ $property->location ?? 'Palm Jumeirah, Dubai' }}" style="background-image: url('{{ $property->map_image_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQF3U8wwqWahzqydH8tpCM9mKKqGUhz-f-IMll0FkwDcp4nlX07epV-AneVFXxuYmLtB4kPr9rgzSOnJMQ1vk6j6TvHgO7GAVG_-D29HeqQOcJhEjqlR6x2NqQqNnlbnN8BFsPN6_WgbRg9JMPth8k-xtki32fTrshtNqgmGeozCjPAWOe7jKjLW4phYIc2pAdFwlCXrxtYrH8mNTDM9ypz3GYPkTpkt6sLtDbW_VHjYDrh3FH-b2VPqHBEmI5nwPI19un-Qi8hj1n' }}"></div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="bg-primary text-white rounded-full p-3 shadow-xl ring-4 ring-white/30 animate-bounce">
                            <span class="material-symbols-outlined block text-2xl">location_on</span>
                        </div>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-auto">
                        <button class="w-full md:w-auto px-6 py-3 bg-white text-gray-900 rounded-xl font-bold shadow-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">directions</span>
                            Get Directions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (4 cols) - Sidebar (Price & Actions) -->
    <div class="lg:col-span-4">
        <!-- Desktop: Full sidebar with price and buttons -->
        <div class="bg-white dark:bg-background-dark rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 hidden lg:block">
            <div class="mb-6">
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Price</p>
                <h1 class="text-gold text-4xl font-extrabold leading-tight tracking-tight">$ {{ number_format($property->price ?? 850000) }}</h1>
                <p class="text-xs text-gray-400 mt-1">Plus taxes & fees</p>
            </div>
            <div class="w-full h-px bg-gray-100 dark:bg-gray-800 my-6"></div>
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="size-12 rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ $property->agent_logo_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz' }}"></div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Listing Agent</p>
                        <p class="font-bold text-gray-900 dark:text-white" x-text="settings.agency_name || 'Loading...'"></p>
                    </div>
                </div>
                <button class="w-full flex items-center justify-center gap-2 h-14 rounded-xl border-2 border-primary bg-white hover:bg-gray-50 text-primary font-bold text-lg transition-transform active:scale-[0.98] shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">call</span>
                    Call Now
                </button>
                <button class="w-full flex items-center justify-center gap-2 h-14 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-lg transition-transform active:scale-[0.98] shadow-md shadow-green-500/20">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>
                    WhatsApp
                </button>
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
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ $property->agent_logo_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz' }}"></div>
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
    <button class="flex-1 flex items-center justify-center gap-2 h-11 rounded-full border border-primary bg-white hover:bg-gray-50 text-primary font-bold text-base transition-transform active:scale-95 shadow-sm">
        <span class="material-symbols-outlined text-[20px]">call</span>
        Call Now
    </button>
    <button class="flex-1 flex items-center justify-center gap-2 h-11 rounded-full bg-green-500 hover:bg-green-600 text-white font-bold text-base transition-transform active:scale-95 shadow-md shadow-green-500/20">
        <svg class="w-5 h-5 fill-current" viewbox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>
        WhatsApp
    </button>
</div>

</main>

<script>
function propertyData() {
    return {
        settings: {},
        
        async init() {
            await this.loadSettings();
        },
        
        async loadSettings() {
            try {
                const response = await fetch('/api/settings');
                const data = await response.json();
                if (data.success) {
                    this.settings = data.data;
                }
            } catch (error) {
                console.error('Failed to load settings:', error);
            }
        }
    }
}
</script>

</body>
</html>