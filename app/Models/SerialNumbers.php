<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class SerialNumbers extends Model
{ use HasFactory;


    protected $guarded = [];
   public function item()
{
    return $this->belongsTo(Items::class,'relevant_store_id'); // foreign key = items.id
}


protected $casts = [
    'tags' => 'array',
];
public function SignalUnit()
{
    return $this->belongsTo(SignalUnit::class, 'signal_unit', 'id');
}
    public function issuePlace()
    {
        return $this->belongsTo(IssuePlaces::class, 'issue_place');
    }
        public function issuing_types()
    {
        return $this->belongsTo(IssuingType::class, 'issuing_type');
    }

   public function country()
    {
        return $this->belongsTo(countries::class, 'countries','name');
    }
// app/Models/SerialNumbers.php
public function recive_item()
{
    return $this->belongsTo(\App\Models\Serial::class, 'serial_id', 'id');
}
public function issue_item()
{
    return $this->belongsTo(Items::class, 'title_names_id'); // wrong FK
}






















}
