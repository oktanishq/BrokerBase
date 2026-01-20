<?php

namespace App\Livewire\Public;

use App\Models\Setting;
use Livewire\Component;

class Hero extends Component
{
    public $settings = [];

    public function mount()
    {
        $this->settings = Setting::first()?->toArray() ?? [];
    }

    public function getCleanedPhoneNumber()
    {
        $phone = $this->settings['w_no'] ?? '';
        return preg_replace('/[^\d+]/', '', $phone);
    }

    public function getWhatsAppMessage()
    {
        $domain = request()->getHost();
        $agencyName = $this->settings['agency_name'] ?? 'Elite Homes';
        $address = $this->settings['office_address'] ?? 'Location not specified';
        $message = "Hii i'm interested in\n*{$agencyName}*\nat {$address}\nUID: N/A\nLink: {$domain}";
        $encodedMessage = urlencode($message);
        $phone = preg_replace('/[^\d]/', '', $this->settings['w_no'] ?? '');
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    public function render()
    {
        return view('livewire.public.hero');
    }
}
