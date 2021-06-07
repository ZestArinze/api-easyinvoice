<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;

class AppHttpUtils {

    public static function appJsonResponse($status, $code = Response::HTTP_OK, $data = null, $error = null, $message = 'OK') {
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