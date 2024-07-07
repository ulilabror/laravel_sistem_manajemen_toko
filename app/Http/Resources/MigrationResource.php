<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MigrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'migration' => $this->migration,
            'batch' => $this->batch,
        ];
    }
}
