<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('apiResponse')) {
    /**
     * Standardized API JSON response format.
     *
     * @param int $code      Custom code (usually same as HTTP status)
     * @param string $msg    Message describing the result
     * @param mixed|null $data Optional payload
     * @param int|null $httpStatus Optional HTTP status code (defaults to $code)
     * @return JsonResponse
     */
    function apiResponse(int $code, string $msg, $data = null, int $httpStatus = null): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ], $httpStatus ?? $code);
    }
}
