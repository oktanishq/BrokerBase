<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-[#f1f1f4] px-4 sm:px-6 lg:px-10 py-3 sm:py-4 flex items-center justify-between transition-all duration-200"
        x-data="headerData()"
        x-init="init()">
    <a href="{{ url('/') }}" class="flex items-center gap-4 hover:opacity-80 transition-opacity">
        <div class="h-12 w-12 rounded-full bg-cover bg-center border border-gray-100" :data-alt="settings.agency_name + ' company logo avatar'" :style="settings.logo_url ? 'background-image: url(' + settings.logo_url + ')' : 'background-image: url(\'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz\');'"></div>
        <h1 class="text-xl font-bold text-[#121317] tracking-tight" x-text="settings.agency_name || 'Loading...'"></h1>
    </a>
    <div class="flex gap-2 sm:gap-3">
        <button @click="sharePage()" class="flex items-center justify-center gap-2 h-10 px-3 sm:px-4 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors font-medium text-sm">
            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
            <span class="hidden sm:inline">Share</span>
        </button>
        <button class="flex items-center justify-center h-10 w-10 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors">
            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">menu</span>
        </button>
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
</header>

<script>
function headerData() {
    return {
        settings: {},
        showNotification: false,
        
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
