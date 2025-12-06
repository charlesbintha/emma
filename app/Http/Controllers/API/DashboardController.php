<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TontineSubscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get user dashboard statistics
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Count subscriptions
        $activeSubscriptions = TontineSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        $completedSubscriptions = TontineSubscription::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Get payments statistics
        $pendingPayments = Payment::whereHas('subscription', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'pending')->count();

        $latePayments = Payment::whereHas('subscription', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'late')->count();

        $totalPaid = Payment::whereHas('subscription', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'paid')->sum('amount');

        $totalDue = Payment::whereHas('subscription', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereIn('status', ['pending', 'late'])->sum('amount');

        // Get upcoming payment
        $upcomingPayment = Payment::whereHas('subscription', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'active');
        })
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->with('subscription.tontine')
            ->first();

        $upcomingPaymentData = null;
        if ($upcomingPayment) {
            $upcomingPaymentData = [
                'id' => $upcomingPayment->id,
                'amount' => (float) $upcomingPayment->amount,
                'due_date' => $upcomingPayment->due_date?->format('Y-m-d'),
                'days_until_due' => now()->diffInDays($upcomingPayment->due_date, false),
                'subscription' => [
                    'id' => $upcomingPayment->subscription->id,
                    'tontine_name' => $upcomingPayment->subscription->tontine->name,
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'active_subscriptions' => $activeSubscriptions,
                'completed_subscriptions' => $completedSubscriptions,
                'pending_payments' => $pendingPayments,
                'late_payments' => $latePayments,
                'total_paid' => (float) $totalPaid,
                'total_due' => (float) $totalDue,
                'upcoming_payment' => $upcomingPaymentData,
            ]
        ]);
    }
}
