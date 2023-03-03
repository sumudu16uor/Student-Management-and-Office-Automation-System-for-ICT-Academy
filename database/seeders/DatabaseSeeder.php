<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BranchSeeder::class,
            PersonSeeder::class,
            EmployeeSeeder::class,
            StaffSeeder::class,
            UserSeeder::class,
            ProcessSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
