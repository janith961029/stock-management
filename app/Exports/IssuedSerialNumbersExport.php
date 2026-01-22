<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class IssuedSerialNumbersExport implements FromCollection, WithHeadings
{
    protected $reciveItemId;

    public function __construct($reciveItemId)
    {
        $this->reciveItemId = $reciveItemId;
    }

    public function collection()
    {
        return DB::table('serial_numbers as sn')
            ->leftJoin('recive_items as ri', 'sn.serial_id', '=', 'ri.id')
            ->leftJoin('signal_units as su', 'sn.signal_unit', '=', 'su.id')
            ->leftJoin('issue_places as ip', 'sn.issue_place', '=', 'ip.id')
            ->where('sn.serial_id', $this->reciveItemId) // <-- හරි variable එක දැන් use කරනවා
            ->where('sn.issued', 1)
            ->select(
                'sn.serial_number',
                'ri.model_name as model_name',
                'su.sig_unit_name as signal_unit_name',
                'ip.issue_place as issue_place_name',
                'ri.warrenty_expiry_date as warranty_expiry_date'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Serial Number',
            'Model Name',
            'Signal Unit',
            'Issue Place',
            'Warrenty Expiry Date',
        ];
    }
}
