<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('semestres')->insert([
            'libelle' => 'Semestre 1',
        ]);

        DB::table('semestres')->insert([
            'libelle' => 'Semestre 2',
        ]);
    }
}
