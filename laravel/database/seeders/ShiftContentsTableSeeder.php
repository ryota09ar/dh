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
            "place"=>"所沢駅",
            "time"=>"09:30",
        ]);

        DB::table('shift_contents')->insert([
            "place"=>"武蔵藤沢",
            "time"=>"07:30",
        ]);
    }
}
