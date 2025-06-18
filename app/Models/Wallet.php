<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'private_key',
        'balance'
    ];

    protected $hidden = [
        'private_key',
    ];

    protected $casts = [
        'balance' => 'decimal:6',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
