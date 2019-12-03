<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CareTakerProfile extends Model
{
    protected $fillable = [
        'image','user_id', 'about', 'address','phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
