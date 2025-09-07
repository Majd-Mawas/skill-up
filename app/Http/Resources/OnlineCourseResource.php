<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnlineCourseResource extends JsonResource
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
            'is_online' => $this->is_online,
            'price' => $this->online_price,
            'start_date' => $this->online_start_date,
            'end_date' => $this->online_end_date,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'trainers' => UserResource::collection($this->whenLoaded('trainers')),
            'gallery' => $this->whenNotNull($this->getMedia('gallery'), function () {
                return $this->getMedia('gallery')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'original' => $media->getUrl(),
                        'thumb' => $media->getUrl('thumb'),
                        'medium' => $media->getUrl('medium'),
                    ];
                });
            }),
            'materials' => $this->whenNotNull($this->getMedia('materials'), function () {
                return $this->getMedia('materials')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'name' => $media->name,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                    ];
                });
            }),
            'online_course_bookings_count' => $this->whenCounted('onlineCourseBookings'),
            'reviews_count' => $this->whenCounted('reviews'),
            'average_rating' => $this->whenLoaded('reviews', function () {
                return $this->reviews->avg('rating');
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}