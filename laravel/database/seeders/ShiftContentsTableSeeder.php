<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shift_contents')->insert([
            "shift_place"=>"所沢駅",
            "shift_time"=>"09:30",
        ]);

        DB::table('shift_contents')->insert([
            "shift_place"=>"武蔵藤沢",
            "shift_time"=>"07:30",
        ]);
    }
}
