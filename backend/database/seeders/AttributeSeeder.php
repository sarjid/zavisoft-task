<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('attributes')->insert([
            ['id' => 1, 'name' => 'Color', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Size', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Ram', 'created_at' =>  now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Region', 'created_at' =>  now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Storage', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
