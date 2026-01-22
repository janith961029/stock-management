<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SerialNumbers;
use App\Models\SignalUnit;
use App\Models\IctCategories;
use App\Models\EquipmentTypes;
use App\Models\TitleNames;
use App\Models\ReciveItems;

use function Livewire\store;

class Items extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $guarded = [];

    protected $casts = [
        'recieved' => 'array',
    ];

    // public function serial_numbers()
    // {
    //     return $this->hasMany(SerialNumbers::class);
    // }

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
        return $this->belongsTo(EquipmentTypes::class, 'equipment_types_id','id');
    }
public function relevantstore()
    {
        return $this->belongsTo(Store::class, 'relevant_store_id','id');
    }
    public function titlenames()
    {
        return $this->belongsTo(TitleNames::class, 'title_names_id');
    }

    public function item_details()
    {
        return $this->hasMany(ReciveItems::class);
    }
}
