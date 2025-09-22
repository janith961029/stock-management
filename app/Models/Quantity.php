<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{protected $table = 'quantities';
    public function issue_places()
    {
        return $this->belongsTo(IssuePlaces::class, 'issue_place');
    }
}
