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
            'libelle' => 'Anglais',
        ]);

        DB::table('modules')->insert([
            'libelle' => 'ReactJs',
        ]);
        DB::table('modules')->insert([
            'libelle' => 'Excel',
        ]);
        DB::table('modules')->insert([
            'libelle' => 'Flutter',
        ]);
        DB::table('modules')->insert([
            'libelle' => 'JavaScript',
        ]);
        DB::table('modules')->insert([
            'libelle' => 'Ph',
        ]);
    }
}
