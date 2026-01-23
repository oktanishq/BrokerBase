<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Property;
use Livewire\Component;

class EditPropertyModal extends Component
{
    public $isOpen = false;
    public $property = null;
    public $saving = false;
    public $ownerSectionExpanded = false;

    // Form data
    public $status = 'available';
    public $is_featured = false;
    public $label_type = 'none';
    public $custom_label_color = '#3B82F6';
    public $owner_name = '';
    public $owner_phone = '';
    public $net_price = '';
    public $private_notes = '';

    protected $listeners = [
        'open-edit-modal' => 'openModal',
        'close-edit-modal' => 'closeModal',
    ];

    public function openModal($propertyData)
    {
        $this->property = $propertyData;
        $this->populateFormData();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function populateFormData()
    {
        if (!$this->property) return;

        $this->status = $this->property['status'] ?? 'available';
        $this->is_featured = $this->property['is_featured'] ?? false;
        $this->label_type = $this->property['label'] ?? 'none';
        $this->custom_label_color = $this->property['custom_label_color'] ?? '#3B82F6';
        $this->owner_name = $this->property['owner_name'] ?? '';
        $this->owner_phone = $this->property['owner_phone'] ?? '';
        $this->net_price = $this->property['net_price'] ? number_format($this->property['net_price']) : '';
        $this->private_notes = $this->property['private_notes'] ?? '';
    }

    public function resetForm()
    {
        $this->property = null;
        $this->status = 'available';
        $this->is_featured = false;
        $this->label_type = 'none';
        $this->custom_label_color = '#3B82F6';
        $this->owner_name = '';
        $this->owner_phone = '';
        $this->net_price = '';
        $this->private_notes = '';
        $this->ownerSectionExpanded = false;
    }

    public function toggleOwnerSection()
    {
        $this->ownerSectionExpanded = !$this->ownerSectionExpanded;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setLabelType($type)
    {
        $this->label_type = $type;
    }

    public function editAllDetails()
    {
        if ($this->property && isset($this->property['id'])) {
            return redirect()->route('properties.edit', $this->property['id']);
        }
    }

    public function saveChanges()
    {
        if (!$this->property || !isset($this->property['id'])) return;

        $this->saving = true;

        try {
            $property = Property::findOrFail($this->property['id']);

            $property->update([
                'status' => $this->status,
                'is_featured' => $this->is_featured,
                'label_type' => $this->label_type,
                'custom_label_color' => $this->custom_label_color,
                'owner_name' => $this->owner_name,
                'owner_phone' => $this->owner_phone,
                'net_price' => str_replace(',', '', $this->net_price),
                'private_notes' => $this->private_notes,
            ]);

            // Emit event to refresh inventory with updated data
            $this->dispatch('property-updated', propertyId: $this->property['id'], data: $property->toArray());

            // Close modal
            $this->closeModal();

            // Show success message
            session()->flash('success', 'Property updated successfully!');
        } catch (\Exception $error) {
            // Show error message
            session()->flash('error', 'Failed to update property. Please try again.');
        } finally {
            $this->saving = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.inventory.edit-property-modal');
    }
}