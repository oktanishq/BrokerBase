<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Property;
use Livewire\Component;

class DeleteConfirmationModal extends Component
{
    public $showDeleteModal = false;
    public $propertyToDelete = null;
    public $isDeleting = false;

    protected $listeners = [
        'open-delete-modal' => 'openModal',
        'close-delete-modal' => 'closeModal',
    ];

    public function openModal($propertyData)
    {
        $this->propertyToDelete = $propertyData;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->propertyToDelete = null;
        $this->isDeleting = false;
    }

    public function deleteProperty()
    {
        if (!$this->propertyToDelete || $this->isDeleting) {
            return;
        }

        $this->isDeleting = true;

        try {
            $property = Property::findOrFail($this->propertyToDelete['id']);
            $property->delete();

            // Emit event to refresh inventory
            $this->dispatch('property-deleted', propertyId: $this->propertyToDelete['id']);

            // Close modal
            $this->closeModal();

            // Show success message
            session()->flash('success', 'Property deleted successfully');
        } catch (\Exception $error) {
            // Show error message
            session()->flash('error', 'Failed to delete property. Please try again.');
            $this->isDeleting = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.inventory.delete-confirmation-modal');
    }
}