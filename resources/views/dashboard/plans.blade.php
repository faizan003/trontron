@extends('layouts.app')

@section('content')
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none">
    <!-- Notifications will be inserted here -->
</div>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-sky-50 pb-24 pt-6 md:py-12 relative">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/3 via-transparent to-secondary-500/3"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Enhanced Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-3 mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-3xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-4">
                Staking Plans
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Choose from our professional staking plans designed to maximize your cryptocurrency earnings with daily rewards
            </p>
        </div>

        <!-- Enhanced Available Balance Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-800">Available for Staking</h3>
                        <p class="text-3xl font-bold text-neutral-900 mt-1">
                            {{ number_format(auth()->user()->wallet->miles_balance ?? 0, 6) }} <span class="text-lg text-neutral-600 font-medium">MSC</span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('dashboard.convert') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Convert TRX
                </a>
            </div>
        </div>

        <!-- Enhanced Staking Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 overflow-hidden group hover:shadow-2xl hover:shadow-slate-200/60 transition-all duration-300 hover:scale-[1.02]">
                    <!-- Enhanced Plan Header -->
                    <div class="bg-gradient-to-br from-primary-500 to-secondary-500 p-6 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">{{ $plan->name }}</h3>
                                    <p class="text-white/80 text-sm">{{ $plan->duration }} Days Plan</p>
                                </div>
                                <div class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold border border-white/30">
                                    {{ number_format($plan->interest_rate, 2) }}% Daily
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white mb-2">
                                    {{ number_format($plan->interest_rate, 2) }}%
                                </div>
                                <div class="text-white/80 text-sm">Daily Returns</div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Plan Details -->
                    <div class="p-6">
                        <!-- Plan Stats -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                                <span class="text-sm font-medium text-neutral-600">Minimum Stake</span>
                                <span class="font-semibold text-neutral-900">{{ number_format($plan->minimum_amount, 6) }} TRX</span>
                            </div>
                            @if($plan->maximum_amount > 0)
                                <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                                    <span class="text-sm font-medium text-neutral-600">Maximum Stake</span>
                                    <span class="font-semibold text-neutral-900">{{ number_format($plan->maximum_amount, 6) }} TRX</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                                <span class="text-sm font-medium text-neutral-600">Daily Return</span>
                                <span class="font-semibold text-success-600">
                                    +{{ number_format($plan->minimum_amount * ($plan->interest_rate / 100), 6) }} TRX
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-neutral-100">
                                <span class="text-sm font-medium text-neutral-600">Total Return</span>
                                <span class="font-semibold text-neutral-900">
                                    {{ number_format(($plan->duration * $plan->interest_rate) / 100, 2) }}x
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-neutral-600">Duration</span>
                                <span class="font-semibold text-neutral-900">{{ $plan->duration }} Days</span>
                            </div>
                        </div>

                        @if($plan->is_active)
                            <!-- Enhanced Staking Form -->
                            <div class="space-y-4">
                                <div class="relative group">
                                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Stake Amount</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="number"
                                               id="stake-amount-{{ $plan->id }}"
                                               class="w-full pl-12 pr-16 py-3 bg-white/60 border border-neutral-200 rounded-2xl text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                               placeholder="Enter amount"
                                               min="{{ $plan->minimum_amount }}"
                                               {{ $plan->maximum_amount > 0 ? "max={$plan->maximum_amount}" : '' }}
                                               step="0.000001"
                                               data-daily-roi="{{ $plan->interest_rate }}"
                                               data-duration="{{ $plan->duration }}"
                                               oninput="calculateReturns({{ $plan->id }})">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <span class="text-neutral-500 font-medium">TRX</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-neutral-500 mt-2 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Min: {{ number_format($plan->minimum_amount, 6) }} TRX
                                        @if($plan->maximum_amount > 0)
                                            | Max: {{ number_format($plan->maximum_amount, 6) }} TRX
                                        @endif
                                    </div>
                                </div>

                                <!-- Enhanced Returns Preview -->
                                <div id="returns-section-{{ $plan->id }}" class="hidden bg-gradient-to-br from-success-50/80 to-emerald-50/80 backdrop-blur-sm rounded-2xl p-4 border border-success-200/50">
                                    <h4 class="text-sm font-semibold text-success-800 mb-3">Expected Returns</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-success-700">Daily Return</span>
                                            <span class="font-bold text-success-600">+<span id="daily-return-{{ $plan->id }}">0.000000</span> TRX</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-success-700">Total Return ({{ $plan->duration }} days)</span>
                                            <span class="font-bold text-success-600">+<span id="total-return-{{ $plan->id }}">0.000000</span> TRX</span>
                                        </div>
                                        <div class="border-t border-success-200 pt-2 mt-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-success-800">ROI Multiple</span>
                                                <span class="font-bold text-success-900"><span id="roi-multiple-{{ $plan->id }}">0.00</span>x</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Stake Button -->
                                <button onclick="startStaking('{{ $plan->id }}')"
                                    class="w-full py-4 px-6 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 group">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Start Staking
                                    </div>
                                </button>
                            </div>
                        @else
                            <!-- Enhanced Disabled State -->
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-neutral-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-neutral-700 mb-2">Plan Temporarily Unavailable</h4>
                                <p class="text-sm text-neutral-500">This staking plan is currently not accepting new stakes</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Enhanced Footer Info -->
        <div class="mt-12 text-center">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 p-8">
                <h3 class="text-xl font-bold text-neutral-900 mb-4">Why Choose Our Staking Plans?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-neutral-800 mb-2">Secure & Transparent</h4>
                        <p class="text-sm text-neutral-600">Built on blockchain technology with full transparency</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-success-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-neutral-800 mb-2">Daily Rewards</h4>
                        <p class="text-sm text-neutral-600">Automated daily interest payments directly to your wallet</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-neutral-800 mb-2">High Returns</h4>
                        <p class="text-sm text-neutral-600">Competitive interest rates to maximize your earnings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.mobile-nav')

