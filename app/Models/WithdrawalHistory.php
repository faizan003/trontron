<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
    protected $table = 'withdrawal_history';

    protected $fillable = [
        'user_id',
        'amount',
        'original_amount',
        'fee',
        'address',
        'transaction_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
    }
}
