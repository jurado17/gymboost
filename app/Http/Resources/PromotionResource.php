<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'discount_code' =>$this->discount_code,
            'porcentual_discount' => $this->porcentual_discount,
            'active_until' => $this->active_until,
            'isActive' => $this->isActive,
            'created_at' => $this->created_at,
        ];
    }
}
