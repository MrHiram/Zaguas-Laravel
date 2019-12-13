<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'image','description','price_per_night','capacity','walk','days_available' 
    ];

    /**Relation between home and owner */
    public function careTaker()
    {
        return $this->belongsTo(CareTakerProfile::class,'care_taker_profile_id');
    }
}
