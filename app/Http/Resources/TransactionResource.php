<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'transaction_type' => $this->transaction_type,
            'payment_method' => $this->payment_method,
            'payment_id' => $this->payment_id,
            'transaction_date' => $this->transaction_date,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
            'product_price' => $this->product->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
