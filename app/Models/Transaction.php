<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'transaction_type',
        'user_id',
        'crypto_account_id',
        'coin_id'
    ];

    public function cryptoAccount()
    {
        return $this->belongsTo(CryptoAccount::class);
    }
}
