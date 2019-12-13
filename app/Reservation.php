<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function careTaker(){
        return $this->belongsTo(CareTakerProfile::class,'care_taker_profile_id');

    }

    public function client(){
        return $this->belongsTo(ClientProfile::class)->withTimestamps();

    }

    public function home(){
        return $this->belongsTo(Home::class,'home_id');
    }
    public function pets(){
        return $this->belongsToMany(Pet::class)->withTimestamps();

    }
}
