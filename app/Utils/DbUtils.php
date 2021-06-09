<?php

namespace App\Utils;

class DbUtils {
    

    public static function sqlLikeQuery(array $inputArray) {
        $colsLike = [];

        foreach($inputArray as $key => $value) {
            if($value === null) continue;
            $colsLike = array_merge($colsLike, [
                [$key, 'LIKE', '%' . $value . '%'],
            ]);
        }

        return $colsLike;
    }
}