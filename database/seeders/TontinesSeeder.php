<?php

namespace Database\Seeders;

use App\Models\Tontine;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TontinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Calendrier des tontines :
     * - Durée : 45 jours (1 mois et 15 jours)
     * - 4 paiements : J0, J15, J30, J45
     * - Dates de début typiques : le 5 ou le 20 du mois
     *
     * @return void
     */
    public function run()
    {
        // Date actuelle pour calculer les tontines
        $now = Carbon::now();

        // Tontine 1 : Active, commence le 5 du mois en cours
        // Si on est après le 5, on prend le 5 du mois en cours, sinon le 5 du mois précédent
        $startDate1 = Carbon::create($now->year, $now->month, 5);
        if ($now->day < 5) {
            $startDate1->subMonth();
        }

        // Tontine 2 : Active, a commencé le 20 du mois précédent
        $startDate2 = Carbon::create($now->year, $now->month, 20)->subMonth();

        // Tontine 3 : En attente, commence le 5 du mois prochain
        $startDate3 = Carbon::create($now->year, $now->month, 5)->addMonth();

        // Tontine 4 : Active, commence le 20 du mois en cours
        $startDate4 = Carbon::create($now->year, $now->month, 20);
        if ($now->day < 20) {
            $startDate4->subMonth();
        }

        // Tontine 5 : Active, a commencé il y a 30 jours (au milieu)
        $startDate5 = $now->copy()->subDays(30);

        // Tontine 6 : Complétée, terminée il y a 2 mois
        $startDate6 = $now->copy()->subMonths(3);

        $tontines = [
            [
                'name' => 'Tontine Parfums Premium - ' . $startDate1->format('F Y'),
                'description' => 'Sélectionnez vos parfums de luxe et payez en 4 tranches sur 45 jours. Commandez autant de parfums que vous le souhaitez !',
                'start_date' => $startDate1,
                'end_date' => $startDate1->copy()->addDays(45),
                'status' => 'active',
            ],
            [
                'name' => 'Tontine Découverte - ' . $startDate2->format('F Y'),
                'description' => 'Découvrez nos parfums avec un système de paiement flexible en 4 fois sur 45 jours. Choisissez vos parfums préférés et leurs quantités.',
                'start_date' => $startDate2,
                'end_date' => $startDate2->copy()->addDays(45),
                'status' => 'active',
            ],
            [
                'name' => 'Tontine Luxe Exclusive - ' . $startDate3->format('F Y'),
                'description' => 'Tontine haut de gamme pour les parfums d\'exception. Composez votre commande et divisez le montant en 4 paiements sur 45 jours.',
                'start_date' => $startDate3,
                'end_date' => $startDate3->copy()->addDays(45),
                'status' => 'pending',
            ],
            [
                'name' => 'Tontine Femmes Élégantes - ' . $startDate4->format('F Y'),
                'description' => 'Une sélection de parfums féminins raffinés. Choisissez vos fragrances et payez en 4 tranches.',
                'start_date' => $startDate4,
                'end_date' => $startDate4->copy()->addDays(45),
                'status' => 'active',
            ],
            [
                'name' => 'Tontine Hommes Distingués - ' . $startDate5->format('F Y'),
                'description' => 'Parfums masculins de caractère. Composez votre commande personnalisée avec paiement échelonné sur 45 jours.',
                'start_date' => $startDate5,
                'end_date' => $startDate5->copy()->addDays(45),
                'status' => 'active',
            ],
            [
                'name' => 'Tontine Spéciale Fêtes - ' . $startDate6->format('F Y'),
                'description' => 'Tontine complétée avec succès pour les fêtes.',
                'start_date' => $startDate6,
                'end_date' => $startDate6->copy()->addDays(45),
                'status' => 'completed',
            ],
        ];

        foreach ($tontines as $tontine) {
            Tontine::create($tontine);
        }

        $this->command->info('Tontines créées avec les dates de paiement :');
        foreach (Tontine::all() as $tontine) {
            $dates = $tontine->getPaymentDueDates();
            $this->command->line("  - {$tontine->name} ({$tontine->status})");
            $this->command->line("    Paiements : " . implode(', ', array_map(fn($d) => $d->format('d/m/Y'), $dates)));
        }
    }
}
