<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function Livewire\store;

class titlenames extends Model
{
      protected $table = 'titlenames';
  protected $guarded = [];
 public function stores()
    {
        return $this->belongsTo(store::class, 'relevant_store_id');
    }

}
