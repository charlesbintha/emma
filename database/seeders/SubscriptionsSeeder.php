<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tontine;
use App\Models\Perfume;
use App\Models\TontineSubscription;
use App\Models\TontineSubscriptionItem;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionsSeeder extends Seeder
{
    /**
     * Intervalles de paiement en jours : J0, J15, J30, J45
     */
    private $paymentIntervals = [0, 15, 30, 45];

    public function run()
    {
        $clients = User::where('role', 'client')->get();
        $perfumes = Perfume::all();

        if ($clients->isEmpty() || $perfumes->isEmpty()) {
            $this->command->warn('Veuillez exécuter AdminUserSeeder et PerfumesSeeder avant ce seeder');
            return;
        }

        // Tontine Premium (ID: 1) - Active, au début
        $tontine1 = Tontine::find(1);
        if ($tontine1) {
            // Client 0 : 2 parfums, 2 premiers paiements effectués (J0 et J15)
            $this->createSubscription($clients[0], $tontine1, [
                ['perfume' => $perfumes[0], 'quantity' => 1],
                ['perfume' => $perfumes[1], 'quantity' => 2],
            ], $tontine1->start_date->copy()->addDays(2), [1, 2]);

            // Client 1 : 1 parfum, 1er paiement effectué (J0)
            $this->createSubscription($clients[1], $tontine1, [
                ['perfume' => $perfumes[2], 'quantity' => 1],
            ], $tontine1->start_date->copy()->addDays(3), [1]);

            // Client 2 : 3 parfums différents, aucun paiement encore
            $this->createSubscription($clients[2], $tontine1, [
                ['perfume' => $perfumes[3], 'quantity' => 1],
                ['perfume' => $perfumes[4], 'quantity' => 1],
                ['perfume' => $perfumes[5], 'quantity' => 1],
            ], $tontine1->start_date->copy()->addDays(5), []);
        }

        // Tontine Découverte (ID: 2) - Active, bien avancée
        $tontine2 = Tontine::find(2);
        if ($tontine2) {
            // Client 1 : Commande complétée (4 paiements)
            $this->createSubscription($clients[1], $tontine2, [
                ['perfume' => $perfumes[8], 'quantity' => 2],
                ['perfume' => $perfumes[9], 'quantity' => 1],
            ], $tontine2->start_date->copy(), [1, 2, 3, 4], 'completed');

            // Client 2 : 3 paiements sur 4 (J0, J15, J30 payés)
            $this->createSubscription($clients[2], $tontine2, [
                ['perfume' => $perfumes[9], 'quantity' => 3],
            ], $tontine2->start_date->copy()->addDays(1), [1, 2, 3]);

            // Client 3 : 2 paiements sur 4 (J0, J15 payés)
            $this->createSubscription($clients[3], $tontine2, [
                ['perfume' => $perfumes[8], 'quantity' => 1],
                ['perfume' => $perfumes[10], 'quantity' => 1],
            ], $tontine2->start_date->copy()->addDays(2), [1, 2]);
        }

        // Tontine Femmes Élégantes (ID: 4) - Active, récente
        $tontine4 = Tontine::find(4);
        if ($tontine4) {
            // Client 3 : Nouvelle inscription, 1er paiement effectué
            $this->createSubscription($clients[3], $tontine4, [
                ['perfume' => $perfumes[4], 'quantity' => 2],
            ], $tontine4->start_date->copy()->addDays(1), [1]);

            // Client 4 : Nouvelle inscription, aucun paiement
            $this->createSubscription($clients[4], $tontine4, [
                ['perfume' => $perfumes[6], 'quantity' => 1],
                ['perfume' => $perfumes[7], 'quantity' => 1],
            ], $tontine4->start_date->copy()->addDays(3), []);
        }

        // Tontine Hommes Distingués (ID: 5) - Active, au milieu (J30)
        $tontine5 = Tontine::find(5);
        if ($tontine5) {
            // Client 0 : 3 paiements sur 4 effectués (J0, J15, J30)
            $this->createSubscription($clients[0], $tontine5, [
                ['perfume' => $perfumes[1], 'quantity' => 1],
                ['perfume' => $perfumes[5], 'quantity' => 2],
            ], $tontine5->start_date->copy(), [1, 2, 3]);

            // Client 4 : Commande complétée (4 paiements)
            $this->createSubscription($clients[4], $tontine5, [
                ['perfume' => $perfumes[5], 'quantity' => 1],
            ], $tontine5->start_date->copy()->addDays(2), [1, 2, 3, 4], 'completed');
        }

        // Tontine complétée (ID: 6)
        $tontine6 = Tontine::find(6);
        if ($tontine6) {
            // Inscription complète avec tous les paiements (4 tranches)
            $this->createSubscription($clients[2], $tontine6, [
                ['perfume' => $perfumes[13], 'quantity' => 1],
                ['perfume' => $perfumes[14], 'quantity' => 1],
            ], $tontine6->start_date->copy(), [1, 2, 3, 4], 'completed');

            // Autre client avec tous les paiements
            $this->createSubscription($clients[0], $tontine6, [
                ['perfume' => $perfumes[0], 'quantity' => 1],
            ], $tontine6->start_date->copy()->addDays(1), [1, 2, 3, 4], 'completed');
        }

        $this->command->info('Souscriptions créées avec succès');
        $this->command->info('Calendrier des paiements : J0, J15, J30, J45 (intervalles de 15 jours)');
    }

    /**
     * Créer une inscription avec ses items et paiements
     * Paiements espacés de 15 jours : J0, J15, J30, J45
     */
    private function createSubscription($user, $tontine, $items, $subscriptionDate, $paidPayments = [], $status = 'active')
    {
        // Créer l'inscription
        $subscription = TontineSubscription::create([
            'user_id' => $user->id,
            'tontine_id' => $tontine->id,
            'subscription_date' => $subscriptionDate,
            'status' => $status,
        ]);

        // Créer les items de la commande
        $totalAmount = 0;
        foreach ($items as $itemData) {
            $perfume = $itemData['perfume'];
            $quantity = $itemData['quantity'];
            $subtotal = $perfume->price * $quantity;

            TontineSubscriptionItem::create([
                'tontine_subscription_id' => $subscription->id,
                'perfume_id' => $perfume->id,
                'quantity' => $quantity,
                'unit_price' => $perfume->price,
                'subtotal' => $subtotal,
            ]);

            $totalAmount += $subtotal;
        }

        // Nombre fixe de paiements : 4 tranches
        $numberOfPayments = 4;
        $installmentAmount = $totalAmount / $numberOfPayments;

        // Créer les paiements avec intervalles de 15 jours
        for ($i = 1; $i <= $numberOfPayments; $i++) {
            // Pour la dernière tranche, ajuster pour éviter les erreurs d'arrondi
            $amount = ($i == $numberOfPayments)
                ? ($totalAmount - ($installmentAmount * ($numberOfPayments - 1)))
                : $installmentAmount;

            // Date d'échéance : start_date + intervalle (0, 15, 30, 45 jours)
            $dueDate = $tontine->start_date->copy()->addDays($this->paymentIntervals[$i - 1]);

            // Déterminer le statut
            $paymentStatus = 'pending';
            $paymentDate = null;
            $paymentMethod = null;
            $reference = null;

            if (in_array($i, $paidPayments)) {
                $paymentStatus = 'paid';
                // Date de paiement : quelques jours avant ou après l'échéance
                $paymentDate = $dueDate->copy()->addDays(rand(-2, 3));
                $paymentMethods = ['mobile_money', 'bank_transfer', 'cash'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                $reference = $paymentMethod === 'mobile_money'
                    ? 'OM' . rand(100000000, 999999999)
                    : 'REF' . rand(10000, 99999);
            } elseif ($dueDate->isPast() && !in_array($i, $paidPayments)) {
                $paymentStatus = 'late';
            }

            Payment::create([
                'tontine_subscription_id' => $subscription->id,
                'payment_number' => $i,
                'amount' => round($amount, 2),
                'due_date' => $dueDate,
                'status' => $paymentStatus,
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'reference' => $reference,
            ]);
        }
    }
}
