<?php

namespace App\Models;

use App\Filament\Widgets\Item;
use Illuminate\Database\Eloquent\Model;

class ReciveItems extends Model
{
   

    protected $guarded = [];
 

    public function serial_numbers()
    {
       return $this->hasMany(\App\Models\serial_numbers::class, 'recive_items_id', 'id');
       
    }
     public function purchase_order_nos()
    {
        return $this->belongsTo(PurchaseOrderNos::class,'purchase_order_no');
       
    }
      public function items()
    {
        return $this->belongsTo(Items::class,'items_id');
    }
    public static function canCreate(): bool
{
    return false;
}
}