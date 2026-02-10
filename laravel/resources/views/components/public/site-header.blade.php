<header x-data="headerData()" x-init="init()" class="sticky top-0 z-40">
    <!-- Header Background Container -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-200">
        <!-- Desktop Header Grid -->
        <div class="px-4 sm:px-6 lg:px-10 h-16 grid grid-cols-[auto,1fr,auto] items-center gap-4">
            <a href="{{ url('/') }}" class="col-start-1 flex items-center gap-4 hover:opacity-80 transition-opacity xl:hidden">
                <div class="h-10 w-10 rounded-full bg-cover bg-center border border-gray-100" :data-alt="settings.agency_name + ' company logo avatar'" :style="settings.logo_url ? 'background-image: url(' + settings.logo_url + ')' : 'background-image: url(\'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz\');'"></div>
                <h1 class="text-xl font-bold text-[#121317] tracking-tight" x-text="settings.agency_name || 'Loading...'"></h1>
            </a>
            
            <!-- Middle column - Search bar (visible on sm+ screens) -->
            <div class="col-start-2 hidden sm:block">
                <div class="relative group max-w-xl mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 group-focus-within:text-primary">search</span>
                    </div>
                    <input 
                        class="block w-full pl-10 pr-3 py-2 border-none rounded-full leading-5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-gray-600 sm:text-sm transition-all duration-200" 
                        placeholder="Search by ID, location, title, price..." 
                        type="text"
                    />
                </div>
            </div>
            
            <!-- Right column - buttons -->
            <div class="col-start-3 flex gap-2 sm:gap-3 justify-end">
                <!-- Search Toggle Button (mobile only) -->
                <button @click="mobileSearchOpen = !mobileSearchOpen" class="flex items-center justify-center h-10 w-10 text-[#121317] hover:bg-gray-100 transition-colors sm:hidden">
                    <span class="material-symbols-outlined text-[20px]" x-text="mobileSearchOpen ? 'close' : 'search'"></span>
                </button>
                
                <button @click="sharePage()" class="flex items-center justify-center gap-2 h-10 px-3 sm:px-4 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors font-medium text-sm">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
                    <span class="hidden sm:inline">Share</span>
                </button>
                <button @click="$dispatch('toggle-mobile-sidebar')" class="flex items-center justify-center h-10 w-10 text-[#121317] hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bars text-[18px] sm:text-[20px]"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Search Bar (collapsible, visible only on mobile when search button clicked) -->
        <div x-show="mobileSearchOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="sm:hidden border-t border-gray-200 dark:border-gray-700 py-3 px-4"
             style="display: none;">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400">search</span>
                </div>
                <input 
                    class="block w-full pl-10 pr-20 py-2.5 border border-gray-300 dark:border-gray-600 rounded-full leading-5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Search by ID, location, title, price..." 
                    type="text"
                    @keydown.escape="mobileSearchOpen = false"
                />
                <button class="absolute inset-y-1.5 right-1.5 px-4 bg-primary text-white rounded-full text-sm font-medium hover:bg-blue-800 transition-colors">
                    Search
                </button>
            </div>
        </div>
        
        <!-- Share Notification -->
        <div x-show="showNotification" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="fixed top-20 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg font-medium">
            Link copied!
        </div>
    </div>

    <!-- <header class="sticky top-0 z-50 bg-surface-light shadow-sm border-b border-gray-200">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center h-16">
<div class="flex items-center">
<div class="flex-shrink-0 flex items-center gap-3">
<div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
<span class="font-bold text-primary text-sm">BB</span>
</div>
<span class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">BrokerBase</span>
</div>
</div>
<div class="flex-1 max-w-2xl mx-auto px-4 hidden sm:block">
<div class="relative group">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-icons text-gray-400 group-focus-within:text-primary">search</span>
</div>
<input class="block w-full pl-10 pr-3 py-2 border-none rounded-full leading-5 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-gray-900 sm:text-sm transition-all duration-200" placeholder="Search by ID, location, title, price..." type="text"/>
</div>
</div>
<div class="hidden md:flex items-center space-x-4">
<button @click="sharePage()" class="flex items-center justify-center gap-2 h-10 px-3 sm:px-4 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors font-medium text-sm">
            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
            <span class="hidden sm:inline">Share</span>
        </button>
<button class="p-2 text-gray-500 hover:text-gray-700">
<span class="material-symbols-outlined">menu</span>
</button>
</div>
<div class="flex items-center md:hidden">
<button class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
<span class="material-symbols-outlined">menu</span>
</button>
</div>
</div>
</div>
<div class="border-t border-gray-200 bg-white py-3 px-4 shadow-inner sm:hidden">
<div class="max-w-3xl mx-auto relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-symbols-outlined text-gray-400">search</span>
</div>
<input class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-full leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out text-gray-900" placeholder="Search by ID, location, title, price or property type..." type="text"/>
<button class="absolute inset-y-1 right-1 px-4 bg-primary text-white rounded-full text-sm font-medium hover:bg-blue-800 transition-colors">
                    Search
                </button>
</div>
</div>
</header>
-->
    <script>
function headerData() {
    return {
        settings: {},
        showNotification: false,
        mobileSearchOpen: false,
        
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
        },
        
        sharePage() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.showNotification = true;
                setTimeout(() => this.showNotification = false, 3000);
            });
        }
    }
}
</script>
</header>
