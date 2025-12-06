<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TontineSubscriptionResource extends JsonResource
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
            'tontine' => new TontineResource($this->whenLoaded('tontine')),
            'items' => TontineSubscriptionItemResource::collection($this->whenLoaded('items')),
            'total_amount' => (float) $this->totalAmount(),
            'total_paid' => (float) $this->totalPaid(),
            'status' => $this->status,
            'subscription_date' => $this->subscription_date?->format('Y-m-d'),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
