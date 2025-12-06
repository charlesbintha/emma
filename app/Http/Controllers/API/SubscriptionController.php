<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TontineSubscriptionResource;
use App\Models\Perfume;
use App\Models\Tontine;
use App\Models\TontineSubscription;
use App\Models\TontineSubscriptionItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    // ========== CART METHODS ==========

    /**
     * Get cart for a tontine
     */
    public function getCart(Request $request, $tontineId)
    {
        $cart = session()->get("cart_tontine_{$tontineId}", []);
        $total = 0;
        $items = [];

        foreach ($cart as $perfumeId => $item) {
            $perfume = Perfume::find($perfumeId);
            if ($perfume) {
                $subtotal = $perfume->price * $item['quantity'];
                $total += $subtotal;
                $items[] = [
                    'perfume_id' => $perfumeId,
                    'perfume' => [
                        'id' => $perfume->id,
                        'name' => $perfume->name,
                        'brand' => $perfume->brand,
                        'price' => (float) $perfume->price,
                        'image_url' => $perfume->image_url ? url('storage/' . $perfume->image_url) : null,
                    ],
                    'quantity' => $item['quantity'],
                    'unit_price' => (float) $perfume->price,
                    'subtotal' => (float) $subtotal,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'payment_per_installment' => $total > 0 ? $total / 4 : 0,
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request, $tontineId)
    {
        $validator = Validator::make($request->all(), [
            'perfume_id' => 'required|exists:perfumes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $perfume = Perfume::find($request->perfume_id);

        if (!$perfume->is_available || $perfume->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Perfume not available or insufficient stock'
            ], 400);
        }

        $cart = session()->get("cart_tontine_{$tontineId}", []);

        if (isset($cart[$request->perfume_id])) {
            $cart[$request->perfume_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->perfume_id] = [
                'quantity' => $request->quantity,
            ];
        }

        session()->put("cart_tontine_{$tontineId}", $cart);

        return $this->getCart($request, $tontineId);
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $tontineId, $perfumeId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = session()->get("cart_tontine_{$tontineId}", []);

        if (!isset($cart[$perfumeId])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }

        $cart[$perfumeId]['quantity'] = $request->quantity;
        session()->put("cart_tontine_{$tontineId}", $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request, $tontineId, $perfumeId)
    {
        $cart = session()->get("cart_tontine_{$tontineId}", []);

        if (isset($cart[$perfumeId])) {
            unset($cart[$perfumeId]);
            session()->put("cart_tontine_{$tontineId}", $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear cart
     */
    public function clearCart(Request $request, $tontineId)
    {
        session()->forget("cart_tontine_{$tontineId}");

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    // ========== SUBSCRIPTION METHODS ==========

    /**
     * Subscribe to a tontine (confirm cart)
     */
    public function subscribe(Request $request, $tontineId)
    {
        $tontine = Tontine::find($tontineId);

        if (!$tontine) {
            return response()->json([
                'success' => false,
                'message' => 'Tontine not found'
            ], 404);
        }

        if ($tontine->status !== 'active' && $tontine->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This tontine is not accepting subscriptions'
            ], 400);
        }

        $cart = session()->get("cart_tontine_{$tontineId}", []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Create subscription
            $subscription = TontineSubscription::create([
                'user_id' => $request->user()->id,
                'tontine_id' => $tontineId,
                'subscription_date' => now(),
                'status' => 'active',
            ]);

            $totalAmount = 0;

            // Create subscription items
            foreach ($cart as $perfumeId => $item) {
                $perfume = Perfume::find($perfumeId);
                if ($perfume) {
                    $subtotal = $perfume->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    TontineSubscriptionItem::create([
                        'tontine_subscription_id' => $subscription->id,
                        'perfume_id' => $perfumeId,
                        'quantity' => $item['quantity'],
                        'unit_price' => $perfume->price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // Create 4 payments
            $paymentAmount = $totalAmount / 4;
            $paymentDates = $tontine->getPaymentDueDates();

            foreach ($paymentDates as $dueDate) {
                Payment::create([
                    'tontine_subscription_id' => $subscription->id,
                    'amount' => $paymentAmount,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);
            }

            // Clear cart
            session()->forget("cart_tontine_{$tontineId}");

            DB::commit();

            $subscription->load(['tontine', 'items.perfume', 'payments']);

            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully',
                'data' => new TontineSubscriptionResource($subscription)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's subscriptions
     */
    public function index(Request $request)
    {
        $subscriptions = TontineSubscription::with(['tontine', 'items.perfume', 'payments'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => TontineSubscriptionResource::collection($subscriptions)
        ]);
    }

    /**
     * Get subscription details
     */
    public function show(Request $request, $id)
    {
        $subscription = TontineSubscription::with(['tontine', 'items.perfume', 'payments'])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TontineSubscriptionResource($subscription)
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request, $id)
    {
        $subscription = TontineSubscription::where('user_id', $request->user()->id)->find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        if ($subscription->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Subscription already cancelled'
            ], 400);
        }

        $subscription->status = 'cancelled';
        $subscription->save();

        // Cancel pending payments
        Payment::where('tontine_subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully'
        ]);
    }
}
