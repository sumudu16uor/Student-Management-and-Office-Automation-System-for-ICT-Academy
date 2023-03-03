<?php

namespace Database\Seeders;

use App\Services\Implementation\IDGenerate\IDGenerateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param IDGenerateService $IDGenerateService
     * @return void
     */
    public function run(IDGenerateService $IDGenerateService)
    {
        DB::table('branches')->insert([
            'branchID' => $IDGenerateService->branchID(),
            'branchName' => 'Hakmana',
            'telNo' => '0769198533',
            'address' => 'ICT Academy, Beliatta road, Hakmana',
            'noOfRooms' => '4',
        ]);
    }
}
