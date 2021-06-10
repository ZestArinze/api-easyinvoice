<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_ISSUED = 'Issued';

    protected $fillable = [
        'summary',
        'vat',
        'status',
        'due_date',
        'currency_id',
        'total_paid',
    ];

    protected $dates = ['due_date'];
}
