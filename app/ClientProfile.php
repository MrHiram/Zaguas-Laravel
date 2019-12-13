<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    protected $fillable = [
        'image', 'about', 'address','phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
