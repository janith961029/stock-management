<?php

namespace App\Filament\Resources\QuantityResource\Pages;

use App\Filament\Resources\QuantityResource;
use App\Models\Quantities;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;

class Scan extends Page
{
    public string $search = '';
    public $record = null;
    public bool $searching = false;

    protected static string $resource = QuantityResource::class;
    protected static string $view = 'filament.resources.quantity-resource.pages.scan';

    public function updatedSearch(): void
    {
        $this->searching = true;
        $this->record = null;
        
        $trimmed = trim((string)$this->search);
        
        // Clean the search input more thoroughly
        $cleaned = preg_replace('/SN\s*CODE\s*:?\s*SN/i', '', $trimmed);
        $cleaned = trim($cleaned);
        $cleaned = preg_replace('/[^\d]/', '', $cleaned); // Keep only digits
        
        // Only search if we have a meaningful input
        if (strlen($cleaned) >= 3) {
            $this->record = Quantities::where(function($query) use ($cleaned) {
                    $query->where('barcode', 'like', "%{$cleaned}%")
                          ->orWhere('serial_number', 'like', "%{$cleaned}%");
                })
                ->first();
            
            Log::debug('Barcode search', [
                'original' => $this->search,
                'cleaned' => $cleaned,
                'found' => !is_null($this->record),
                'record' => $this->record
            ]);
            
            // Auto-clear after successful search (optional)
            if ($this->record) {
                $this->dispatch('search-cleared');
            }
        }
        
        $this->searching = false;
    }
    
    // Add method to manually clear search
    public function clearSearch(): void
    {
        $this->search = '';
        $this->record = null;
        $this->dispatch('search-cleared');
    }
}