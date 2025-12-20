<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>BrokerBase Dealer Inventory - List View</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#f59e0b", // Amber-500
                        "background-light": "#f9fafb", // gray-50
                        "background-dark": "#23220f",
                        "royal-blue": "#1e3a8a", // blue-900
                    },
                    fontFamily: {
                        "display": ["Spline Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 overflow-hidden">
<div x-data="{
    searchTerm: '',
    statusFilter: 'all',
    typeFilter: 'all',
    viewMode: 'list', // 'list' or 'grid'
    perPage: 10, // Items per page
    currentPage: 1, // Current page number
    
    // Sample properties data (will be replaced with real data later)
    properties: [
        {
            id: 1,
            title: 'Sunset Villa - Luxury Oceanfront Estate',
            price: '$ 450,000',
            location: 'Business Bay, Dubai',
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f',
            views: 124,
            bedrooms: 3,
            bathrooms: 2,
            sqft: 1500,
            type: 'Villa',
            status: 'available',
            added_ago: '2 days ago'
        },
        {
            id: 2,
            title: 'City Apartment - Modern Downtown Living',
            price: '$ 850,000',
            location: 'Downtown, Seattle',
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDdhjV4Kk0pc9BPnmOBfpnmUfWfZXgnJiKxKd0fBo3FkvF2Dw1_m-VUSfwR9RXPR9Mh_K6UP3m5MdbMtnevhMoJrYW9VcXQ0KkJcc3q3jt0T-8ADSIPTYb-T_SxL4I8HInHQ0ngU4p9h80Do3Ac7mXpX37jGdGqg4KkyMS2oZunrbaz8rrealQBNZd2sfkiIoKIxqvnAwuxwKG3267dMdaaTT_4aAlFfUdds9vqolu76zP8xpGfu04YE81DWahgI_ejytJbVjAk6OzQ',
            views: 892,
            bedrooms: 2,
            bathrooms: 2,
            sqft: 980,
            type: 'Apartment',
            status: 'sold',
            sold_ago: '1 week ago'
        },
        {
            id: 3,
            title: 'Commercial Office Space',
            price: 'Price TBD',
            location: 'Financial District, NY',
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZQ1h5gajMn8U5gGwfOrWCKVWL7VHXHJMq0MaaOt3B4MeV4ipBbWSs9RTsUuAI1-ham_5nr2n1CCLr8y2VytmK2bnMk6UtMpwgnaKYudsto8BfD0Rw-lthz_UV9QwKDGCl-XQvQdaG7SRB1m0buhSHP-2QwyGiNpYEPuM4DQT1n2Bl38LpdnTnI93zhCbHG3wdijJZHS5w6wGDhSFixvqA-BUADXJAXbuAgzh5Zh9TEcNynTBErL21Wg0ZtPi4yxDvhU2UhSbeOUWo',
            views: 0,
            bedrooms: null,
            bathrooms: 4,
            sqft: 3200,
            type: 'Office',
            status: 'draft',
            edited_ago: 'today'
        }
    ],
    
    // Computed filtered properties
    get filteredProperties() {
        return this.properties.filter(property => {
            const matchesSearch = property.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                property.location.toLowerCase().includes(this.searchTerm.toLowerCase());
            
            const matchesStatus = this.statusFilter === 'all' || property.status === this.statusFilter;
            const matchesType = this.typeFilter === 'all' || property.type === this.typeFilter;
            
            return matchesSearch && matchesStatus && matchesType;
        });
    },
    
    // Paginated properties
    get paginatedProperties() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.filteredProperties.slice(start, end);
    },
    
    // Pagination info
    get totalPages() {
        return Math.ceil(this.filteredProperties.length / this.perPage);
    },
    
    get showingFrom() {
        if (this.filteredProperties.length === 0) return 0;
        return (this.currentPage - 1) * this.perPage + 1;
    },
    
    get showingTo() {
        return Math.min(this.currentPage * this.perPage, this.filteredProperties.length);
    },
    
    get showingCount() {
        return this.filteredProperties.length;
    },
    
    get totalCount() {
        return this.properties.length;
    },
    
    // Pagination controls
    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
        }
    },
    
    prevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
        }
    },
    
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
        }
    }
}" class="flex h-screen w-full bg-background-light">
    <!-- Sidebar -->
    @include('components.sidebar')
    
    <!-- Main Content -->
    <div class="flex flex-col flex-1 h-full lg:ml-64 relative overflow-hidden bg-gray-50">
        <!-- Header -->
        <header class="flex items-center justify-between bg-white border-b border-gray-100 px-6 py-4 shadow-sm sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="flex flex-col">
                    <nav aria-label="Breadcrumb" class="hidden sm:flex">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
                            <li class="inline-flex items-center">
                                <a class="hover:text-royal-blue transition-colors" href="{{ url('/admin/dashboard') }}">Home</a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                                    <span class="ml-1 font-medium text-gray-700">Properties</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="flex items-center justify-center size-10 rounded-full bg-gray-50 hover:bg-gray-200 text-slate-700 transition-colors relative">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 ring-2 ring-gray-100 cursor-pointer" 
                     data-alt="Real estate agency profile logo showing abstract building" 
                     style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo');">
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto flex flex-col gap-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">My Inventory</h1>
                    @include('components.AddPropertyModal')
                </div>

                <!-- Search and Filter Bar -->
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between sticky top-0 z-10">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                        <input x-model="searchTerm" class="w-full py-2.5 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                               placeholder="Search by title, location..." type="text"/>
                    </div>

                    <!-- Filters and View Toggle -->
                    <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-3 justify-between md:justify-end">
                        <div class="flex gap-3 w-full sm:w-auto">
                            <!-- Status Filter -->
                            <select x-model="statusFilter" class="w-full sm:w-auto py-2.5 px-4 pr-8 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="all">Status: All</option>
                                <option value="available">Available</option>
                                <option value="sold">Sold</option>
                                <option value="draft">Draft</option>
                            </select>
                            
                            <!-- Type Filter -->
                            <select x-model="typeFilter" class="w-full sm:w-auto py-2.5 px-4 pr-8 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="all">Type: All</option>
                                <option value="Apartment">Apartment</option>
                                <option value="Villa">Villa</option>
                                <option value="Office">Office</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                        </div>

                        <!-- View Toggle -->
                        <div class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200 ml-auto sm:ml-0">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                                    class="p-1.5 rounded transition-all" 
                                    title="Grid View">
                                <span class="material-symbols-outlined text-[22px]">grid_view</span>
                            </button>
                            <button @click="viewMode = 'list'" 
                                    :class="viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                                    class="p-1.5 rounded transition-all" 
                                    title="List View">
                                <span class="material-symbols-outlined text-[22px]">view_list</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Property List -->
                <div x-show="filteredProperties.length > 0" x-transition>
                    <!-- List View -->
                    <div x-show="viewMode === 'list'" class="flex flex-col gap-4">
                        <template x-for="property in paginatedProperties" :key="`list-${property.id}`">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col md:flex-row w-full" 
                                 :class="property.status === 'sold' ? 'opacity-90 hover:opacity-100' : ''">
                                
                                <!-- Property Image -->
                                <div class="relative w-full md:w-64 lg:w-72 h-48 md:h-auto shrink-0 overflow-hidden">
                                    <div class="w-full h-full bg-cover bg-center" 
                                         :class="property.status === 'sold' ? 'grayscale contrast-125' : ''" 
                                         x-bind:style="`background-image: url('${property.image}')`"
                                         class="group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent md:hidden"></div>
                                    
                                    <!-- View Count -->
                                    <div class="absolute bottom-3 left-3 md:left-auto md:right-3 bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-md flex items-center gap-1.5 text-xs font-medium">
                                        <span class="material-symbols-outlined text-[16px]" x-text="property.views > 0 ? 'visibility' : 'visibility_off'"></span>
                                        <span x-text="property.views"></span>
                                    </div>
                                </div>

                                <!-- Property Details -->
                                <div class="p-5 flex flex-col flex-1 justify-between gap-3">
                                    <div class="flex flex-col gap-1">
                                        <!-- Status and Date -->
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold" 
                                                  :class="{
                                                      'bg-green-100 text-green-700 border-green-200': property.status === 'available',
                                                      'bg-red-100 text-red-700 border-red-200': property.status === 'sold',
                                                      'bg-gray-200 text-gray-600 border-gray-300': property.status === 'draft'
                                                  }"
                                                  x-text="property.status.charAt(0).toUpperCase() + property.status.slice(1)">
                                            </span>
                                            <span class="text-sm text-gray-400 hidden md:block" 
                                                  x-text="property.status === 'sold' ? 
                                                          `Sold ${property.sold_ago || '1 week ago'}` : 
                                                          property.status === 'draft' ? 
                                                          `Last edited ${property.edited_ago || 'today'}` : 
                                                          `Added ${property.added_ago || '2 days ago'}`">
                                            </span>
                                        </div>

                                        <!-- Title and Price -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-1">
                                            <h3 class="text-xl font-bold text-slate-900 leading-tight truncate" 
                                                x-bind:title="property.title"
                                                x-text="property.title">
                                            </h3>
                                            <h3 class="text-xl font-bold" 
                                                :class="{
                                                    'text-amber-600': property.status === 'available',
                                                    'text-gray-400 line-through decoration-red-500 decoration-2': property.status === 'sold',
                                                    'text-gray-400 italic': property.status === 'draft'
                                                }"
                                                x-text="property.price">
                                            </h3>
                                        </div>

                                        <!-- Location -->
                                        <div class="flex items-center gap-1 text-gray-500 text-sm">
                                            <span class="material-symbols-outlined text-[18px] text-red-500">location_on</span>
                                            <span x-text="property.location"></span>
                                        </div>
                                    </div>

                                    <!-- Property Specs -->
                                    <div class="flex items-center flex-wrap gap-4 mt-2 pt-3 border-t border-gray-50">
                                        <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                                            <span class="material-symbols-outlined text-[18px]" 
                                                  x-text="property.type === 'Office' || property.type === 'Commercial' ? 'desk' : 'bed'">
                                            </span>
                                            <span x-text="property.type === 'Office' || property.type === 'Commercial' ? 
                                                           property.type : 
                                                           `${property.bedrooms || 3} Beds`">
                                            </span>
                                        </div>
                                        <div class="w-px h-4 bg-gray-300"></div>
                                        <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                                            <span class="material-symbols-outlined text-[18px]">bathtub</span>
                                            <span x-text="`${property.bathrooms || 2} Baths`"></span>
                                        </div>
                                        <div class="w-px h-4 bg-gray-300"></div>
                                        <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
                                            <span class="material-symbols-outlined text-[18px]">square_foot</span>
                                            <span x-text="`${Number(property.sqft || 1500).toLocaleString()} sqft`"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="px-4 py-3 md:py-0 md:px-6 bg-gray-50 md:bg-transparent md:border-l border-t md:border-t-0 border-gray-100 flex md:flex-col items-center justify-end md:justify-center gap-2 md:w-32 shrink-0">
                                    <!-- Share Button -->
                                    <template x-if="property.status !== 'draft'">
                                        <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Share via WhatsApp">
                                            <span class="material-symbols-outlined text-[20px]">chat</span>
                                        </button>
                                    </template>
                                    <template x-if="property.status === 'draft'">
                                        <button class="flex items-center justify-center size-9 rounded-full text-gray-300 cursor-not-allowed" title="Share disabled">
                                            <span class="material-symbols-outlined text-[20px]">chat</span>
                                        </button>
                                    </template>

                                    <!-- Edit Button -->
                                    <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Property">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>

                                    <!-- More Options / Delete Button -->
                                    <template x-if="property.status === 'draft'">
                                        <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Draft">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </template>
                                    <template x-if="property.status !== 'draft'">
                                        <button class="flex items-center justify-center size-9 rounded-full text-gray-400 hover:text-slate-700 hover:bg-gray-100 transition-colors" title="More Options">
                                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="property in paginatedProperties" :key="`grid-${property.id}`">
                            @include('components.PropertyGridCard')
                        </template>
                    </div>
                </div>

                <!-- No Results Message -->
                <div class="text-center py-12" x-show="filteredProperties.length === 0" x-transition>
                    <div class="flex flex-col items-center gap-4">
                        <span class="material-symbols-outlined text-6xl text-gray-300">search_off</span>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                            <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4" x-show="filteredProperties.length > 0" x-transition>
                    <div class="flex items-center gap-4">
                        <!-- Items per page dropdown -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500">Show:</label>
                            <select x-model="perPage" @change="currentPage = 1" class="py-1 px-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-sm text-gray-500">per page</span>
                        </div>
                        
                        <!-- Results info -->
                        <span class="text-sm text-gray-500" x-text="`Showing ${showingFrom}-${showingTo} of ${showingCount} properties`"></span>
                    </div>
                    
                    <!-- Pagination controls -->
                    <div class="flex items-center gap-2">
                        <button @click="prevPage" 
                                :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
                                class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg transition-colors">
                            Previous
                        </button>
                        
                        <!-- Page numbers -->
                        <div class="flex items-center gap-1">
                            <template x-for="page in totalPages" :key="page">
                                <button @click="goToPage(page)" 
                                        :class="currentPage === page ? 'bg-royal-blue text-white' : 'text-gray-500 hover:bg-gray-50'"
                                        class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors">
                                    <span x-text="page"></span>
                                </button>
                            </template>
                        </div>
                        
                        <button @click="nextPage" 
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800'"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- Global Logout Modal JavaScript --}}
<script>
// Global state object
window.logoutModalState = {
    showLogoutModal: false,
    isLoggingOut: false
};

// Global functions
window.openLogoutModal = function() {
    window.logoutModalState.showLogoutModal = true;
};

window.closeLogoutModal = function() {
    window.logoutModalState.showLogoutModal = false;
};

window.confirmLogout = function() {
    window.logoutModalState.isLoggingOut = true;
};
</script>

{{-- Include Logout Modal Component --}}
<x-logout-confirmation-modal />

</body>
</html>