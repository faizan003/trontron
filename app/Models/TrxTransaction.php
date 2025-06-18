<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'type',
        'status',
        'description',
        'address'
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
