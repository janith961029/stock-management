<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getSerialDetails($id)
    {
        // Search in quantities table directly
        $result = DB::table('quantities')
            ->where('barcode', $id)
            ->select(
                'warrenty_expiry_date',
                 'name',
                'serial_number',
               // 'barcode',
                'issuing_type',
                'issue_place',
                'signal_unit'
            )
            ->first();

        if ($result) {
            return response()->json([
                'serial_number'       => $result->serial_number,
               // 'barcode'           => $result->barcode,
               'issuing_type'         => $result->issuing_type,
                   'name'             => $result->name,
                'issue_place'         => $result->issue_place,
                'signal_unit'         => $result->signal_unit,
              'warrenty_expiry_date'  => $result->warrenty_expiry_date,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
    }

    public function itemdetails($id){
        return response()->json([
            'msg' => $id
        ]);
    }
}