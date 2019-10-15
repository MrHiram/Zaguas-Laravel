<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;

        $user->name ="Admin";
        $user->lastname ="Sudo";
        $user->email ="admin@zaguas.com";
        $user->password= Hash::make("patos1234");
        $user->activation_token = str_random(60);
        $user->save();
    }
}
