<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Csoapproval extends Model
{
     protected $guarded = [];




     public function titlenames()
    {
        return $this->belongsTo(TitleNames::class, 'title_names_id');
    }

 public function store()
{
    return $this->belongsTo(Store::class, 'relevant_store_id', 'id');
}


}
