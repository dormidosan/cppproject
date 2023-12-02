<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFilePath = base_path('database/seeders/airports.sql');
        $sql = file_get_contents($sqlFilePath);
        DB::unprepared($sql);
    }
}
