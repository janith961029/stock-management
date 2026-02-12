<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class IssueItem extends Model
{

protected $table = 'recive_items';
    protected $guarded = [];


    public function serial_numbers()
    {
        $foreignKey = Schema::hasColumn('serial_numbers', 'serial_id')
            ? 'serial_id'
            : 'recive_items_id';

        return $this->hasMany(SerialNumbers::class, $foreignKey, 'id');
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
    $foreignKey = Schema::hasColumn('recive_items', 'recive_items_id')
        ? 'recive_items_id'
        : 'items_id';

    return $this->belongsTo(Items::class, $foreignKey, 'id');
}

  public function title()
{
    $foreignKey = Schema::hasColumn('recive_items', 'recive_items_id')
        ? 'recive_items_id'
        : 'items_id';

    return $this->belongsTo(Items::class, $foreignKey, 'id');
}

    public function modelName()
{
    return $this->belongsTo(ModelName::class, 'model_name', 'id');
}



    public static function canCreate(): bool
{
    return false;
}
}
