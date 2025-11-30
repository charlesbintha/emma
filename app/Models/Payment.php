<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tontine_subscription_id',
        'payment_number',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'payment_method',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'datetime',
        'payment_number' => 'integer',
    ];

    /**
     * Relation avec la souscription
     */
    public function tontineSubscription()
    {
        return $this->belongsTo(TontineSubscription::class);
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les paiements effectu\u00e9s
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope pour les paiements en retard
     */
    public function scopeLate($query)
    {
        return $query->where('status', 'late')
            ->orWhere(function($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now());
            });
    }

    /**
     * V\u00e9rifier si le paiement est en retard
     */
    public function isLate()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    /**
     * Marquer comme pay\u00e9
     */
    public function markAsPaid($paymentMethod = null, $reference = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'reference' => $reference,
        ]);
    }
}
