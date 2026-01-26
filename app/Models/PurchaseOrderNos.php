<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderNos extends Model

{
  protected $guarded = [];


public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'sup_id');

    }

public function votes()
{
    return $this->belongsTo(Votes::class, 'vote_code', 'id');
}


public function establishment()
{
    return $this->belongsTo(Establishment::class, 'rcvd_to', 'id');
}
}
