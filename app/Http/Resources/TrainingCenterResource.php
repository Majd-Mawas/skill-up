<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingCenterResource extends JsonResource
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
            'description' => $this->description,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'website' => $this->website,
            'status' => $this->status,
            'area' => new AreaResource($this->whenLoaded('area')),
            'logo' => [
                'original' => $this->logo_url,
                'thumb' => $this->logo_thumb_url,
                'medium' => $this->logo_medium_url,
            ],
            'gallery' => $this->getMedia('gallery')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'original' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'medium' => $media->getUrl('medium'),
                ];
            }),
            'courses_count' => $this->whenCounted('courses'),
            'reviews_count' => $this->whenCounted('reviews'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
