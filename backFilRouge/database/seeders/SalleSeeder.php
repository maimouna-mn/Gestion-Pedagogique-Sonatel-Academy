<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('salles')->insert([
            'libelle' => 'Salle A',
            'numero' => '101',
            'nombrePlaces' => '50',
        ]);

        DB::table('salles')->insert([
            'libelle' => 'Salle B',
            'numero' => '102',
            'nombrePlaces' => '40',
        ]);
    }
}
