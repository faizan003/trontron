@php
    use Illuminate\Support\Facades\Schema;
@endphp

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-24 pt-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Referral Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            @php
                $hasActiveStaking = auth()->user()->stakings()
                    ->where('status', 'active')
                    ->exists();
            @endphp

            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="text-sm font-medium text-gray-600">Total Referrals</h3>
                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->referrals()->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="text-sm font-medium text-gray-600">Total Earnings</h3>
                <p class="text-2xl font-bold text-gray-900">{{ number_format(auth()->user()->referral_earnings, 6) }} TRX</p>
            </div>
            <div class="col-span-2 md:col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h3 class="text-sm font-medium text-gray-600">Total Converted to StakeTRX</h3>
                <p class="text-2xl font-bold text-gray-900">
                    @php
                        $totalConverted = auth()->user()->trxTransactions()
                            ->where('type', 'referral_convert')
                            ->where('status', 'completed')
                            ->sum('amount');
                    @endphp
                    {{ number_format($totalConverted, 6) }} TRX
                </p>
                <p class="text-xs text-gray-500 mt-1">Successfully converted to StakeTRX</p>
            </div>
        </div>

        <!-- Share Referral Card -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl shadow-sm p-6 mb-6 text-white">
            <h2 class="text-lg font-semibold mb-2">Share & Earn</h2>
            <p class="text-sm opacity-90 mb-4">Get 5% of TRX when your referrals convert to StakeTRX</p>

            @if($hasActiveStaking)
                <div class="flex items-center space-x-2 bg-white/10 rounded-lg p-3 mb-4">
                    <span class="font-mono text-lg flex-1">{{ auth()->user()->referral_code }}</span>
                    <button onclick="copyToClipboard('{{ auth()->user()->referral_code }}')"
                        class="bg-white/20 p-2 rounded-lg hover:bg-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <button onclick="shareReferral()"
                class="w-full bg-white text-blue-600 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                Share Now
            </button>

            @if(!$hasActiveStaking)
                <div class="mt-4 bg-white/10 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-white/80 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-white/80">Activate a staking plan to unlock your referral code!</p>
                    </div>
                    <a href="{{ route('dashboard.plans') }}" class="mt-3 inline-block text-sm text-white/90 hover:text-white">
                        View Staking Plans →
                    </a>
                </div>
            @endif
        </div>

        <!-- Referral Earnings & Convert Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Earnings Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Referral Earnings</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Available Balance</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format(auth()->user()->referral_earnings, 6) }} TRX</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Minimum Required</span>
                        <span class="text-gray-900">100 TRX</span>
                    </div>
                </div>
            </div>

            <!-- Convert Card -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-sm p-6 text-white">
                <h3 class="text-lg font-semibold mb-2">Convert to StakeTRX</h3>
                <p class="text-sm opacity-90 mb-4">Convert your referral earnings to StakeTRX when you reach 100 TRX</p>

                @if(auth()->user()->referral_earnings >= 100)
                    <button onclick="convertEarnings()"
                        class="w-full bg-white text-green-600 py-3 rounded-lg font-medium hover:bg-green-50 transition-colors">
                        Convert {{ number_format(auth()->user()->referral_earnings, 6) }} TRX to StakeTRX
                    </button>
                @else
                    <div class="bg-white/10 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm">Progress to Convert</span>
                            <span class="text-sm">{{ floor((auth()->user()->referral_earnings / 100) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-white rounded-full h-2"
                                style="width: {{ min((auth()->user()->referral_earnings / 100) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    <button disabled
                        class="w-full bg-white/50 text-white py-3 rounded-lg font-medium cursor-not-allowed">
                        Need {{ number_format(100 - auth()->user()->referral_earnings, 6) }} more TRX
                    </button>
                @endif
            </div>
        </div>

        <!-- Conversion History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Conversion History</h2>
                <div class="flex space-x-2">
                    <a href="#referral-earnings" class="text-sm text-blue-600 hover:text-blue-700">
                        Referral Earnings
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="#conversion-history" class="text-sm text-blue-600 hover:text-blue-700">
                        Conversion History
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(auth()->user()->trxTransactions()->where('type', 'referral_convert')->latest()->get() as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ number_format($transaction->amount, 6) }} TRX
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Referral Convert
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No conversion history yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Referred Users List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Referred Users</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Earnings</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(auth()->user()->referrals as $referral)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm">
                                            {{ strtoupper(substr($referral->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $referral->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $referral->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $referral->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        @php
                                            $earnings = 0;
                                            if (Schema::hasTable('referral_earnings')) {
                                                $earnings = $referral->referralTransactions()->sum('amount');
                                            }
                                        @endphp
                                        {{ number_format($earnings, 6) }} TRX
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No referrals yet. Share your code to start earning!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('layouts.mobile-nav')

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Copied to clipboard!', 'success');
    });
}

async function shareReferral() {
    @if($hasActiveStaking)
        const referralCode = '{{ auth()->user()->referral_code }}';
        const shareText = `🚀 Join TronX - The Future of Staking!\n\n` +
                         `💎 Use my referral code: ${referralCode}\n` +
                         `💰 Daily Returns up to 2%\n` +
                         `💫 3x Returns on Investment\n` +
                         `🔄 Easy TRX to StakeTRX conversion\n` +
                         `🎁 Get bonuses for referrals\n\n` +
                         `📱 Join now:\n` +
                         `{{ url('/register') }}?ref=${referralCode}`;
    @else
        const shareText = `🚀 Join TronX - The Future of Staking!\n\n` +
                         `💰 Daily Returns up to 2%\n` +
                         `💫 3x Returns on Investment\n` +
                         `🔄 Easy TRX to StakeTRX conversion\n` +
                         `🎁 Get bonuses for referrals\n\n` +
                         `📱 Join now:\n` +
                         `{{ url('/register') }}`;
    @endif

    try {
        if (navigator.share) {
            await navigator.share({
                title: 'Join TronX',
                text: shareText,
                url: @if($hasActiveStaking)
                    `{{ url('/register') }}?ref={{ auth()->user()->referral_code }}`
                @else
                    `{{ url('/register') }}`
                @endif
            });
        } else {
            await navigator.clipboard.writeText(shareText);
            showNotification('Referral link copied to clipboard! Share it with your friends.', 'success');
        }
    } catch (error) {
        console.error('Error sharing:', error);
        try {
            await navigator.clipboard.writeText(shareText);
            showNotification('Referral link copied to clipboard!', 'success');
        } catch (clipboardError) {
            showNotification('Failed to share. Please try again.', 'error');
        }
    }
}

async function convertEarnings() {
    if (!confirm('Are you sure you want to convert your referral earnings to StakeTRX?')) {
        return;
    }

    try {
        const response = await fetch('{{ route("convert.referral.earnings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Successfully converted earnings to StakeTRX!', 'success');
            window.location.reload();
        } else {
            showNotification(result.message || 'Failed to convert earnings. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to convert earnings. Please try again.', 'error');
    }
}
</script>
@endsection
