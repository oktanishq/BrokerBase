<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;

class PropertyGridCard extends Component
{
    public $property;

    public function mount($property)
    {
        $this->property = $property;
    }

    public function openEditModal()
    {
        $this->dispatch('open-edit-modal', $this->property->toArray());
    }

    public function openDeleteModal()
    {
        $this->dispatch('open-delete-modal', $this->property->toArray());
    }

    public function render()
    {
        return view('livewire.admin.inventory.property-grid-card');
    }
}