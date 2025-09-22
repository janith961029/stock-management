<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderNos extends Model

{ 
  protected $guarded = [];    


public function supplier()
    {
        return $this->hasMany(Supplier::class,'sup_id');
       
    }
  
public function votes()
{
    return $this->belongsTo(Votes::class, 'vote_id', 'id');
}
    

public function Eshtablishment()
{
    return $this->belongsTo(Establishment::class, 'rcvd', 'id');
}
}
