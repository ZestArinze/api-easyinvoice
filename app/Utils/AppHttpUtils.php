<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppHttpUtils {

    /**
     * 
     * @param bool $status
     * @param int $code status code
     * @param mixed $data
     * @param mixed $error
     * @param string $message
     */
    public static function appJsonResponse($status, $code = Response::HTTP_OK, $data = null, $error = null, $message = 'OK'): JsonResponse {
        if($error && ! $message) {
            $message = 'Error';
        }

        return response()->json([
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'message' => $message,
        ], $code);
    }

}