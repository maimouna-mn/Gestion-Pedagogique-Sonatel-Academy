<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modules')->insert([
            'libelle' => 'Angular',
        ]);

        DB::table('modules')->insert([
            'libelle' => 'Laravel',
        ]);
    }
}
