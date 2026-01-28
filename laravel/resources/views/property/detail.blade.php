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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
<body class="bg-background-light dark:bg-background-dark text-[#121317] font-display min-h-screen flex flex-col" x-data="imageGallery()" x-init="init()">
<main class="w-full bg-white min-h-screen flex flex-col relative pb-20 lg:pb-12 mx-auto">
<x-public.site-header />

<!-- Main Content Grid -->
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 sm:px-6 lg:px-10 mt-6">
    <!-- Left Column (4 cols) - Image Gallery -->
    <div class="lg:col-span-4 lg:sticky lg:top-24 lg:self-start h-fit z-10">
        <!-- Image Gallery with Fixed Aspect Ratio -->
        <div class="relative w-full rounded-2xl overflow-hidden shadow-lg group" style="aspect-ratio: 4/5; max-height: calc(100vh - 22rem);">
            <!-- Main Swiper -->
            <div class="swiper main-swiper absolute inset-0">
                <div class="swiper-wrapper h-full">
                    @if(count($property->all_images ?? []) > 0)
                        @foreach($property->all_images as $index => $image)
                        <div class="swiper-slide h-full">
                            <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center relative">
                                <img src="{{ $image['url'] }}"
                                     alt="{{ $property->name }} - Image {{ $index + 1 }}"
                                     class="w-full h-full object-contain transition-opacity duration-300"
                                     onload="this.style.opacity='1'"
                                     style="opacity: 0;">
                                <!-- Error fallback -->
                                <div class="absolute inset-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center opacity-0 transition-opacity">
                                    <span class="material-symbols-outlined text-gray-400 text-4xl">image</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="swiper-slide h-full">
                            <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-gray-400 text-6xl mb-2 block">image</span>
                                    <p class="text-gray-500 dark:text-gray-400">No images available</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Navigation buttons -->
                <button class="swiper-button-prev-custom absolute left-4 top-1/2 -translate-y-1/2 size-12 bg-white/90 hover:bg-white rounded-full flex items-center justify-center text-gray-800 shadow-lg transition-all z-10"
                        x-show="totalSlides > 1"
                        x-transition>
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </button>
                <button class="swiper-button-next-custom absolute right-4 top-1/2 -translate-y-1/2 size-12 bg-white/90 hover:bg-white rounded-full flex items-center justify-center text-gray-800 shadow-lg transition-all z-10"
                        x-show="totalSlides > 1"
                        x-transition>
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </button>

                <!-- Status badge -->
                <div class="absolute top-4 left-4 z-20">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-green-500 text-white shadow-md uppercase tracking-wider">
                        {{ $property->status ?? 'For Sale' }}
                    </span>
                </div>

                <!-- Image counter -->
                <div class="absolute top-4 right-4 z-20" x-show="totalSlides > 1" x-transition>
                    <div class="bg-black/60 backdrop-blur-md text-white px-3 py-1 rounded-full text-sm font-medium">
                        <span x-text="currentSlide + 1"></span> / <span x-text="totalSlides"></span>
                    </div>
                </div>

                <!-- Show all photos button -->
                <div class="absolute bottom-4 right-4 z-20">
                    <button @click="openGalleryModal()"
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-black/60 hover:bg-black/70 backdrop-blur-md text-white transition-colors">
                        <span class="material-symbols-outlined text-[16px] mr-2">photo_camera</span>
                        Show all photos (<span x-text="totalSlides"></span>)
                    </button>
                </div>
            </div>
        </div>

        <!-- Thumbnail swiper (shown when multiple images) -->
        <div class="mt-4 overflow-x-auto" x-show="totalSlides > 1" x-transition>
            <div class="flex gap-3 pb-2 min-w-max">
                @if(count($property->all_images ?? []) > 0)
                    @foreach($property->all_images as $index => $image)
                    <div class="cursor-pointer flex-shrink-0" @click="goToSlide({{ $index }})">
                        <div class="w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                             :class="currentSlide === {{ $index }} ? 'border-primary shadow-lg' : 'border-gray-200 dark:border-gray-700'">
                            <img src="{{ $image['url'] }}"
                                 alt="Thumbnail {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Action Buttons (Desktop) -->
        <div class="mt-6 hidden lg:flex gap-4">
            <a :href="'tel:' + getCleanedPhoneNumber()" class="flex-1 flex items-center justify-center gap-2 h-14 rounded-xl border-2 border-primary bg-white hover:bg-gray-50 text-primary font-bold text-lg transition-transform active:scale-[0.98] shadow-sm">
                <span class="material-symbols-outlined text-[24px]">call</span>
                Call Now
            </a>
            <a :href="getWhatsAppMessage()" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-2 h-14 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-lg transition-transform active:scale-[0.98] shadow-md shadow-green-500/20">
                <i class="fa-brands fa-whatsapp text-[24px]"></i>
                WhatsApp
            </a>
        </div>
    </div>

    <!-- Right Column (8 cols) - Property Details & Actions -->
    <div class="lg:col-span-8 space-y-6">

        <!-- Property Details Card -->
        <div class="bg-white dark:bg-background-dark rounded-2xl p-6 lg:p-8 shadow-black border border-gray-100 dark:border-gray-800">
            <!-- Property Details Section -->
            <div>
                <!-- Mobile: Title, Location, then Price -->
                <div class="lg:hidden">
                    <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-snug mb-3">{{ $property->name ?? 'Property' }}</h2>
                    <div class="flex items-start gap-2 text-gray-500 dark:text-gray-400 mb-3">
                        <span class="material-symbols-outlined text-primary mt-0.5 text-[20px]">location_on</span>
                        <p class="text-base font-normal">{{ $property->location ?? 'Location not specified' }}</p>
                    </div>
                    <h1 class="text-gold text-[32px] font-bold leading-tight tracking-tight">{{ $property->price > 0 ? '₹ ' . number_format($property->price) : '₹ TBD' }}</h1>
                </div>

                <!-- Desktop: Title, Location, then Price -->
                <div class="hidden lg:block mb-8">
                    <h1 class="text-gray-900 dark:text-white text-3xl lg:text-4xl font-extrabold leading-tight mb-3">{{ $property->name ?? 'Property' }}</h1>
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 mb-6">
                        <span class="material-symbols-outlined text-primary text-[22px]">location_on</span>
                        <p class="text-lg font-medium">{{ $property->location ?? 'Location not specified' }}</p>
                    </div>
                    <div>
                        <h1 class="text-gold text-4xl font-extrabold leading-tight tracking-tight">{{ $property->price > 0 ? '₹ ' . number_format($property->price) : '₹ TBD' }}</h1>
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
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">stairs</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">Flr</span>
                        </div>
                        <div class="flex flex-col items-center justify-center p-3 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-5 lg:py-3">
                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 mb-1 lg:mb-0 lg:mr-3">{{ $property->type_label == 'Villa' ? 'villa' : ($property->type_label == 'Plot' ? 'landscape' : ($property->type_label == 'Commercial' ? 'business' : 'apartment')) }}</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white lg:text-sm">{{ $property->type_label ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-100 dark:bg-gray-800 my-8"></div>

            <!-- About Property Section -->
            <div class="mb-10">
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-4">About</h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-300 text-base leading-relaxed">
                        {!! $property->description ?? 'No description available for this property.' !!}
                    </p>
                </div>
            </div>

            <!-- Amenities Section -->
            <div class="mb-10">
                <h3 class="text-gray-900 dark:text-white font-bold text-xl mb-6">Amenities</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6">
                    @php
                    $amenitiesData = \App\Data\AmenitiesData::getAll();
                    $iconMap = array_column($amenitiesData, 'icon', 'name');
                    @endphp
                    @if($property->amenities && count($property->amenities) > 0)
                        @foreach($property->amenities as $amenity)
                        <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-green-500 text-[20px]">{{ $iconMap[$amenity] ?? 'check_circle' }}</span>
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

</div>

<!-- You May Also Like Section 
<x-public.detail.alsolike :current-property-id="$property->id" /> -->

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

<!-- Fullscreen Image Gallery Overlay -->
<div x-show="galleryModalOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm"
     @keydown.escape.window="closeGalleryModal()">

    <!-- Close Button -->
    <button @click="closeGalleryModal()"
            class="absolute top-4 right-4 z-20 p-3 bg-black/50 hover:bg-black/70 rounded-full text-white transition-colors">
        <span class="material-symbols-outlined text-2xl">close</span>
    </button>

    <!-- Main Image Swiper -->
    <div class="swiper modal-swiper h-full">
        <div class="swiper-wrapper">
            @if(count($property->all_images ?? []) > 0)
                @foreach($property->all_images as $index => $image)
                <div class="swiper-slide">
                    <div class="w-full h-full flex items-center justify-center p-4">
                        <img src="{{ $image['url'] }}"
                             alt="{{ $property->name }} - Image {{ $index + 1 }}"
                             class="max-w-full max-h-full object-contain">
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Bottom Thumbnail Strip -->
    <div class="absolute bottom-0 left-0 right-0 p-6 bg-black/50 backdrop-blur-md z-30"
         x-show="totalSlides > 1"
         @touchstart.stop @touchmove.stop @touchend.stop
         style="pointer-events: auto;">
        <div class="flex justify-center">
            <div class="flex gap-3 pb-2 overflow-x-auto max-w-full">
                @if(count($property->all_images ?? []) > 0)
                    @foreach($property->all_images as $index => $image)
                    <div class="cursor-pointer flex-shrink-0" @click="goToModalSlide({{ $index }})">
                        <div class="w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                             :class="modalCurrentSlide === {{ $index }} ? 'border-white shadow-lg' : 'border-white/50'">
                            <img src="{{ $image['url'] }}"
                                 alt="Thumbnail {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function imageGallery() {
    return {
        // Main swiper instances
        mainSwiper: null,
        modalSwiper: null,

        // State
        currentSlide: 0,
        modalCurrentSlide: 0,
        totalSlides: {{ count($property->all_images ?? []) }},
        galleryModalOpen: false,
        showNavigation: false,

        // Settings for WhatsApp (keeping existing functionality)
        settings: {},
        whatsappLink: '',

        init() {
            this.initSwipers();
            this.loadSettings();
        },

        initSwipers() {
            // Main swiper
            this.mainSwiper = new Swiper('.main-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: this.totalSlides > 1,
                autoplay: false, // Disabled autoplay for manual control
                navigation: {
                    nextEl: '.swiper-button-next-custom',
                    prevEl: '.swiper-button-prev-custom',
                },
                on: {
                    slideChange: (swiper) => {
                        this.currentSlide = swiper.realIndex;
                    },
                    init: () => {
                        this.showNavigation = true;
                    }
                }
            });
        },

        initModalSwipers() {
            // Modal main swiper (fullscreen)
            this.modalSwiper = new Swiper('.modal-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: this.totalSlides > 1,
                keyboard: {
                    enabled: true,
                    onlyInViewport: false,
                },
                on: {
                    slideChange: (swiper) => {
                        this.modalCurrentSlide = swiper.realIndex;
                    }
                }
            });
        },

        goToSlide(index) {
            if (this.mainSwiper) {
                this.mainSwiper.slideTo(index);
            }
        },

        goToModalSlide(index) {
            if (this.modalSwiper) {
                this.modalSwiper.slideTo(index);
            } else {
                console.warn('Modal swiper not initialized');
            }
        },

        openGalleryModal() {
            this.galleryModalOpen = true;
            this.modalCurrentSlide = this.currentSlide;
            setTimeout(() => {
                this.initModalSwipers();
                this.modalSwiper.slideTo(this.currentSlide);
            }, 200);
        },

        closeGalleryModal() {
            this.galleryModalOpen = false;
            // Destroy modal swiper to prevent conflicts
            if (this.modalSwiper) {
                this.modalSwiper.destroy();
                this.modalSwiper = null;
            }
        },

        // Existing WhatsApp functionality
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
            return '{{ $property->location ?? "Location not specified" }}';
        },

        getPropertyId() {
            return '{{ $property->id ?? 1 }}';
        }
    }
}
</script>

</body>
</html>