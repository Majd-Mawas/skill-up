<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'invoice_number' => $this->invoice_number,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'paid_date' => $this->paid_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
