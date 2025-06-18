<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StakingPlan extends Model
{
    protected $fillable = [
        'name',
        'minimum_amount',
        'maximum_amount',
        'interest_rate',
        'duration',
        'is_active'
    ];

    protected $casts = [
        'minimum_amount' => 'decimal:6',
        'maximum_amount' => 'decimal:6',
        'interest_rate' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean'
    ];

    public function stakings()
    {
        return $this->hasMany(Staking::class, 'plan_id');
    }
}
