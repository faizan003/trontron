<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StakingController;
use App\Http\Controllers\Api\StakeTrxController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\StakingPlanController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\Google2FAController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ContractBalanceController;


Route::get('/', function () {
    return view('welcome');
});

// Note: Public API Configuration routes removed due to Hostinger restrictions
// Registration now uses embedded configuration for better compatibility

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard');
    Route::get('/dashboard/convert', [DashboardController::class, 'convert'])->name('dashboard.convert');
    Route::get('/dashboard/referrals', [DashboardController::class, 'referrals'])->name('dashboard.referrals');
    Route::get('/dashboard/stake', [DashboardController::class, 'stake'])->name('dashboard.stake');
    Route::get('/dashboard/plans', [DashboardController::class, 'plans'])->name('dashboard.plans');
    Route::get('/dashboard/withdraw', [DashboardController::class, 'withdraw'])->name('dashboard.withdraw');

    // Staking Routes
    Route::post('/stake', [StakingController::class, 'stake'])->name('stake');
    Route::get('/staking/stats', [StakingController::class, 'getStakingStats'])->name('staking.stats');
    Route::post('/staking/{staking}/withdraw', [StakingController::class, 'withdraw'])->name('staking.withdraw');

    // TRX Conversion Routes
    Route::post('/convert-to-staketrx', [StakeTrxController::class, 'convert'])->name('convert.staketrx');
    Route::get('/convert-status', [StakeTrxController::class, 'getConversionStatus'])->name('convert.status');
    Route::post('/convert-referral-earnings', [StakeTrxController::class, 'convertReferralEarnings'])->name('convert.referral.earnings');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::post('/verify-password', function (Request $request) {
        if (Hash::check($request->password, auth()->user()->password)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    })->name('verify.password');

    // Add the plans API route here
    Route::get('/api/plans/{plan}', function (App\Models\StakingPlan $plan) {
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'minimum_amount' => $plan->minimum_amount,
            'maximum_amount' => $plan->maximum_amount,
            'interest_rate' => $plan->interest_rate,
            'duration' => $plan->duration
        ]);
    })->name('api.plans.show');

    Route::post('/withdraw-earnings', [WithdrawController::class, 'withdraw'])->name('withdraw.earnings');

    // Google 2FA Routes
    Route::get('/2fa/setup', [Google2FAController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/verify', [Google2FAController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/disable', [Google2FAController::class, 'disable'])->name('2fa.disable');
    Route::post('/2fa/validate-withdraw', [Google2FAController::class, 'validateWithdraw'])->name('2fa.validate.withdraw');
    
      Route::get('/api/contract-balance/{address}', [ContractBalanceController::class, 'getBalance'])
        ->name('api.contract.balance')
        ->where('address', 'T[A-Za-z0-9]{33}');
    
    // Secure API Configuration (authenticated)
    Route::get('/api/config', [App\Http\Controllers\SecureApiController::class, 'getApiConfig'])
        ->name('api.config');
    
    // Secure Private Key Access
    Route::post('/api/secure/private-key', [App\Http\Controllers\SecureWalletController::class, 'getPrivateKey'])
        ->name('api.secure.private-key');
        
    // Withdrawal status route for chatbot
    Route::get('/api/withdrawal-status', function () {
        $user = auth()->user();
        
        // Get the latest withdrawal for this user
        $latestWithdrawal = $user->withdrawals()
            ->latest()
            ->first();
        
        if (!$latestWithdrawal) {
            return response()->json([
                'success' => true,
                'has_withdrawal' => false,
                'message' => 'No withdrawals found'
            ]);
        }
        
        // Calculate time since withdrawal
        $timeSinceWithdrawal = $latestWithdrawal->created_at->diffInMinutes(now());
        $timeAgo = $latestWithdrawal->created_at->diffForHumans();
        
        return response()->json([
            'success' => true,
            'has_withdrawal' => true,
            'withdrawal' => [
                'id' => $latestWithdrawal->id,
                'amount' => number_format($latestWithdrawal->amount, 6),
                'original_amount' => number_format($latestWithdrawal->original_amount, 6),
                'fee' => number_format($latestWithdrawal->fee, 6),
                'address' => $latestWithdrawal->address,
                'status' => $latestWithdrawal->status,
                'transaction_id' => $latestWithdrawal->transaction_id,
                'created_at' => $latestWithdrawal->created_at->format('M d, Y H:i'),
                'time_ago' => $timeAgo,
                'minutes_since' => $timeSinceWithdrawal
            ]
        ]);
    })->name('api.withdrawal.status');
});

// Admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('plans', StakingPlanController::class);
});

// Password Reset Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');
