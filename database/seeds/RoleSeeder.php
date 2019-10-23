<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'client';
        $role->save();
        $role = new Role();
        $role->name = 'care_taker';
        $role->save();
    }
}
