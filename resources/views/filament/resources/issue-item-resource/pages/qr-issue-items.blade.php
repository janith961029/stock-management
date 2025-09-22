<x-filament-panels::page>
    {{-- <p><strong>Item Code:</strong> {{ $record->item_code }}</p> --}}
    @livewire('serial-qr-list', ['itemId' => $record->id]) 
</x-filament-panels::page>
