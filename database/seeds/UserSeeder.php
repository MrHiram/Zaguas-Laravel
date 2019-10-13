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

        $user->name ="patos";
        $user->last_name ="patos";
        $user->email ="patos@gmail.com";
        $user->password= Hash::make("patos");
        $user->activation_token = str_random(60);
        $user->save();
    }
}
