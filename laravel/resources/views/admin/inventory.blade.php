@extends('layouts.admin')

@section('title', 'BrokerBase Dealer Inventory - List View')

@section('head')
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
@endsection

@section('header-content')
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
@endsection

@section('content')
<!-- Edit Property Modal -->
@include('components.EditPropertyModal')

<div x-data="{
    searchTerm: '',
    statusFilter: 'all',
    typeFilter: 'all',
    viewMode: 'list', // 'list' or 'grid'
    perPage: 10, // Items per page
    currentPage: 1, // Current page number
    loading: false,
    error: null,

    // Real properties data from API
    properties: [],
    
    // API base URL
    apiUrl: '/api/admin/properties',

    // Initialize data
    async init() {
        await this.fetchProperties();
        
        // Listen for property updates from edit modal
        this.$root.addEventListener('property-updated', (event) => {
            this.handlePropertyUpdate(event.detail);
        });
    },
    
    // Handle property update from modal
    handlePropertyUpdate(detail) {
        const { propertyId, data } = detail;
        
        // Update the property in the current list
        const propertyIndex = this.properties.findIndex(p => p.id === propertyId);
        if (propertyIndex !== -1) {
            this.properties[propertyIndex] = { ...this.properties[propertyIndex], ...data };
        }
        
        // Show success message
        this.showNotification('Property updated successfully!', 'success');
    },
    
    // Show notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    },

    // Fetch properties from API
    async fetchProperties() {
        this.loading = true;
        this.error = null;
        
        try {
            const params = new URLSearchParams({
                per_page: this.perPage,
                page: this.currentPage,
                ...(this.searchTerm && { search: this.searchTerm }),
                ...(this.statusFilter !== 'all' && { status: this.statusFilter }),
                ...(this.typeFilter !== 'all' && { type: this.typeFilter })
            });
            
            const response = await fetch(`${this.apiUrl}?${params}`);
            const data = await response.json();
            
            if (data.success) {
                this.properties = data.data;
            } else {
                this.error = 'Failed to load properties';
                this.properties = [];
            }
        } catch (error) {
            console.error('Error fetching properties:', error);
            this.error = 'Network error occurred';
            this.properties = [];
        } finally {
            this.loading = false;
        }
    },

    // Watch for filter changes
    watch: {
        searchTerm() {
            this.currentPage = 1;
            this.fetchProperties();
        },
        statusFilter() {
            this.currentPage = 1;
            this.fetchProperties();
        },
        typeFilter() {
            this.currentPage = 1;
            this.fetchProperties();
        },
        perPage() {
            this.currentPage = 1;
            this.fetchProperties();
        },
        currentPage() {
            this.fetchProperties();
        }
    },

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
}">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                    <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">My Inventory</h1>
                    @include('components.AddPropertyModal')
                </div>

                <!-- Search and Filter Bar -->
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between sticky top-0 z-10 mb-8">
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
                                <option value="apartment">Apartment</option>
                                <option value="villa">Villa</option>
                                <option value="office">Office</option>
                                <option value="commercial">Commercial</option>
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

                <!-- Loading State -->
                <div x-show="loading" x-transition class="mt-8">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-royal-blue"></div>
                        <p class="mt-4 text-gray-500">Loading properties...</p>
                    </div>
                </div>

                <!-- Error State -->
                <div x-show="error && !loading" x-transition class="mt-8">
                    <div class="flex flex-col items-center justify-center py-12">
                        <span class="material-symbols-outlined text-6xl text-red-300">error</span>
                        <div class="mt-4 text-center">
                            <h3 class="text-lg font-medium text-gray-900">Error loading properties</h3>
                            <p class="text-gray-500" x-text="error"></p>
                            <button @click="fetchProperties" class="mt-4 px-4 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors">
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Property List -->
                <div x-show="!loading && !error && properties.length > 0" x-transition class="mt-8">
                    <!-- List View -->
                    <div x-show="viewMode === 'list'" class="flex flex-col gap-4">
                        <template x-for="property in properties" :key="`list-${property.id}`">
                            @include('components.PropertyListCard')
                        </template>
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="property in properties" :key="`grid-${property.id}`">
                            @include('components.PropertyGridCard')
                        </template>
                    </div>
                </div>

                <!-- No Results Message -->
                <div class="text-center py-12" x-show="!loading && !error && properties.length === 0" x-transition class="mt-8">
                    <div class="flex flex-col items-center gap-4">
                        <span class="material-symbols-outlined text-6xl text-gray-300">search_off</span>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                            <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4" x-show="!loading && !error && properties.length > 0" x-transition>
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
                        <span class="text-sm text-gray-500" x-text="`${properties.length} properties found`"></span>
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
@endsection