<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_type' => $this->product_type,
            'product_sku' => $this->product_sku,
            'product_label' => $this->product_label,
            'product_barcode_id' => $this->product_barcode_id,
            'product_description' => $this->product_description,
            'product_price' => $this->product_description,
            'price' => $this->price,
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' =>  FileResource::collection($this->whenLoaded('files')),
        ];
    }
}
