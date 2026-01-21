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
                        class="p-1.5 rounded transition-all {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}"
                        title="Grid View">
                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                </button>
                <button wire:click="$set('viewMode', 'list')"
                        class="p-1.5 rounded transition-all {{ $viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}"
                        title="List View">
                    <span class="material-symbols-outlined text-[22px]">view_list</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Property List -->
    @if($this->properties->count() > 0)
        <div class="mt-8">
            <!-- List View -->
            @if($viewMode === 'list')
                <div class="flex flex-col gap-4">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-list-card', ['property' => $property], key('list-' . $property->id))
                    @endforeach
                </div>
            @endif

            <!-- Grid View -->
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-grid-card', ['property' => $property], key('grid-' . $property->id))
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- No Results Message -->
    @if($this->properties->count() === 0)
        <div class="text-center py-12 mt-8">
            <div class="flex flex-col items-center gap-4">
                <span class="material-symbols-outlined text-6xl text-gray-300">search_off</span>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">No properties found</h3>
                    <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    @if($this->properties->count() > 0)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4">
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
            <span class="text-sm text-gray-500">{{ $this->showingCount }} properties found</span>
        </div>

        <!-- Pagination controls -->
        <div class="flex items-center gap-2">
            <button wire:click="prevPage"
                    @disabled($this->properties->onFirstPage())
                    class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg transition-colors {{ $this->properties->onFirstPage() ? 'text-gray-400 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50' }}">
                Previous
            </button>

            <!-- Page numbers -->
            <div class="flex items-center gap-1">
                @for ($page = 1; $page <= $this->totalPages; $page++)
                    <button wire:click="goToPage({{ $page }})"
                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors {{ $this->properties->currentPage() === $page ? 'bg-royal-blue text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $page }}
                    </button>
                @endfor
            </div>

            <button wire:click="nextPage"
                    @disabled(!$this->properties->hasMorePages())
                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg transition-colors {{ !$this->properties->hasMorePages() ? 'text-gray-400 cursor-not-allowed' : 'text-white bg-royal-blue hover:bg-blue-800' }}">
                Next
            </button>
        </div>
    </div>
    @endif

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