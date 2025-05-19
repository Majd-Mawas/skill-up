<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'course' => new CourseResource($this->whenLoaded('course')),
            'status' => $this->status,
            'enrollment_date' => $this->enrollment_date,
            'completion_date' => $this->completion_date,
            'grade' => $this->grade,
            'feedback' => $this->feedback,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
