<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 
    ];
    //relacion de usuarios y roles
    public function users(){
            return $this->belongsToMany(User::class)->withTimestamps();
        
    }
}
