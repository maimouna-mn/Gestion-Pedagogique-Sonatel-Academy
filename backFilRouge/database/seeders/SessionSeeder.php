<?php

namespace Database\Seeders;

use App\Models\Salle;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sessions')->insert([
            'date' => '2023-10-22',
            'heure_debut' => '08:00:00',
            'heure_fin' => '10:00:00',
            'Type' => 'presentiel',
            'salle_id' => Salle::inRandomOrder()->first()->id, 
        ]);

        DB::table('sessions')->insert([
            'date' => '2023-10-23',
            'heure_debut' => '14:00:00',
            'heure_fin' => '16:00:00',
            'Type' => 'enLigne',
            'salle_id' => null,
        ]);

    }
}
