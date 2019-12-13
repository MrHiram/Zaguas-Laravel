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

        $user2 = new User();
        $user2->name = "Zaguas";
        $user2->lastname =  "Zaguas";
        $user2->email = "zaguas@gmail.com";
        $user2->password = Hash::make('patos.4k');;
        $user2->active = 1;
        $user2->activation_token = "default";
        
        $user2->save();
        $role = Role::where("name","care_taker")->first();
        $user2->roles()->attach($role);
       
        
    }
}
