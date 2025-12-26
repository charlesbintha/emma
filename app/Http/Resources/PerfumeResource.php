<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeResource extends JsonResource
{
    /**
     * Get the correct image URL
     *
     * Le lien symbolique existe sur Hostinger, on utilise toujours le chemin standard
     */
    private function getImageUrl()
    {
        if (!$this->image_url) {
            return null;
        }

        // Le lien symbolique existe, utiliser le chemin standard Laravel
        return url('storage/' . $this->image_url);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'description' => $this->description,
            'price' => (float) $this->price,
            'prix_achat' => $this->prix_achat ? (float) $this->prix_achat : null,
            'stock_quantity' => $this->stock_quantity,
            'is_available' => (bool) $this->is_available,
            'image_url' => $this->image_url ? $this->getImageUrl() : null,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
