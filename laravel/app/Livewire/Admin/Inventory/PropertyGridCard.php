<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;

class PropertyGridCard extends Component
{
    public $property;

    protected $listeners = [
        'property-updated' => 'handlePropertyUpdate',
    ];

    public function mount($property)
    {
        $this->property = $property;
    }

    public function handlePropertyUpdate($propertyId, $data)
    {
        if ($this->property->id == $propertyId) {
            $this->property->fill($data);
        }
    }

    public function openEditModal()
    {
        $this->dispatch('open-edit-modal', $this->property->frontend_data);
    }

    public function openDeleteModal()
    {
        $this->dispatch('open-delete-modal', $this->property->frontend_data);
    }

    public function render()
    {
        return view('livewire.admin.inventory.property-grid-card');
    }
}