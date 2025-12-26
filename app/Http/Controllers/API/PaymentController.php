<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\TontineSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Get all user's payments
     */
    public function index(Request $request)
    {
        $query = Payment::whereHas('subscription', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('subscription.tontine');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('due_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => PaymentResource::collection($payments)
        ]);
    }

    /**
     * Get payment details
     */
    public function show(Request $request, $id)
    {
        $payment = Payment::whereHas('subscription', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('subscription.tontine')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PaymentResource($payment)
        ]);
    }

    /**
     * Get payments for a specific subscription
     */
    public function getPaymentsBySubscription(Request $request, $subscriptionId)
    {
        $subscription = TontineSubscription::where('user_id', $request->user()->id)
            ->find($subscriptionId);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        $payments = Payment::where('tontine_subscription_id', $subscriptionId)
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => PaymentResource::collection($payments)
        ]);
    }

    /**
     * Make a payment
     */
    public function pay(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'reference' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $payment = Payment::whereHas('subscription', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Payment already made'
            ], 400);
        }

        if ($payment->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Payment has been cancelled'
            ], 400);
        }

        // Mark payment as paid
        $payment->markAsPaid($request->payment_method, $request->reference);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'data' => new PaymentResource($payment)
        ]);
    }
}