<style>
.notification {
    transform: translateY(-100%);
    animation: slideDown 0.3s ease-out forwards;
}

.notification.hide {
    animation: slideUp 0.3s ease-out forwards;
}

@keyframes slideDown {
    from { transform: translateY(-100%); }
    to { transform: translateY(0); }
}

@keyframes slideUp {
    from { transform: translateY(0); }
    to { transform: translateY(-100%); }
}

.confirmation-dialog {
    animation: scaleIn 0.2s ease-out;
}

@keyframes scaleIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
// Using global notification system from shared-functions.js

// Update the showConfirmation function
function showConfirmation(message, onConfirm, onCancel) {
    const dialog = document.createElement('div');
    dialog.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';

    dialog.innerHTML = `
        <div class="confirmation-dialog bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
            <div class="text-center mb-6">
                <svg class="w-12 h-12 mx-auto text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-900 font-medium whitespace-pre-line">${message}</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="handleCancel(this)" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200">
                    Cancel
                </button>
                <button onclick="handleConfirm(this)" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                    Confirm
                </button>
            </div>
        </div>
    `;

    // Store the actual callback functions
    dialog._confirmCallback = onConfirm;
    dialog._cancelCallback = onCancel;

    document.body.appendChild(dialog);
}

// Update the handleConfirm function
function handleConfirm(button) {
    const dialog = button.closest('.fixed');
    const callback = dialog._confirmCallback;
    dialog.remove();

    if (typeof callback === 'function') {
        callback();
    }
}

// Update the handleCancel function
function handleCancel(button) {
    const dialog = button.closest('.fixed');
    const callback = dialog._cancelCallback;
    dialog.remove();

    if (typeof callback === 'function') {
        callback();
    }
}

