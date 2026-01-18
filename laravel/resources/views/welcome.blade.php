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
<x-public.site-header />
<livewire:public.hero />
<section class="px-6 lg:px-10 py-10 pb-2 sticky top-[71px] z-40 bg-white shadow-sm border-b border-gray-100 pt-3">
<div class="mb-3">
<div class="flex gap-3">

<x-public.advanced-filters
    data-on-apply="function(filters) {console.log('Applying  filters:', filters); }"
    data-on-reset="function() {console.log('Resetting filters'); }"
>
</x-public.advanced-filters>
 
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

<x-public.listing />
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
        },
        
        getWhatsAppMessage() {
            const domain = window.location.origin.replace(/^https?:\/\//, '');
            const message = `Hii i'm interested in\n*${this.settings.agency_name || 'Elite Homes'}*\nat ${this.settings.office_address || 'Location not specified'}\nUID: N/A\nLink: ${domain}`;
            const encodedMessage = encodeURIComponent(message);
            return `${this.whatsappLink}?text=${encodedMessage}`;
        },
        
        getCleanedPhoneNumber() {
            const phone = this.settings.w_no || '';
            // Keep only digits and + sign
            return phone.replace(/[^\d+]/g, '');
        }
    }
}
</script>

</body>
</html>
