<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function careTaker(){
        return $this->hasOne(CareTakerProfile::class)->withTimestamps();

    }

    public function client(){
        return $this->hasOne(ClientProfile::class)->withTimestamps();

    }

    public function pets(){
        return $this->hasMany(Pets::class)->withTimestamps();

    }
}
