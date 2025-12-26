<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tontine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Durée de la tontine en jours (45 jours = 1 mois et 15 jours)
     */
    const DURATION_DAYS = 45;

    /**
     * Calculer automatiquement la date de fin (45 jours après le début)
     */
    public function calculateEndDate()
    {
        if ($this->start_date) {
            return $this->start_date->copy()->addDays(self::DURATION_DAYS);
        }
        return null;
    }

    /**
     * Obtenir les dates d'échéance des paiements
     * Retourne un tableau avec les 4 dates: J0, J15, J30, J45
     */
    public function getPaymentDueDates()
    {
        if (!$this->start_date) {
            return [];
        }

        return [
            $this->start_date->copy(),                    // Paiement 1 : Jour 0
            $this->start_date->copy()->addDays(15),       // Paiement 2 : Jour 15
            $this->start_date->copy()->addDays(30),       // Paiement 3 : Jour 30
            $this->start_date->copy()->addDays(45),       // Paiement 4 : Jour 45
        ];
    }

    /**
     * Vérifier si la tontine est terminée
     */
    public function isEnded()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Relation avec les souscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(TontineSubscription::class);
    }

    /**
     * Relation avec les participants (users) via les souscriptions
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'tontine_subscriptions')
            ->withPivot('subscription_date', 'status')
            ->withTimestamps();
    }

    /**
     * Scope pour les tontines actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les tontines en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Vérifier si la tontine est pleine
     * Note: Participants illimités, retourne toujours false
     */
    public function isFull()
    {
        return false;
    }
}
