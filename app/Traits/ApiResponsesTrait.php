<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponsesTrait
{
    public function successResponse($data, $message = 'Success', $status = 200, $response = true) : JsonResponse
    {
        return response()->json([
            'response'  => $response,
            'status'    => $status,
            'message'   => $message,
            'data'      => $data
        ], $status);
    }

    public function errorResponse($message = 'Error', $status = 400, $response = false): JsonResponse
    {
        return response()->json([
            'response'  => $response,
            'status'    => $status,
            'message'   => $message
        ], $status);
    }
}
