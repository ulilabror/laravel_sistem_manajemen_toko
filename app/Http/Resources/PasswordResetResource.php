<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PasswordResetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
