<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\User;
use App\Models\Store;
use App\Models\TransactionDetail;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'buyer_id',
        'store_id',
        'address_id',
        'address',
        'city',
        'postal_code',
        'shipping',
        'shipping_type',
        'shipping_cost',
        'tracking_number',
        'tax',
        'grand_total',
        'payment_status',
        'recipient_name',
        'payment_method',
        'va_number',
    ];

    // Pembeli
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Store pemilik transaksi
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function details()
    {
        // alias supaya kode lama tetap jalan
        return $this->hasMany(TransactionDetail::class);
    }


    // Detail item pesanan
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
