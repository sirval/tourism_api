<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        $roles = [
            [
               'name'=>'Admin',
               'created_at' => now(),
               'updated_at' => now(),
            ],
            [
               'name'=>'User',
               'created_at' => now(),
               'updated_at' => now(),
            ],
        ];
    
        foreach ($roles as $key => $role) {
           DB::table('roles')->insert([
            'name' => $role
           ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
