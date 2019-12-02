<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image','name', 'size', 'temperament','race','description','allergies','
        feeding','specials_cares'
    ];

    /**Relation between pet and owner */
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
