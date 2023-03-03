<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('processes')->insert([
            'processType' => 'month_end',
            'updated_at' => Carbon::now(),
            'handlerStaffID' => 'STAFF001',
            'branchID' => 'BRNCH001',
        ]);

        DB::table('processes')->insert([
            'processType' => 'year_end',
            'updated_at' => Carbon::now(),
            'handlerStaffID' => 'STAFF001',
            'branchID' => 'BRNCH001',
        ]);

        DB::table('processes')->insert([
            'processType' => 'ol_batch_end',
            'updated_at' => Carbon::now(),
            'handlerStaffID' => 'STAFF001',
            'branchID' => 'BRNCH001',
        ]);

        DB::table('processes')->insert([
            'processType' => 'al_batch_end',
            'updated_at' => Carbon::now()->lessThan(Carbon::now()->year . '-08-01 00:00:01') ?
                Carbon::now()->subYear() . '-09-01 00:00:01' :
                Carbon::now()->year . '-09-01 00:00:01',
            'handlerStaffID' => 'STAFF001',
            'branchID' => 'BRNCH001',
        ]);

        DB::table('processes')->insert([
            'processType' => 'clear_login',
            'updated_at' => Carbon::now(),
            'handlerStaffID' => 'STAFF001',
            'branchID' => 'BRNCH001',
        ]);
    }
}
