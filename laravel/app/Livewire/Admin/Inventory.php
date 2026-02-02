<?php

namespace App\Livewire\Admin;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Admin\Inventory\EditPropertyModal;
use App\Livewire\Admin\Inventory\DeleteConfirmationModal;

class Inventory extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $sortBy = 'newest';
    public $viewMode = 'list'; // 'list' or 'grid'
    public $perPage = 10;
    public $editingPropertyId = null;
    public $jumpToPage = null;

    // How many pages to show on each side of current page
    protected $onEachSide = 1;

    protected $listeners = [
        'property-updated' => 'handlePropertyUpdate',
        'property-deleted' => 'handlePropertyDeleted',
        'open-edit-modal' => 'forwardToEditModal',
        'open-delete-modal' => 'forwardToDeleteModal',
    ];

    public function mount()
    {
        // Check if there's a property ID in the query string to edit
        if (request()->has('edit') && request('edit')) {
            $this->editingPropertyId = request('edit');
            $this->openEditModal(request('edit'));
        }
    }

    /**
     * Boot the component and check if current page is valid
     */
    public function boot()
    {
        // Check if we're on a page that no longer exists (e.g., after items were deleted)
        $this->checkPageValidity();
    }

    /**
     * Check if current page is valid and redirect to page 1 if not
     */
    protected function checkPageValidity()
    {
        // Get the current page from the query string
        $currentPage = $this->getPage();
        
        // Calculate total pages based on current filters
        $totalItems = $this->getFilteredTotalCount();
        $totalPages = $totalItems > 0 ? ceil($totalItems / $this->perPage) : 1;
        
        // If current page is greater than total pages, reset to page 1
        if ($currentPage > $totalPages && $currentPage > 1) {
            $this->setPage(1);
        }
    }

    /**
     * Get filtered total count (same logic as getPropertiesProperty but just counting)
     */
    protected function getFilteredTotalCount()
    {
        $query = Property::query();
        
        // Apply same filters as getPropertiesProperty()
        if ($this->searchTerm) {
            $query->where(function ($q) {
                if (is_numeric($this->searchTerm)) {
                    $q->where('id', $this->searchTerm);
                }
                $q->orWhere('title', 'ILIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('address', 'ILIKE', '%' . $this->searchTerm . '%');
            });
        }
        
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->typeFilter !== 'all') {
            $query->where('property_type', $this->typeFilter);
        }
        
        return $query->count();
    }

    public function updatedViewMode($value)
    {
        // Save view mode preference to LocalStorage via JavaScript
        $this->dispatch('viewModeChanged', $value)->to('livewire.admin.inventory');
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Jump to a specific page
     */
    public function updatedJumpToPage($value)
    {
        if ($value && $value >= 1 && $value <= $this->totalPages) {
            $this->setPage($value);
            $this->jumpToPage = null;
        }
    }

    /**
     * Get the pagination window for sliding window pagination
     * Returns an array with 'leftGap', 'pages', 'rightGap' to determine what to show
     */
    public function getPaginationWindowProperty()
    {
        $currentPage = $this->properties->currentPage();
        $totalPages = $this->totalPages;
        $onEachSide = $this->onEachSide;

        // If total pages is small, show all
        if ($totalPages <= 7) {
            return [
                'showAll' => true,
                'pages' => range(1, $totalPages),
            ];
        }

        // Calculate the window around current page
        $windowStart = max(1, $currentPage - $onEachSide);
        $windowEnd = min($totalPages, $currentPage + $onEachSide);

        // Determine if we need ellipsis
        $showLeftEllipsis = $windowStart > 2;
        $showRightEllipsis = $windowEnd < $totalPages - 1;

        // Build pages array
        $pages = [];

        // Always add first page
        $pages[] = 1;

        // Add left ellipsis if needed
        if ($showLeftEllipsis) {
            $pages[] = '...';
        }

        // Add pages in the window
        for ($i = $windowStart; $i <= $windowEnd; $i++) {
            if ($i > 1 && $i < $totalPages) {
                $pages[] = $i;
            }
        }

        // Add right ellipsis if needed
        if ($showRightEllipsis) {
            $pages[] = '...';
        }

        // Always add last page
        if ($totalPages > 1) {
            $pages[] = $totalPages;
        }

        return [
            'showAll' => false,
            'pages' => $pages,
        ];
    }

    public function handlePropertyUpdate($propertyId, $data)
    {
        // Update the specific property in the current page's collection
        $this->properties->getCollection()->transform(function ($property) use ($propertyId, $data) {
            if ($property->id == $propertyId) {
                // Update property with new data
                $property->fill($data);
            }
            return $property;
        });

        session()->flash('success', 'Property updated successfully!');
    }

    public function handlePropertyDeleted($propertyId)
    {
        // Refresh the properties list
        $this->resetPage();
        session()->flash('success', 'Property deleted successfully!');
    }

    public function openEditModal($propertyId)
    {
        $property = Property::find($propertyId);
        if ($property) {
            // Use edit_data which includes the image field
            $this->dispatch('open-edit-modal', $property->edit_data)->to(EditPropertyModal::class);
        }
    }

    public function forwardToEditModal($propertyData)
    {
        $this->dispatch('open-edit-modal', $propertyData)->to(EditPropertyModal::class);
    }

    public function forwardToDeleteModal($propertyData)
    {
        $this->dispatch('open-delete-modal', $propertyData)->to(DeleteConfirmationModal::class);
    }

    public function getSortOptionsProperty()
    {
        return [
            'newest' => 'Newest First',
            'oldest' => 'Oldest First',
            'updated_desc' => 'Latest Updated',
            'price_asc' => 'Price: Low to High',
            'price_desc' => 'Price: High to Low',
            'title_asc' => 'Title: A to Z',
            'title_desc' => 'Title: Z to A',
            'size_asc' => 'Size: Small to Large',
            'size_desc' => 'Size: Large to Small',
        ];
    }

    public function getSortIcon($sortBy)
    {
        return match($sortBy) {
            'newest', 'oldest' => 'schedule',
            'updated_desc' => 'edit',
            'price_asc', 'price_desc' => 'payments',
            'title_asc', 'title_desc' => 'sort_by_alpha',
            'size_asc', 'size_desc' => 'square_foot',
            default => 'sort',
        };
    }

    public function getPropertiesProperty()
    {
        $query = Property::query();

        // Apply search
        if ($this->searchTerm) {
            $query->where(function ($q) {
                // Only search by ID if search term is numeric
                if (is_numeric($this->searchTerm)) {
                    $q->where('id', $this->searchTerm);
                }
                $q->orWhere('title', 'ILIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('address', 'ILIKE', '%' . $this->searchTerm . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply type filter
        if ($this->typeFilter !== 'all') {
            $query->where('property_type', $this->typeFilter);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'updated_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'size_asc':
                $query->orderBy('area_sqft', 'asc');
                break;
            case 'size_desc':
                $query->orderBy('area_sqft', 'desc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getTotalCountProperty()
    {
        return Property::count();
    }

    public function getShowingFromProperty()
    {
        if ($this->properties->total() === 0) return 0;
        return ($this->properties->currentPage() - 1) * $this->properties->perPage() + 1;
    }

    public function getShowingToProperty()
    {
        return min($this->properties->currentPage() * $this->properties->perPage(), $this->properties->total());
    }

    public function getShowingCountProperty()
    {
        return $this->properties->total();
    }

    public function getTotalPagesProperty()
    {
        return $this->properties->lastPage();
    }

    public function render()
    {
        return view('livewire.admin.inventory');
    }
}