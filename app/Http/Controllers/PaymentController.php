<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TontineSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = Payment::with(['tontineSubscription.user', 'tontineSubscription.tontine', 'tontineSubscription.items.perfume']);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('late') && $request->late == '1') {
                $query->late();
            }

            $payments = $query->orderBy('due_date')->paginate(20);
        } else {
            $subscriptionIds = $user->subscriptions()->pluck('id');
            $query = Payment::with(['tontineSubscription.tontine', 'tontineSubscription.items.perfume'])
                ->whereIn('tontine_subscription_id', $subscriptionIds);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $payments = $query->orderBy('due_date')->paginate(20);
        }

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $payment->tontineSubscription->user_id !== auth()->id()) {
            abort(403);
        }

        $payment->load(['tontineSubscription.user', 'tontineSubscription.tontine', 'tontineSubscription.items.perfume', 'tontineSubscription.payments']);

        return view('payments.show', compact('payment'));
    }

    public function pay(Payment $payment)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $payment->tontineSubscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement a déjà été effectué.');
        }

        if ($payment->status === 'cancelled') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement a été annulé.');
        }

        // Charger les relations nécessaires
        $payment->load(['tontineSubscription.tontine', 'tontineSubscription.items.perfume']);

        return view('payments.pay', compact('payment'));
    }

    public function processPay(Request $request, Payment $payment)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $payment->tontineSubscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement a déjà été effectué.');
        }

        if ($payment->status === 'cancelled') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement a été annulé.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $payment->markAsPaid($validated['payment_method'], $validated['reference'] ?? null);

        // Vérifier si c'est le dernier paiement de la souscription
        $subscription = $payment->tontineSubscription;
        if ($subscription->isFullyPaid()) {
            $subscription->update(['status' => 'completed']);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function adminConfirm(Request $request, Payment $payment)
    {
        // Seuls les admins peuvent confirmer
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement a déjà été confirmé.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $payment->markAsPaid($validated['payment_method'], $validated['reference'] ?? null);

        // Vérifier si c'est le dernier paiement de la souscription
        $subscription = $payment->tontineSubscription;
        if ($subscription->isFullyPaid()) {
            $subscription->update(['status' => 'completed']);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Paiement confirmé avec succès.');
    }

    public function cancel(Payment $payment)
    {
        // Seuls les admins peuvent annuler
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Impossible d\'annuler un paiement déjà effectué.');
        }

        if ($payment->status === 'cancelled') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Ce paiement est déjà annulé.');
        }

        $payment->update(['status' => 'cancelled']);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Paiement annulé avec succès.');
    }

    public function markAsLate()
    {
        // Seuls les admins peuvent marquer comme en retard
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Trouver tous les paiements en attente dont la date est dépassée
        $latePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'late']);

        return redirect()->route('payments.index')
            ->with('success', "$latePayments paiement(s) marqué(s) comme en retard.");
    }

    /**
     * Afficher le formulaire de paiement multiple
     */
    public function payMultiple(TontineSubscription $subscription)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $subscription->user_id !== auth()->id()) {
            abort(403);
        }

        // Charger les relations nécessaires
        $subscription->load(['tontine', 'items.perfume']);

        // Récupérer tous les paiements en attente ou en retard
        $pendingPayments = $subscription->payments()
            ->whereIn('status', ['pending', 'late'])
            ->orderBy('payment_number')
            ->get();

        if ($pendingPayments->isEmpty()) {
            return redirect()->route('subscriptions.show', $subscription)
                ->with('error', 'Aucun paiement en attente pour cette souscription.');
        }

        return view('payments.pay-multiple', compact('subscription', 'pendingPayments'));
    }

    /**
     * Traiter le paiement multiple
     */
    public function processPayMultiple(Request $request, TontineSubscription $subscription)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_ids' => 'required|array|min:1',
            'payment_ids.*' => 'exists:payments,id',
            'payment_method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $paidCount = 0;
            $totalAmount = 0;

            foreach ($validated['payment_ids'] as $paymentId) {
                $payment = Payment::findOrFail($paymentId);

                // Vérifier que le paiement appartient bien à cette souscription
                if ($payment->tontine_subscription_id !== $subscription->id) {
                    throw new \Exception("Paiement non valide");
                }

                // Vérifier que le paiement n'est pas déjà payé ou annulé
                if ($payment->status === 'paid' || $payment->status === 'cancelled') {
                    continue;
                }

                $payment->markAsPaid($validated['payment_method'], $validated['reference'] ?? null);
                $paidCount++;
                $totalAmount += $payment->amount;
            }

            // Vérifier si la souscription est maintenant complètement payée
            if ($subscription->isFullyPaid()) {
                $subscription->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()->route('subscriptions.show', $subscription)
                ->with('success', "$paidCount paiement(s) enregistré(s) avec succès pour un montant total de " . number_format($totalAmount, 2) . " FCFA.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.show', $subscription)
                ->with('error', 'Une erreur est survenue lors du traitement des paiements.');
        }
    }
}
