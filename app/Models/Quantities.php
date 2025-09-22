<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quantities extends Model
{
     protected $table = 'quantities';


     public function issue_places()
    {
        return $this->belongsTo(IssuePlaces::class, 'issue_place');
    }
     public function signalUnit()
    {
        return $this->belongsTo(SignalUnit::class, 'signal_unit', 'id');
    }
}
