<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(ShiftContentsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
    }
}
