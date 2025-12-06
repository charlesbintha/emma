<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeResource extends JsonResource
{
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
            'image_url' => $this->image_url ? url('storage/' . $this->image_url) : null,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
