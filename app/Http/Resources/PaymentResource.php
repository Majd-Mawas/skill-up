<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'payment_details' => $this->payment_details,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
