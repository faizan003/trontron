<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'referral_code',
        'referred_by',
        'password',
        'total_earnings',
        'google2fa_secret',
        'google2fa_enabled'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'total_earnings' => 'decimal:6'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function stakings()
    {
        return $this->hasMany(Staking::class);
    }

    public function trxTransactions()
    {
        return $this->hasMany(TrxTransaction::class);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referralTransactions()
    {
        return $this->hasMany(ReferralEarning::class, 'referred_id');
    }

    public function earnedReferrals()
    {
        return $this->hasMany(ReferralEarning::class, 'referrer_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(WithdrawalHistory::class);
    }
}
