<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnneeScolaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anneescolaires')->insert([
            'libelle' => '2023-2024',
        ]);

        DB::table('anneescolaires')->insert([
            'libelle' => '2024-2025',
        ]);
    }
}
