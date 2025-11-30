<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use App\Models\TontineSubscription;
use App\Models\TontineSubscriptionItem;
use App\Models\Perfume;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TontineSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $subscriptions = TontineSubscription::with(['user', 'tontine', 'items.perfume'])->latest()->paginate(20);
        } else {
            $subscriptions = $user->subscriptions()->with(['tontine', 'items.perfume'])->latest()->paginate(20);
        }

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Afficher le formulaire pour choisir des parfums (panier)
     */
    public function create(Request $request, Tontine $tontine)
    {
        // Vérifier que la tontine accepte encore des inscriptions
        if ($tontine->status !== 'pending' && $tontine->status !== 'active') {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Cette tontine n\'accepte plus de nouvelles inscriptions.');
        }

        if ($tontine->isFull()) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Cette tontine est complète.');
        }

        // Vérifier que l'utilisateur n'est pas déjà inscrit
        if ($tontine->subscriptions()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Vous êtes déjà inscrit à cette tontine.');
        }

        // Recherche et pagination des parfums
        $query = Perfume::available();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perfumes = $query->orderBy('name')->paginate(10)->withQueryString();

        // Récupérer le panier de la session
        $cart = Session::get('tontine_cart_' . $tontine->id, []);

        return view('subscriptions.create', compact('tontine', 'perfumes', 'cart'));
    }

    /**
     * Ajouter un parfum au panier
     */
    public function addToCart(Request $request, Tontine $tontine)
    {
        $validated = $request->validate([
            'perfume_id' => 'required|exists:perfumes,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $perfume = Perfume::findOrFail($validated['perfume_id']);

        // Vérifier le stock
        if ($perfume->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Stock insuffisant pour ce parfum.');
        }

        // Récupérer le panier actuel
        $cartKey = 'tontine_cart_' . $tontine->id;
        $cart = Session::get($cartKey, []);

        // Ajouter ou mettre à jour l'item dans le panier
        if (isset($cart[$perfume->id])) {
            $cart[$perfume->id]['quantity'] += $validated['quantity'];
        } else {
            $cart[$perfume->id] = [
                'perfume_id' => $perfume->id,
                'name' => $perfume->name,
                'brand' => $perfume->brand,
                'price' => $perfume->price,
                'quantity' => $validated['quantity'],
                'subtotal' => $perfume->price * $validated['quantity'],
                'image_url' => $perfume->image_url,
            ];
        }

        // Recalculer le subtotal
        $cart[$perfume->id]['subtotal'] = $cart[$perfume->id]['price'] * $cart[$perfume->id]['quantity'];

        Session::put($cartKey, $cart);

        return back()->with('success', 'Parfum ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d'un item dans le panier
     */
    public function updateCartItem(Request $request, Tontine $tontine, $perfumeId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cartKey = 'tontine_cart_' . $tontine->id;
        $cart = Session::get($cartKey, []);

        if (isset($cart[$perfumeId])) {
            $cart[$perfumeId]['quantity'] = $validated['quantity'];
            $cart[$perfumeId]['subtotal'] = $cart[$perfumeId]['price'] * $validated['quantity'];
            Session::put($cartKey, $cart);

            return back()->with('success', 'Quantité mise à jour !');
        }

        return back()->with('error', 'Item non trouvé dans le panier.');
    }

    /**
     * Supprimer un item du panier
     */
    public function removeFromCart(Tontine $tontine, $perfumeId)
    {
        $cartKey = 'tontine_cart_' . $tontine->id;
        $cart = Session::get($cartKey, []);

        if (isset($cart[$perfumeId])) {
            unset($cart[$perfumeId]);
            Session::put($cartKey, $cart);

            return back()->with('success', 'Parfum retiré du panier !');
        }

        return back()->with('error', 'Item non trouvé dans le panier.');
    }

    /**
     * Vider le panier
     */
    public function clearCart(Tontine $tontine)
    {
        $cartKey = 'tontine_cart_' . $tontine->id;
        Session::forget($cartKey);

        return back()->with('success', 'Panier vidé !');
    }

    /**
     * Valider la commande et créer la souscription
     */
    public function store(Request $request, Tontine $tontine)
    {
        // Récupérer le panier
        $cartKey = 'tontine_cart_' . $tontine->id;
        $cart = Session::get($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('subscriptions.create', $tontine)
                ->with('error', 'Votre panier est vide. Ajoutez au moins un parfum.');
        }

        // Vérifications
        if ($tontine->status !== 'pending' && $tontine->status !== 'active') {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Cette tontine n\'accepte plus de nouvelles inscriptions.');
        }

        if ($tontine->isFull()) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Cette tontine est complète.');
        }

        if ($tontine->subscriptions()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Vous êtes déjà inscrit à cette tontine.');
        }

        DB::beginTransaction();

        try {
            // Créer la souscription
            $subscription = TontineSubscription::create([
                'tontine_id' => $tontine->id,
                'user_id' => auth()->id(),
                'subscription_date' => now(),
                'status' => 'active',
            ]);

            // Créer les items de la commande
            $totalAmount = 0;
            foreach ($cart as $item) {
                TontineSubscriptionItem::create([
                    'tontine_subscription_id' => $subscription->id,
                    'perfume_id' => $item['perfume_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $totalAmount += $item['subtotal'];
            }

            // Nombre fixe de paiements : 4 tranches sur 45 jours
            // Paiement 1 : date de début (jour 0)
            // Paiement 2 : 15 jours après (jour 15)
            // Paiement 3 : 30 jours après (jour 30)
            // Paiement 4 : 45 jours après (jour 45)
            $numberOfPayments = 4;
            $installmentAmount = $totalAmount / $numberOfPayments;

            // Intervalles en jours : 0, 15, 30, 45
            $paymentIntervals = [0, 15, 30, 45];

            // Créer les 4 paiements
            for ($i = 1; $i <= $numberOfPayments; $i++) {
                // Pour la dernière tranche, ajuster pour éviter les erreurs d'arrondi
                $amount = ($i == $numberOfPayments)
                    ? ($totalAmount - ($installmentAmount * ($numberOfPayments - 1)))
                    : $installmentAmount;

                Payment::create([
                    'tontine_subscription_id' => $subscription->id,
                    'payment_number' => $i,
                    'amount' => round($amount, 2),
                    'due_date' => $tontine->start_date->copy()->addDays($paymentIntervals[$i - 1]),
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            // Vider le panier
            Session::forget($cartKey);

            return redirect()->route('subscriptions.show', $subscription)
                ->with('success', 'Inscription réussie à la tontine !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tontines.show', $tontine)
                ->with('error', 'Une erreur est survenue lors de l\'inscription: ' . $e->getMessage());
        }
    }

    public function show(TontineSubscription $subscription)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $subscription->load(['tontine', 'user', 'items.perfume', 'payments' => function($query) {
            $query->orderBy('payment_number');
        }]);

        return view('subscriptions.show', compact('subscription'));
    }

    public function cancel(TontineSubscription $subscription)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($subscription->status === 'cancelled') {
            return redirect()->route('subscriptions.show', $subscription)
                ->with('error', 'Cette souscription est déjà annulée.');
        }

        // Vérifier s'il y a des paiements effectués
        $paidPayments = $subscription->payments()->where('status', 'paid')->count();

        if ($paidPayments > 0 && !auth()->user()->isAdmin()) {
            return redirect()->route('subscriptions.show', $subscription)
                ->with('error', 'Impossible d\'annuler : des paiements ont déjà été effectués. Contactez un administrateur.');
        }

        DB::beginTransaction();

        try {
            // Annuler la souscription
            $subscription->update(['status' => 'cancelled']);

            // Annuler tous les paiements en attente
            $subscription->payments()->where('status', 'pending')->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('subscriptions.index')
                ->with('success', 'Souscription annulée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.show', $subscription)
                ->with('error', 'Une erreur est survenue lors de l\'annulation.');
        }
    }
}
