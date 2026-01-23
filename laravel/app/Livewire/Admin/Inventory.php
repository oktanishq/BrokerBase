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
    public $viewMode = 'list'; // 'list' or 'grid'
    public $perPage = 10;

    protected $listeners = [
        'property-updated' => 'handlePropertyUpdate',
        'property-deleted' => 'handlePropertyDeleted',
        'open-edit-modal' => 'forwardToEditModal',
        'open-delete-modal' => 'forwardToDeleteModal',
    ];

    public function mount()
    {
        // Initialize if needed
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

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedViewMode()
    {
        // Handle view mode change if needed
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

    public function forwardToEditModal($propertyData)
    {
        $this->dispatch('open-edit-modal', $propertyData)->to(EditPropertyModal::class);
    }

    public function forwardToDeleteModal($propertyData)
    {
        $this->dispatch('open-delete-modal', $propertyData)->to(DeleteConfirmationModal::class);
    }

    public function nextPage()
    {
        $this->setPage($this->page + 1);
    }

    public function prevPage()
    {
        $this->setPage($this->page - 1);
    }

    public function goToPage($page)
    {
        $this->setPage($page);
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