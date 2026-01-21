<div>
    <!-- Edit Property Modal -->
    @livewire('admin.inventory.edit-property-modal')

    <!-- Delete Confirmation Modal -->
    @livewire('admin.inventory.delete-confirmation-modal')

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">My Inventory</h1>
        <x-admin.AddPropertyModal />
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between sticky top-0 z-10 mb-8">
        <!-- Search Input -->
        <div class="relative w-full md:w-96 group">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <span class="material-symbols-outlined">search</span>
            </span>
            <input wire:model.live.debounce.300ms="searchTerm" class="w-full py-2.5 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                   placeholder="Search by title, location..." type="text"/>
        </div>

        <!-- Filters and View Toggle -->
        <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-3 justify-between md:justify-end">
            <div class="flex gap-3 w-full sm:w-auto">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="w-full sm:w-auto py-2.5 px-4 pr-8 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="all">Status: All</option>
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="draft">Draft</option>
                </select>

                <!-- Type Filter -->
                <select wire:model.live="typeFilter" class="w-full sm:w-auto py-2.5 px-4 pr-8 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="all">Type: All</option>
                    <option value="apartment">Apartment</option>
                    <option value="villa">Villa</option>
                    <option value="office">Office</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>

            <!-- View Toggle -->
            <div class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200 ml-auto sm:ml-0">
                <button wire:click="$set('viewMode', 'grid')"
                        :class="$viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                        class="p-1.5 rounded transition-all"
                        title="Grid View">
                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                </button>
                <button wire:click="$set('viewMode', 'list')"
                        :class="$viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600'"
                        class="p-1.5 rounded transition-all"
                        title="List View">
                    <span class="material-symbols-outlined text-[22px]">view_list</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="mt-8">
        <div class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-royal-blue"></div>
            <p class="mt-4 text-gray-500">Loading properties...</p>
        </div>
    </div>

    <!-- Error State -->
    <div wire:loading.remove x-show="$error" class="mt-8">
        <div class="flex flex-col items-center justify-center py-12">
            <span class="material-symbols-outlined text-6xl text-red-300">error</span>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900">Error loading properties</h3>
                <p class="text-gray-500">{{ $error }}</p>
                <button wire:click="$refresh" class="mt-4 px-4 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors">
                    Try Again
                </button>
            </div>
        </div>
    </div>

    <!-- Property List -->
    <div wire:loading.remove x-show="$properties->count() > 0" class="mt-8">
        <!-- List View -->
        <div x-show="$viewMode === 'list'" class="flex flex-col gap-4">
            @foreach($properties as $property)
                @livewire('admin.inventory.property-list-card', ['property' => $property], key('list-' . $property->id))
            @endforeach
        </div>

        <!-- Grid View -->
        <div x-show="$viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
                @livewire('admin.inventory.property-grid-card', ['property' => $property], key('grid-' . $property->id))
            @endforeach
        </div>
    </div>

    <!-- No Results Message -->
    <div wire:loading.remove x-show="$properties->count() === 0" class="text-center py-12 mt-8">
        <div class="flex flex-col items-center gap-4">
            <span class="material-symbols-outlined text-6xl text-gray-300">search_off</span>
            <div>
                <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                <p class="text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div wire:loading.remove x-show="$properties->count() > 0" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4">
        <div class="flex items-center gap-4">
            <!-- Items per page dropdown -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-500">Show:</label>
                <select wire:model.live="perPage" class="py-1 px-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">per page</span>
            </div>

            <!-- Results info -->
            <span class="text-sm text-gray-500">{{ $showingCount }} properties found</span>
        </div>

        <!-- Pagination controls -->
        <div class="flex items-center gap-2">
            <button wire:click="prevPage"
                    :disabled="$properties->onFirstPage()"
                    :class="$properties->onFirstPage() ? 'text-gray-400 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg transition-colors">
                Previous
            </button>

            <!-- Page numbers -->
            <div class="flex items-center gap-1">
                @for ($page = 1; $page <= $totalPages; $page++)
                    <button wire:click="goToPage({{ $page }})"
                            :class="$properties->currentPage() === {{ $page }} ? 'bg-royal-blue text-white' : 'text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors">
                        {{ $page }}
                    </button>
                @endfor
            </div>

            <button wire:click="nextPage"
                    :disabled="$properties->hasMorePages()"
                    :class="!$properties->hasMorePages() ? 'text-gray-400 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800'"
                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors">
                Next
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all bg-green-500">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all bg-red-500">
            {{ session('error') }}
        </div>
    @endif
</div>