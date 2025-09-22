<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerCard extends Model
{
    protected $guarded = [];

public function item()
{
    return $this->belongsTo(Items::class, 'items_id');
}
}

