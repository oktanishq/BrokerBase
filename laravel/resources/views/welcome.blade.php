<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Elite Homes - Dealer Homepage</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@vite('resources/css/app.css')
<script src="{{ asset('js/alpine-components.js') }}"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#121317] font-display min-h-screen flex flex-col" x-data="welcomeData()" x-init="init()">
<main class="w-full bg-white min-h-screen flex flex-col relative pb-12 mx-auto">
<x-site-header />
<section class="bg-gradient-to-b from-white to-[#f6f6f8] px-6 lg:px-10 py-10">
<div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-10 items-start">
<div class="flex flex-col gap-8">
<div class="flex items-start gap-6">
<div class="h-28 w-28 lg:h-32 lg:w-32 shrink-0 rounded-full bg-cover bg-center border-4 border-white shadow-lg ring-1 ring-gray-100" :data-alt="settings.agency_name + ' company logo avatar'" :style="settings.logo_url ? 'background-image: url(' + settings.logo_url + ')' : 'background-image: url(\'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz\');'"></div>
<div class="flex flex-col gap-2 pt-2">
<div class="flex flex-wrap items-center gap-3">
<h2 class="text-3xl font-bold text-[#121317]" x-text="settings.agency_name || 'Loading...'"></h2>
<span x-show="settings.rera_id && settings.rera_id.trim() !== ''" class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide border border-green-200">RERA Registered</span>
</div>
<div class="flex items-center gap-2 text-[#666e85] text-base">
<span class="material-symbols-outlined text-[20px] text-primary">location_on</span>
<p x-text="settings.office_address || 'Loading location...'"></p>
</div>
<p x-show="settings.rera_id && settings.rera_id.trim() !== ''" class="text-[#666e85] text-sm font-medium">RERA ID: <span x-text="settings.rera_id || 'Loading...'"></span></p>
</div>
</div>
<div class="flex gap-6 mt-4">
<div class="bg-white rounded-xl p-4 flex flex-col items-center shadow-sm border border-[#eef0f3]">
<span class="text-2xl font-bold text-primary">12</span>
<span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Active Listings</span>
</div>
<div class="bg-white rounded-xl p-4 flex flex-col items-center shadow-sm border border-[#eef0f3]">
<span class="text-2xl font-bold text-primary">50+</span>
<span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Properties Sold</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 h-full min-h-[220px] md:min-h-0">
<div class="relative flex-1 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100 min-h-[160px]">
<iframe allowfullscreen="" class="absolute inset-0 w-full h-full border-0 grayscale-[20%] opacity-90 hover:opacity-100 transition-opacity" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1839487053644!2d-73.98773128459413!3d40.75890017932676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1633023222533!5m2!1sen!2sus">
</iframe>
<div class="absolute bottom-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-[10px] font-bold shadow-sm pointer-events-none text-gray-600">
                        Locate Us
                    </div>
</div>
<div class="flex gap-3">
<button class="flex-1 h-12 bg-primary hover:bg-blue-800 text-white font-bold rounded-xl shadow-md shadow-blue-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
<span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">call</span>
                        Call Dealer
                    </button>
<a :href="whatsappLink" target="_blank" rel="noopener noreferrer" class="flex-1 h-12 bg-whatsapp hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-green-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
<i class="fa-brands fa-whatsapp text-[20px] group-hover:scale-110 transition-transform"></i>
                        WhatsApp
                    </a>
</div>
</div>
</div>
</section>
<section class="px-6 lg:px-10 py-10 pb-2 sticky top-[71px] z-40 bg-white shadow-sm border-b border-gray-100 pt-3">
<div class="mb-3">
<div class="flex gap-3">
@include('public_components.advanced-filters', [
    'onApply' => 'function(filters) { console.log("Applying filters:", filters); }',
    'onReset' => 'function() { console.log("Resetting filters"); }'
])
<label class="relative flex-1 items-center">
<span class="absolute left-4 text-[#666e85] material-symbols-outlined top-1/2 -translate-y-1/2">search</span>
<input class="w-full bg-[#f1f1f4] text-[#121317] placeholder:text-[#666e85] h-12 rounded-full pl-20 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 border-none text-base transition-all" placeholder="Search location or building..." type="text"/>
</label>
</div>
</div>
<div class="flex flex-col gap-3">
<div class="flex gap-2 overflow-x-auto no-scrollbar mask-linear-fade pb-2">
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-primary text-white text-sm font-semibold shadow-md shadow-blue-900/10 flex-shrink-0">
                All
            </button>
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors flex-shrink-0">
                For Sale
            </button>
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors flex-shrink-0">
                For Rent
            </button>
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors flex-shrink-0">
                2 BHK
            </button>
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors flex-shrink-0">
                3 BHK
            </button>
<button class="whitespace-nowrap h-9 px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors flex-shrink-0">
                Commercial
            </button>
</div>
</div>

</section>
@include('public_components.listing')
<div class="fixed bottom-6 right-6 z-10 lg:hidden">
<button class="group flex items-center gap-2 bg-primary text-white h-14 pl-5 pr-6 rounded-full shadow-xl shadow-blue-900/30 hover:scale-105 hover:bg-blue-800 transition-all duration-300">
<span class="material-symbols-outlined text-[24px]">call</span>
<span class="font-bold text-base">Contact Dealer</span>
</button>
</div>
</main>
@stack('scripts')

<script>
function welcomeData() {
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
            // Remove all non-digit characters
            const digits = phone.replace(/\D/g, '');
            this.whatsappLink = 'https://wa.me/' + digits;
        }
    }
}
</script>

</body>
</html>
