<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('travel_options')->truncate();
        $travel_options = [
            [
               'option'=>'Flights',
               'created_at' => now(),
               'updated_at' => now(),
            ],
            [
               'option'=>'Hotels',
               'created_at' => now(),
               'updated_at' => now(),
            ],
            [
                'option'=>'Tours',
                'created_at' => now(),
                'updated_at' => now(),
             ],
        ];
    
        foreach ($travel_options as $key => $travel_option) {
           DB::table('travel_options')->insert([
            'option' => $travel_option
           ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
