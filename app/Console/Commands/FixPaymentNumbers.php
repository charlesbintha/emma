<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\TontineSubscription;
use Illuminate\Support\Facades\DB;

class FixPaymentNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fix-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les numéros de paiement (2-5 vers 1-4)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔧 Correction des numéros de paiement...');
        $this->newLine();

        DB::beginTransaction();

        try {
            // Récupérer toutes les souscriptions
            $subscriptions = TontineSubscription::with('payments')->get();

            $totalSubscriptions = $subscriptions->count();
            $fixedPayments = 0;

            $this->info("📊 {$totalSubscriptions} souscription(s) trouvée(s)");
            $this->newLine();

            foreach ($subscriptions as $subscription) {
                $this->info("🔄 Souscription #{$subscription->id}");

                // Récupérer les paiements triés par due_date
                $payments = $subscription->payments()->orderBy('due_date')->get();

                // Afficher l'état avant
                $this->line("   Avant: " . $payments->pluck('payment_number')->join(', '));

                // Recalculer les numéros de paiement
                $expectedNumber = 1;
                foreach ($payments as $payment) {
                    if ($payment->payment_number !== $expectedNumber) {
                        $oldNumber = $payment->payment_number;
                        $payment->payment_number = $expectedNumber;
                        $payment->save();

                        $this->comment("   ✓ Paiement #{$payment->id}: {$oldNumber} → {$expectedNumber}");
                        $fixedPayments++;
                    }
                    $expectedNumber++;
                }

                // Afficher après
                $this->info("   Après:  " . $payments->pluck('payment_number')->join(', '));
                $this->newLine();
            }

            DB::commit();

            $this->newLine();
            $this->info("✅ Correction terminée !");
            $this->info("📈 {$fixedPayments} paiement(s) corrigé(s)");

            // Vérification finale
            $this->newLine();
            $this->info("🔍 Vérification finale...");

            $invalidPayments = DB::table('payments')
                ->select('tontine_subscription_id', DB::raw('COUNT(*) as count'),
                         DB::raw('MIN(payment_number) as min'),
                         DB::raw('MAX(payment_number) as max'))
                ->groupBy('tontine_subscription_id')
                ->havingRaw('count != 4 OR min != 1 OR max != 4')
                ->get();

            if ($invalidPayments->isEmpty()) {
                $this->info("✅ Toutes les souscriptions ont des paiements numérotés 1-4");
            } else {
                $this->error("⚠️ {$invalidPayments->count()} souscription(s) avec problèmes:");
                foreach ($invalidPayments as $invalid) {
                    $this->error("   Souscription #{$invalid->tontine_subscription_id}: {$invalid->count} paiements, numéros {$invalid->min}-{$invalid->max}");
                }
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erreur: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
