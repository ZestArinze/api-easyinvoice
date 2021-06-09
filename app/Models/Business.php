<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    // fields that are mass-assignable
    protected $fillable = [
        'business_name',
        'email',
        'address',
        'phone_number',
    ];

    public function clients() {
        return $this->hasMany(Client::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
