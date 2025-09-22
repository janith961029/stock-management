<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules'; 

    protected $fillable = [
        'name', 'details', 
    ];


    public function permissions()
    {
      
        return $this->hasMany(Permissions::class,'module_id');
    }
}
