<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('priority')->insert([
            'id' => 1,
            'name' => 'High',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('priority')->insert([
            'id' => 2,
            'name' => 'Medium',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('priority')->insert([
            'id' => 3,
            'name' => 'Low',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
