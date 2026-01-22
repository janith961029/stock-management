<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueItem extends Model
{

protected $table = 'recive_items';
    protected $guarded = [];


    public function serial_numbers()
    {
        return $this->hasMany(SerialNumbers::class, 'serial_id', 'id');
    }

     public function purchase_order_nos()
    {
        return $this->belongsTo(PurchaseOrderNos::class,'purchase_order_no');

    }
     public function equipment_types()
    {
        return $this->belongsTo(EquipmentTypes::class, 'equipment_types_id');
    }
   public function recplaces()
    {
        return $this->belongsTo(RecPlaces::class, 'received_place','id');
    }
     public function titlenames()
    {
        return $this->belongsTo(TitleNames::class, 'title_names_id');
    }
public function country()
    {
        return $this->belongsTo(countries::class, 'manufactured_country','id');
    }
  public function item()
{
    return $this->belongsTo(Items::class,'recive_items_id','id'); // foreign key = items.id
}

  public function title()
{
    return $this->belongsTo(Items::class, 'recive_items_id','id'); // wrong FK
}



    public static function canCreate(): bool
{
    return false;
}
}
