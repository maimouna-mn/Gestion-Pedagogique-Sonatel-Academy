<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classes')->insert([
            'libelle' => 'Classe A',
            'niveau' => 'Première',
            'effectif' =>20,
        ]);

        DB::table('classes')->insert([
            'libelle' => 'Classe B',
            'niveau' => 'Seconde',
            'effectif' =>20,

        ]);
    }
}
