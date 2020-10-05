<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'ADMIN',
            'status' => 1,
        ]);

        DB::table('roles')->insert([
            'name' => 'AUTHOR',
            'status' => 1,
        ]);

        DB::table('roles')->insert([
            'name' => 'CUSTOMER',
            'status' => 1,
        ]);
    }
}
