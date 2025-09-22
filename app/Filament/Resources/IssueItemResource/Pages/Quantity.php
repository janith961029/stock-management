<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Exports\IssuedSerialNumbersExport;
use App\Filament\Resources\IssueItemResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Quantity extends Page
{
    protected static string $resource = IssueItemResource::class;
    protected static ?string $title = 'Item Quantity Details';
    protected static string $view = 'filament.resources.issue-item-resource.pages.quantity';
    
    use InteractsWithRecord;

    // Remove the $record property declaration since it's provided by the trait
    public $itemData;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        static::authorizeResourceAccess();
        $this->itemData = $this->getViewData();
    }

    public function getViewData(): array
    {
        $itemid = $this->record->items_id;

        // Get counts
        $totalCount = DB::table('items')
            ->join('recive_items', 'items.id', '=', 'recive_items.items_id')
            ->join('serial_numbers', 'recive_items.id', '=', 'serial_numbers.recive_items_id')
            ->where('items.id', $itemid)
            ->count('serial_numbers.id');

        $receivedCount = DB::table('items')
            ->join('recive_items', 'items.id', '=', 'recive_items.items_id')
            ->join('serial_numbers', 'recive_items.id', '=', 'serial_numbers.recive_items_id')
            ->where('items.id', $itemid)
            ->where('serial_numbers.recieved', 1)
            ->count('serial_numbers.id');

        $issuedCount = DB::table('items')
            ->join('recive_items', 'items.id', '=', 'recive_items.items_id')
            ->join('serial_numbers', 'recive_items.id', '=', 'serial_numbers.recive_items_id')
            ->where('items.id', $itemid)
            ->where('serial_numbers.issued', 1)
            ->count('serial_numbers.id');

        $availableCount = $totalCount - $issuedCount;

        // Get serial numbers
      $serialNumbers = DB::table('serial_numbers as sn')
    ->leftJoin('recive_items as ri', 'sn.recive_items_id', '=', 'ri.id')
    ->leftJoin('items as it', 'ri.items_id', '=', 'it.id')
    ->leftJoin('signal_units as su', 'sn.signal_unit', '=', 'su.id')
    ->leftJoin('issue_places as ip', 'sn.issue_place', '=', 'ip.id')
    ->where('ri.items_id', $itemid)
    ->select(
        'sn.serial_number',
        'sn.barcode',
        'sn.issuing_type',
        'sn.issue_place',
        'sn.signal_unit as signal_unit_name',
        'sn.issued',        
        'sn.recieved',           
        'ri.warrenty_expiry_date as warrenty_expiry_date'
    )
    ->get();      


  return [
            'totalCount' => $totalCount,
            'receivedCount' => $receivedCount,
            'availableCount' => $availableCount,
            'issuedCount' => $issuedCount,
            'serialNumbers' => $serialNumbers,
            'itemCode' => $this->record->item_code ?? 'N/A',
        ];
    }

    public function exportIssuedSerialNumbers()
    {
        $itemId = $this->record->id;
        return Excel::download(new IssuedSerialNumbersExport($itemId), 'issued_serial_numbers.xlsx');
    }

    // Add this method to handle authorization if needed
    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();
    }
}