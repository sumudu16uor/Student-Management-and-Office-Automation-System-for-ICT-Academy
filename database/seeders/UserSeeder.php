<?php

namespace Database\Seeders;

use App\Services\Implementation\IDGenerate\IDGenerateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param IDGenerateService $IDGenerateService
     * @return void
     */
    public function run(IDGenerateService $IDGenerateService)
    {
        DB::table('users')->insert([
            'userID' => $IDGenerateService->userID(),
            'username' => 'SuperAdmin',
            'password' => Hash::make('adminSuper2018'),
            'privilege' => 'Super',
            'employeeID' => 'STAFF001',
            'status' => 'Active'
        ]);
    }
}
