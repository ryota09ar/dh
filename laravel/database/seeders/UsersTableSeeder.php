<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            "name"=>"a",
            "email"=>"a@a",
            "password"=>Hash::make("a"),
        ]);

        DB::table('users')->insert([
            "name"=>"b",
            "email"=>"b@b",
            "password"=>Hash::make("b"),
        ]);

        DB::table('users')->insert([
            "name"=>"c",
            "email"=>"c@c",
            "password"=>Hash::make("c"),
        ]);
    }
}
