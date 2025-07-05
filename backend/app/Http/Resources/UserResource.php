<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Identification
            'id' => $this->id,
            
            // Personal Data
            'full_name' => $this->full_name,
            'age' => $this->age,
            'profile_image' => $this->profile_image,
            'biography' => $this->biography,
            
            // Address
            'full_address' => $this->full_address, // Calculated complete address
            'address_details' => [
                'street' => $this->street,
                'neighborhood' => $this->neighborhood,
                'state' => $this->state,
            ],
            
            // Metadata
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
