<?php

namespace App\Utils;

use Illuminate\Validation\ValidationException;

class ErrorUtils {
    /**
     * 
     * format and return validation messages
     * @return array
     */
    public static function formatErrorBlock(ValidationException $exception): array {
        // extract errors into array
        $errors = $exception->validator->errors()->toArray();
        $errorResponse = [];

        foreach ($errors as $field => $message) {
            $errorField = ['field' => $field];
            foreach ($message as $key => $msg) {
                if ($key) {
                    $errorField['message' . $key] = $msg;
                } else {
                    $errorField['message'] = $msg;
                }
            }
            $errorResponse[] = $errorField;
        }

        return $errorResponse;
    }
}