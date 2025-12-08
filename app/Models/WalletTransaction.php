<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'transaction_type',
        'amount',
        'status',
        'va_number',
        'description',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(UserBalance::class, 'wallet_id');
    }
}
