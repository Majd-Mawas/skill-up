<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['user', 'course', 'payment'])->paginate(10);
        return $this->sendResponse(InvoiceResource::collection($invoices), 'Invoices retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'payment_id' => 'nullable|exists:payments,id',
            'invoice_number' => 'required|string|unique:invoices',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $invoice = Invoice::create($request->all());
        return $this->sendResponse(new InvoiceResource($invoice), 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return $this->sendResponse(
            new InvoiceResource($invoice->load(['user', 'course', 'payment'])),
            'Invoice retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'payment_id' => 'nullable|exists:payments,id',
            'invoice_number' => 'sometimes|required|string|unique:invoices,invoice_number,' . $invoice->id,
            'subtotal' => 'sometimes|required|numeric|min:0',
            'tax' => 'sometimes|required|numeric|min:0',
            'total' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:pending,paid,cancelled',
            'due_date' => 'sometimes|required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $invoice->update($request->all());
        return $this->sendResponse(new InvoiceResource($invoice), 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return $this->sendResponse(null, 'Invoice deleted successfully.');
    }
}
