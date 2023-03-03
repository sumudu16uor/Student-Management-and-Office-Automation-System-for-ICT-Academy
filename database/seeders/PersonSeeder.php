<?php

namespace Database\Seeders;

use App\Services\Implementation\IDGenerate\IDGenerateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param IDGenerateService $IDGenerateService
     * @return void
     */
    public function run(IDGenerateService $IDGenerateService)
    {
        DB::table('people')->insert([
            'personID' => $IDGenerateService->staffID(),
            'personType' => 'Employee',
            'firstName' => 'Super',
            'lastName' => 'User',
            'dob' => '1997-09-29',
            'sex' => 'Male',
            'telNo' => '0778596940',
            'address' => 'FoT, UoR',
            'email' => 'helankas26@gmail.com',
            'status' => 'Super',
            'joinedDate' => Carbon::now()->format('Y-m-d'),
        ]);
    }
}
