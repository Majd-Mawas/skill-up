<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['user', 'course'])->paginate(10);
        return $this->sendResponse(PaymentResource::collection($payments), 'Payments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
            'transaction_id' => 'required|string|unique:payments',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_details' => 'nullable|json',
            'paid_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $payment = Payment::create($request->all());
        return $this->sendResponse(new PaymentResource($payment), 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $this->sendResponse(
            new PaymentResource($payment->load(['user', 'course'])),
            'Payment retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|in:credit_card,bank_transfer,cash',
            'transaction_id' => 'sometimes|required|string|unique:payments,transaction_id,' . $payment->id,
            'status' => 'sometimes|required|in:pending,completed,failed,refunded',
            'payment_details' => 'nullable|json',
            'paid_at' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $payment->update($request->all());
        return $this->sendResponse(new PaymentResource($payment), 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return $this->sendResponse(null, 'Payment deleted successfully.');
    }
}
