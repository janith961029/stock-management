<?php

namespace App\Http\Controllers;
use App\Models\Items;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use DNS1D;
class ItemPdfController extends Controller
{
 public function download(Items $item)
{
    // Generate barcode and save to file
     $barcodeText = $item->item_code . ' | ' . $item->ledger_card_no . ' | SN: ' . $item->Item_Type;
     $barcodeData = DNS1D::getBarcodePNG($barcodeText, 'C128');
    
    return Pdf::loadView('pdf.item', [
        'item' => $item,
        'barcodeImage' => $barcodeData,
    ])->download("Item_{$item->id}.pdf");
}
public function downloadMultiple()
{
    $items = Items::take(10)->get(); // or use a filter or selected IDs

    $barcodes = [];

    foreach ($items as $item) {
        $barcodeData = DNS1D::getBarcodePNG($item->item_code, 'C128');
        $barcodes[$item->id] = $barcodeData;
    }

    return Pdf::loadView('pdf.items-multiple', [
        'items' => $items,
        'barcodes' => $barcodes,
    ])->download('Multiple_Items.pdf');
}
}