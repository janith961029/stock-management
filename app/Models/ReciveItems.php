<?php

namespace App\Models;

use App\Filament\Widgets\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciveItems extends Model
{

 use HasFactory;

    protected $table = 'items';
    protected $guarded = [];

    protected $casts = [
        'recieved' => 'array',
    ];

    public function serial_numbers()
    {
        return $this->hasMany(SerialNumbers::class);
    }

    public function signalUnit()
    {
        return $this->belongsTo(SignalUnit::class, 'signal_unit', 'id');
    }

    public function ictcategories()
    {
        return $this->belongsTo(IctCategories::class, 'ict_category_id', 'id');
    }

    public function equipment_types()
    {
        return $this->belongsTo(EquipmentTypes::class, 'equipment_types_id');
    }

    public function titlenames()
    {
        return $this->belongsTo(TitleNames::class, 'title_names_id');
    }

    public function item_details()
    {
        return $this->hasMany(Serial::class);
    }
 public function title()
{
    return $this->belongsTo(Items::class, 'recive_items_id','id'); // wrong FK
}

}
