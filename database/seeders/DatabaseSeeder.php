<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {




        // Puis tes livraisons...
        DB::table('livraison')->insert([
            [
                'id_livreur' => 1,
                'id_utilisateur' => 1,
                'id_adresse_depart' => 1,
                'id_adresse_arrivee' => 2,
                'date_livraison' => now(),
                'code_validation' => 'ABC123',
                'poids' => 10.5,
                'fragile' => false,
                'statut' => 'en_attente',
                'contenu' => 'Colis test 1',
                'date' => now(),
                'modalite' => 'express',
                'type' => 'colis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_livreur' => 1,
                'id_utilisateur' => 2,
                'id_adresse_depart' => 1,
                'id_adresse_arrivee' => 3,
                'date_livraison' => now(),
                'code_validation' => 'XYZ789',
                'poids' => 5.0,
                'fragile' => true,
                'statut' => 'en_cours',
                'contenu' => 'Colis test 2',
                'date' => now(),
                'modalite' => 'standard',
                'type' => 'colis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