// Update the startStaking function
async function startStaking(planId) {
    const stakeButton = document.querySelector(`button[onclick="startStaking('${planId}')"]`);
    const originalButtonText = stakeButton.innerHTML;
    
    // Set loading state
    function setLoadingState(loading = true) {
        if (loading) {
            stakeButton.disabled = true;
            stakeButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        } else {
            stakeButton.disabled = false;
            stakeButton.innerHTML = originalButtonText;
        }
    }

    try {
        const inputElement = document.getElementById(`stake-amount-${planId}`);
        const stakeAmount = parseFloat(inputElement.value);
                    const availableBalance = {{ auth()->user()->wallet->miles_balance ?? 0 }};

        // Validate input amount
        if (isNaN(stakeAmount) || stakeAmount <= 0) {
            showNotification('Please enter a valid stake amount', 'error');
            return;
        }

        // Start loading state
        setLoadingState(true);

        // Get plan details
        const response = await fetch(`{{ url('/api/plans') }}/${planId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            setLoadingState(false);
            throw new Error('Failed to fetch plan details');
        }
        const plan = await response.json();

        // Validate minimum amount
        if (stakeAmount < plan.minimum_amount) {
            setLoadingState(false);
            showNotification(`Minimum stake amount is ${plan.minimum_amount} TRX`, 'error');
            return;
        }

        // Validate maximum amount if set
        if (plan.maximum_amount > 0 && stakeAmount > plan.maximum_amount) {
            setLoadingState(false);
            showNotification(`Maximum stake amount is ${plan.maximum_amount} TRX`, 'error');
            return;
        }

        // Check available balance
        if (stakeAmount > availableBalance) {
            setLoadingState(false);
            showConfirmation(
                `Insufficient MilesCoin balance. Available: ${availableBalance} MSC\n\nWould you like to convert TRX to MilesCoin?`,
                () => window.location.href = '{{ route("dashboard.convert") }}'
            );
            return;
        }

        // Stop loading for confirmation dialog
        setLoadingState(false);

        // Show staking confirmation
        showConfirmation(
            `Are you sure you want to stake ${stakeAmount} TRX?\n\nDaily Return: ${(stakeAmount * plan.interest_rate / 100).toFixed(6)} TRX\nDuration: ${plan.duration} days`,
            async () => {
                try {
                    // Start loading again for actual staking
                    setLoadingState(true);
                    
                    const stakeResponse = await fetch('{{ route("stake") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            plan_id: planId,
                            amount: stakeAmount
                        })
                    });

                    const result = await stakeResponse.json();

                    if (result.success) {
                        setLoadingState(false);
                        showNotification('Successfully staked! Your earnings will start accumulating now.', 'success');
                        setTimeout(() => window.location.href = '{{ route("dashboard") }}', 2000);
                    } else {
                        setLoadingState(false);
                        throw new Error(result.message || 'Failed to stake');
                    }
                } catch (error) {
                    setLoadingState(false);
                    showNotification(error.message || 'Failed to start staking. Please try again.', 'error');
                }
            },
            () => {
                // On cancel, ensure button is re-enabled
                setLoadingState(false);
            }
        );

    } catch (error) {
        setLoadingState(false);
        showNotification(error.message || 'Failed to start staking. Please try again.', 'error');
    }
}

// Add this function to calculate daily returns
function calculateReturns(planId) {
    const input = document.getElementById(`stake-amount-${planId}`);
    const amount = parseFloat(input.value) || 0;
    const dailyRoi = parseFloat(input.dataset.dailyRoi);
    const duration = parseInt(input.dataset.duration);

    const dailyReturn = (amount * dailyRoi) / 100;
    const totalReturn = (amount * dailyRoi * duration) / 100;

    // Update the display
    document.getElementById(`daily-return-${planId}`).textContent = dailyReturn.toFixed(6);
    document.getElementById(`total-return-${planId}`).textContent = totalReturn.toFixed(6);
    document.getElementById(`returns-section-${planId}`).classList.remove('hidden');
}
</script>
@endsection
