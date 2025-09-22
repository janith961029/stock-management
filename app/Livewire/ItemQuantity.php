<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ItemQuantity extends Component
{
    public $itemId;
    public $itemData;
    
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->itemData = DB::table('items')
            ->where('id', $this->itemId)
            ->first();
    }
    
    public function render()
    {
        return view('livewire.item-quantity', [
            'item' => $this->itemData
        ]);
    }
}