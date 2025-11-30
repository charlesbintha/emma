<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TontineSubscriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tontine_subscription_id',
        'perfume_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relation avec la souscription
     */
    public function tontineSubscription()
    {
        return $this->belongsTo(TontineSubscription::class);
    }

    /**
     * Relation avec le parfum
     */
    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }
}
