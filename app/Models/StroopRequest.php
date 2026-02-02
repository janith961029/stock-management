<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StroopRequest extends Model
{

    protected $table = 'csoapprovals';
        protected $guarded = [];
    protected $primaryKey = 'id';


  public function store()
{
    return $this->belongsTo(Store::class, 'relevant_store_id', 'id');
}


}
