<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'about',
        'phone',
        'address_id',
        'city',
        'address',
        'postal_code',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function balance()
    {
        return $this->hasOne(StoreBalance::class);
    }

    public function balanceHistories()
    {
        return $this->hasManyThrough(
            StoreBalanceHistory::class,
            StoreBalance::class,
            'store_id',
            'store_balance_id',
            'id',
            'id'
        );
    }

    public function withdrawals()
    {
        return $this->hasManyThrough(
            Withdrawal::class,
            StoreBalance::class,
            'store_id',
            'store_balance_id',
            'id',
            'id'
        );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
