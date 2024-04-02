<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'receipt_no' => $this->receipt_no,
            'order_type' => $this->order_type,
            'total_order' => $this->total_order,
            'details' => OrderResource::collection($this->whenLoaded('orders')),
            'order_at' => $this->sold_at,
            'uuid' => $this->uuid,
        ];
    }
}
