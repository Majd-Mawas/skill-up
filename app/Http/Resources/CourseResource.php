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
            'price' => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'training_center' => new TrainingCenterResource($this->whenLoaded('trainingCenter')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
