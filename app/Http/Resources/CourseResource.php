<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'duration_hours' => $this->duration_hours,
            'difficulty_level' => $this->difficulty_level,
            'prerequisites' => $this->prerequisites,
            'learning_outcomes' => $this->learning_outcomes,
            'is_active' => $this->is_active,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'training_centers' => TrainingCenterResource::collection($this->whenLoaded('trainingCenters')),
            'thumbnail' => [
                'original' => $this->thumbnail_url,
                'thumb' => $this->thumbnail_thumb_url,
                'medium' => $this->thumbnail_medium_url,
            ],
            'gallery' => $this->getMedia('gallery')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'original' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'medium' => $media->getUrl('medium'),
                ];
            }),
            'materials' => $this->getMedia('materials')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'url' => $media->getUrl(),
                ];
            }),
            'enrollments_count' => $this->whenCounted('enrollments'),
            'reviews_count' => $this->whenCounted('reviews'),
            'average_rating' => $this->whenLoaded('reviews', function () {
                return $this->reviews->avg('rating');
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
