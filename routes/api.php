<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StakeTrxController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/convert-to-staketrx', [StakeTrxController::class, 'convert']);
});

// Add withdrawal status API route
Route::middleware('auth')->get('/withdrawal-status', function () {
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
});
