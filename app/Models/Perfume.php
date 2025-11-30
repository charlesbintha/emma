<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfume extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'brand',
        'description',
        'price',
        'image_url',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Relation avec le fournisseur
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relation avec les items de souscription de tontine
     */
    public function subscriptionItems()
    {
        return $this->hasMany(TontineSubscriptionItem::class);
    }

    /**
     * Relation avec les souscriptions de tontine (via la table pivot items)
     */
    public function tontineSubscriptions()
    {
        return $this->belongsToMany(
            TontineSubscription::class,
            'tontine_subscription_items',
            'perfume_id',
            'tontine_subscription_id'
        )->withPivot('quantity', 'unit_price', 'subtotal');
    }

    /**
     * Scope pour les parfums disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock_quantity', '>', 0);
    }
}
