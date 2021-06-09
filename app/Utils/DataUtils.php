<?php

namespace App\Utils;

class DataUtils {
    public static function nonNullFields(array $data) {
        $result = [];
        foreach ($data as $key => $value) {
            if($value !== null) {
                $result[$key] = $value;
            } 
        }

        return $result;
    }
}