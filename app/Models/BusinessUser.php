<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessUser extends Model
{
    use HasFactory;

    protected $table = 'business_user';

    public static function userBusiness($businessId) {

        return BusinessUser::where([
            'business_id'   => $businessId,
            'user_id'       => auth()->id(),
        ])->first();
    }
}
