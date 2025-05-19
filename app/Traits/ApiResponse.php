<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Format the data for the response.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function formatData($data)
    {
        if ($data instanceof JsonResource) {
            return $data->response()->getData(true)['data'];
        }

        if ($data instanceof ResourceCollection) {
            return $data->response()->getData(true);
        }

        if ($data instanceof LengthAwarePaginator) {
            return [
                'data' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                ],
            ];
        }

        if ($data instanceof Collection) {
            return $data->toArray();
        }

        return $data;
    }
}
