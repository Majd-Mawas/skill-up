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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'gender' => $this->gender,
            'study' => $this->study,
            'phone_verified' => $this->phone_verified,
            'email_verified_at' => $this->email_verified_at,
            'avatar' => [
                'original' => $this->avatar_url,
                'thumb' => $this->avatar_thumb_url,
                'medium' => $this->avatar_medium_url,
            ],
            'area' => new AreaResource($this->whenLoaded('area')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'interests' => InterestResource::collection($this->whenLoaded('interests')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
