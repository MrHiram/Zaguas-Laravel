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
        'name', 'size', 'temperament','race','description','allergies','
        feeding','specials_care'
    ];

    /**Relation between pet and owner */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    
}
