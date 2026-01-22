<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function Livewire\store;

class Titlenames extends Model
{
      protected $table = 'titlenames';
  protected $guarded = [];
 public function stores()
    {
        return $this->belongsTo(Store::class, 'relevant_store_id');
    }

}
