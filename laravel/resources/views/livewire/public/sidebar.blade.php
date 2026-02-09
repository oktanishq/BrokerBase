<div x-data="{ mobileOpen: false }" @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen">
    <!-- Desktop Sidebar (xl and up) -->
    <aside class="hidden xl:flex w-64 bg-primary text-white flex-col fixed h-full z-30 shadow-xl">
        <!-- Logo & Branding -->
        <div class="p-6 flex items-center space-x-3 border-b border-blue-800">
            <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-primary font-bold text-xl overflow-hidden">
                @if(!empty($settings['logo_url']))
                    <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['agency_name'] ?? 'Logo' }}" class="w-full h-full object-cover">
                @else
                    {{ substr($settings['agency_name'] ?? 'BB', 0, 2) }}
                @endif
            </div>
            <span class="font-bold text-xl tracking-wide">{{ $settings['agency_name'] ?? 'BrokerBase' }}</span>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <a class="flex items-center space-x-3 px-4 py-3 bg-blue-800 rounded-lg text-white font-medium" href="#">
                <span class="material-icons">dashboard</span>
                <span>All Listings</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">villa</span>
                <span>Villas</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">apartment</span>
                <span>Apartments</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">landscape</span>
                <span>Plots</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">storefront</span>
                <span>Commercial</span>
            </a>
            <div class="border-t border-blue-800 my-4 pt-4">
                <div class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Available</div>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">key</span>
                    <span>For Rent</span>
                </a>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">sell</span>
                    <span>For Sale</span>
                </a>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">assignment</span>
                    <span>For Lease</span>
                </a>
            </div>
        </nav>
        
        <!-- Agent Contact -->
        <div class="p-4 border-t border-blue-800">
            <div class="bg-blue-800 rounded-xl p-3 flex items-center space-x-3">
                @if(!empty($settings['logo_url']))
                    <img alt="Agent" class="h-10 w-10 rounded-full border-2 border-white object-cover" src="{{ $settings['logo_url'] }}">
                @else
                    <div class="h-10 w-10 rounded-full border-2 border-white flex items-center justify-center bg-primary text-white font-bold">
                        {{ substr($settings['agency_name'] ?? 'BB', 0, 2) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $settings['agency_name'] ?? 'BrokerBase' }}</p>
                    <p class="text-xs text-blue-200 truncate">{{ $settings['w_no'] ?? '+971 4 000 0000' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="mobileOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 z-45 xl:hidden"
        style="display: none;">
    </div>

    <!-- Mobile Sidebar Panel -->
    <div 
        x-show="mobileOpen"
        x-transition:enter="transform transition-transform ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition-transform ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 w-72 bg-primary text-white z-50 xl:hidden transform transition-transform duration-300"
        style="display: none;">
        
        <!-- Mobile Header -->
        <div class="flex items-center justify-between p-4 border-b border-blue-800">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-primary font-bold text-xl overflow-hidden">
                    @if(!empty($settings['logo_url']))
                        <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['agency_name'] ?? 'Logo' }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($settings['agency_name'] ?? 'BB', 0, 2) }}
                    @endif
                </div>
                <span class="font-bold text-lg tracking-wide">{{ $settings['agency_name'] ?? 'BrokerBase' }}</span>
            </div>
            <button @click="mobileOpen = false" class="p-2 hover:bg-blue-800 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a class="flex items-center space-x-3 px-4 py-3 bg-blue-800 rounded-lg text-white font-medium" href="#">
                <span class="material-icons">dashboard</span>
                <span>All Listings</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">villa</span>
                <span>Villas</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">apartment</span>
                <span>Apartments</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">landscape</span>
                <span>Plots</span>
            </a>
            <a class="flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors" href="#">
                <span class="material-icons">storefront</span>
                <span>Commercial</span>
            </a>
            <div class="border-t border-blue-800 my-4 pt-4">
                <div class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2">Available</div>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">key</span>
                    <span>For Rent</span>
                </a>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">sell</span>
                    <span>For Sale</span>
                </a>
                <a class="flex items-center space-x-3 px-4 py-2 hover:bg-blue-800 rounded-lg text-blue-100 hover:text-white transition-colors text-sm" href="#">
                    <span class="material-icons text-sm">assignment</span>
                    <span>For Lease</span>
                </a>
            </div>
        </nav>
        
        <!-- Mobile Agent Contact -->
        <div class="p-4 border-t border-blue-800">
            <div class="bg-blue-800 rounded-xl p-3 flex items-center space-x-3">
                @if(!empty($settings['logo_url']))
                    <img alt="Agent" class="h-10 w-10 rounded-full border-2 border-white object-cover" src="{{ $settings['logo_url'] }}">
                @else
                    <div class="h-10 w-10 rounded-full border-2 border-white flex items-center justify-center bg-primary text-white font-bold">
                        {{ substr($settings['agency_name'] ?? 'BB', 0, 2) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $settings['agency_name'] ?? 'BrokerBase' }}</p>
                    <p class="text-xs text-blue-200 truncate">{{ $settings['w_no'] ?? '+971 4 000 0000' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
