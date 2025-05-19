<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::with(['user', 'course'])->paginate(10);
        return $this->sendResponse(CertificateResource::collection($certificates), 'Certificates retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'certificate_number' => 'required|string|unique:certificates',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:active,expired,revoked',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $certificate = Certificate::create($request->all());
        return $this->sendResponse(new CertificateResource($certificate), 'Certificate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        return $this->sendResponse(
            new CertificateResource($certificate->load(['user', 'course'])),
            'Certificate retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'certificate_number' => 'sometimes|required|string|unique:certificates,certificate_number,' . $certificate->id,
            'issue_date' => 'sometimes|required|date',
            'expiry_date' => 'sometimes|required|date|after:issue_date',
            'status' => 'sometimes|required|in:active,expired,revoked',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $certificate->update($request->all());
        return $this->sendResponse(new CertificateResource($certificate), 'Certificate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return $this->sendResponse(null, 'Certificate deleted successfully.');
    }
}
