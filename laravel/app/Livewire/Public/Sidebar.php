<?php

namespace App\Livewire\Public;

use App\Models\Setting;
use Livewire\Component;

class Sidebar extends Component
{
    public $settings = [];

    public function mount()
    {
        $this->settings = Setting::first()?->toArray() ?? [];
    }

    public function render()
    {
        return view('livewire.public.sidebar');
    }
}
