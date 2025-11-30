<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ordre important : respecter les dépendances entre les tables
        $this->call([
            AdminUserSeeder::class,      // Créer les utilisateurs (admin + clients)
            SuppliersSeeder::class,      // Créer les fournisseurs
            PerfumesSeeder::class,       // Créer les parfums (dépend des fournisseurs)
            TontinesSeeder::class,       // Créer les tontines
            SubscriptionsSeeder::class,  // Créer les inscriptions et paiements (dépend de tout)
        ]);

        $this->command->info('Base de données remplie avec succès !');
        $this->command->info('Compte admin : admin@tontine.com / password');
        $this->command->info('Comptes clients : jean@example.com, marie@example.com, etc. / password');
    }
}
