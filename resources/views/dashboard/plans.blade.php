@extends('layouts.app')

@section('content')
<div id="notification-container" class="fixed top-0 left-0 right-0 z-50 pointer-events-none">
    <div class="max-w-sm mx-auto px-4 mt-4">
        <!-- Notifications will be inserted here -->
    </div>
</div>

<div class="min-h-screen bg-gray-50 pb-24 pt-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Available Balance Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600">Available for Staking</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format(auth()->user()->wallet->tronstake_balance ?? 0, 6) }} StakeTRX
                    </p>
                </div>
            </div>
        </div>

        <!-- Staking Plans -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $plan->duration }} Days Plan</p>
                            </div>
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ number_format($plan->interest_rate, 2) }}% Daily
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Minimum Stake</span>
                                <span class="font-medium">{{ number_format($plan->minimum_amount, 6) }} TRX</span>
                            </div>
                            @if($plan->maximum_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Maximum Stake</span>
                                    <span class="font-medium">{{ number_format($plan->maximum_amount, 6) }} TRX</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Daily Return</span>
                                <span class="font-medium text-green-600">
                                    {{ number_format($plan->minimum_amount * ($plan->interest_rate / 100), 6) }} TRX
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Return</span>
                                <span class="font-medium">
                                    {{ number_format(($plan->duration * $plan->interest_rate) / 100, 2) }}x
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            @if($plan->is_active)
                                <div class="space-y-4">
                                    <div class="relative">
                                        <input type="number"
                                               id="stake-amount-{{ $plan->id }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Enter stake amount"
                                               min="{{ $plan->minimum_amount }}"
                                               {{ $plan->maximum_amount > 0 ? "max={$plan->maximum_amount}" : '' }}
                                               step="0.000001"
                                               data-daily-roi="{{ $plan->interest_rate }}"
                                               data-duration="{{ $plan->duration }}"
                                               oninput="calculateReturns({{ $plan->id }})">
                                        <div class="text-xs text-gray-500 mt-1">
                                            Min: {{ number_format($plan->minimum_amount, 6) }} TRX
                                            @if($plan->maximum_amount > 0)
                                                | Max: {{ number_format($plan->maximum_amount, 6) }} TRX
                                            @endif
                                        </div>
                                    </div>
                                    <div id="returns-section-{{ $plan->id }}" class="hidden mt-4 bg-gray-50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Daily Return</span>
                                            <span class="text-green-600">+<span id="daily-return-{{ $plan->id }}">0.000000</span> TRX</span>
                                        </div>
                                        <div class="flex justify-between text-sm font-medium">
                                            <span class="text-gray-700">Total Return ({{ $plan->duration }} days)</span>
                                            <span class="text-green-600">+<span id="total-return-{{ $plan->id }}">0.000000</span> TRX</span>
                                        </div>
                                    </div>
                                    <button onclick="startStaking('{{ $plan->id }}')"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Stake Now
                                    </button>
                                </div>
                            @else
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed">
                                    Currently Unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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
        const availableBalance = {{ auth()->user()->wallet->tronstake_balance ?? 0 }};

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
                `Insufficient StakeTRX balance. Available: ${availableBalance} TRX\n\nWould you like to convert TRX to StakeTRX?`,
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
