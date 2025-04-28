<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            'id' => 1,
            'name' => 'To Do',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('statuses')->insert([
            'id' => 2,
            'name' => 'In Progress',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('statuses')->insert([
            'id' => 3,
            'name' => 'Completed',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
