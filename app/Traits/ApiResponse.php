<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    protected function successResponse($data, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data instanceof JsonResource ? $data->response()->getData(true)['data'] : $data,
            'meta' => $this->getMetaData($data)
        ], $code);
    }

    protected function errorResponse(string $message, int $code, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors instanceof ValidationException) {
            $response['errors'] = $errors->errors();
            $code = 422;
        } elseif ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function getMetaData($data): ?array
    {
        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            return [
                'current_page' => $data->resource->currentPage(),
                'last_page' => $data->resource->lastPage(),
                'per_page' => $data->resource->perPage(),
                'total' => $data->resource->total(),
            ];
        }

        return null;
    }
}
