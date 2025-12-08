<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UserBalance;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    public function isSeller()
    {
        return $this->role === 'member'
            && $this->store
            && $this->store->is_verified;
    }

    // Relations
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(UserBalance::class, 'user_id');
    }
}
