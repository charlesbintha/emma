<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TontineSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tontine_id',
        'user_id',
        'subscription_date',
        'status',
    ];

    protected $casts = [
        'subscription_date' => 'datetime',
    ];

    /**
     * Relation avec la tontine
     */
    public function tontine()
    {
        return $this->belongsTo(Tontine::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les items de la commande (parfums + quantités)
     */
    public function items()
    {
        return $this->hasMany(TontineSubscriptionItem::class);
    }

    /**
     * Relation avec les paiements
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope pour les souscriptions actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * V\u00e9rifier si tous les paiements sont effectu\u00e9s
     */
    public function isFullyPaid()
    {
        return $this->payments()->where('status', '!=', 'paid')->count() === 0;
    }

    /**
     * Calculer le montant total de la commande
     */
    public function totalAmount()
    {
        return $this->items()->sum('subtotal');
    }

    /**
     * Calculer le montant total payé
     */
    public function totalPaid()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }
}
