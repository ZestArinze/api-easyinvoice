<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone_number', 
    ];

    public function business() {
        return $this->belongsTo(Business::class);
    }

    public static function clientRecord($email, $businessId) {
        return Client::where([
            'email'         => $email,
            'business_id'   => $businessId
        ])->first();
    }
}
