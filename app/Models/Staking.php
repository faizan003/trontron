<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'earned_amount',
        'status',
        'staked_at',
        'end_at'
    ];

    protected $casts = [
        'amount' => 'decimal:6',
        'earned_amount' => 'decimal:6',
        'staked_at' => 'datetime',
        'end_at' => 'datetime',
        'last_reward_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(StakingPlan::class, 'plan_id');
    }

    public function calculateEarnings()
    {
        if ($this->status !== 'active') {
            return $this->earned_amount;
        }

        $duration = now()->diffInDays($this->staked_at);
        $dailyRate = $this->reward_rate / 365;
        $earnings = $this->amount * ($dailyRate / 100) * $duration;

        return $earnings;
    }

    public function isRewardDue()
    {
        if (!$this->last_reward_at) {
            return true;
        }

        return now()->diffInHours($this->last_reward_at) >= 24;
    }
}
