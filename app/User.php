<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname', 'email', 'password','active', 'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    

    /**Relations */

    /**relacion de usuario Roles */

    public function roles(){
        
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**Relation between owner and pets */
    

    
    public function clientProfile(){
        
        return $this->hasOne(ClientProfile::class);
    }

    public function careTakerProfile(){
        
        return $this->hasOne(CareTakerProfile::class);
    }
    /**Authorize Roles  */

    public function authorizeRoles($roles)
    {
    abort_unless($this->hasAnyRole($roles), 401);
    return true;
    }
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
         foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
            }
        }
        } else {
             if ($this->hasRole($roles)) {
                 return true; 
        }   
    }
    return false;
}
    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first() || $role == "guest") {
            return true;
    }
        return false;
}

    public function getIdProfileClient(){
        return $this->clientProfile()->select('id')->get()[0]["id"];
        
    }

    public function getIdProfileCareTaker(){
        return $this->careTakerProfile()->select('id')->get()[0]["id"];
        
    }
}
