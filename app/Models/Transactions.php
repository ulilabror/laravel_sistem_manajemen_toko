<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'transaction_type', 'payment_method', 'transaction_date', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
