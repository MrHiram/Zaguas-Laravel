<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "patos";
        $user->lastname =  "hola";
        $user->email = "patos@gmail.com";
        $user->password = Hash::make('patos.4k');;
        $user->active = 1;
        $user->activation_token = "default";
        
        $user->save();
        $role = Role::where('name', 'client')->first();
        $user->roles()->attach($role);
        $role2 = Role::where('name', 'care_taker')->first();
        $user->roles()->attach($role2);
        
    }
}
