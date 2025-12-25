<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeResource extends JsonResource
{
    /**
     * Get the correct image URL based on environment
     *
     * TEMPORARY FIX: Hostinger doesn't allow symlink creation via PHP
     * This will use the direct path until symlink is created
     */
    private function getImageUrl()
    {
        if (!$this->image_url) {
            return null;
        }

        // Check if storage symlink exists
        $symlinkExists = is_link(public_path('storage'));

        if ($symlinkExists) {
            // Symlink exists, use standard Laravel path
            return url('storage/' . $this->image_url);
        } else {
            // Symlink doesn't exist, use direct path (Hostinger workaround)
            return url('storage/app/public/' . $this->image_url);
        }
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
