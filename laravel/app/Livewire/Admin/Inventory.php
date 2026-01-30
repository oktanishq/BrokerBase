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