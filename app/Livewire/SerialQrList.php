<?php

namespace App\Livewire;

use App\Models\serial_numbers;
use Livewire\Component;

class SerialQrList extends Component
{
    public $serialNumbers;
    public $itemId;  // item id or any filter id you want

    public function mount($itemId = null)
{
    $this->itemId = $itemId;

    if ($this->itemId) {
        $this->serialNumbers = serial_numbers::with([
            'item',         // relation to Item model for item_name, warranty_expiry_date
            'signalUnit',   // relation to SignalUnit model for signal_unit_name
            'IssuePlace'    // relation to IssuePlace model for issue_place_name
        ])->where('recive_items_id', $this->itemId)->get();
    } else {
        $this->serialNumbers = collect();
    }
}


    public function render()
    {
        return view('livewire.serial-qr-list');
    }
}