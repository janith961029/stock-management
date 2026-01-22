<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModule extends Model
{
   protected $table = 'model_has_roles';
    protected $guarded = [];
    


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id','id');
    }
     public function model(): BelongsTo
    {
        return $this->belongsTo(User::class, 'model_id','id');
    }
}
