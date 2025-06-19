@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 pb-32 md:pb-8">
        <div class="space-y-6">
            <!-- Balance Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 md:p-6">
                    @if(auth()->user()->wallet)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- TRX Balance -->
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-base md:text-lg font-semibold text-gray-900">TRX Balance</h2>
                                    <button onclick="refreshBalance()" id="refresh-button" class="text-blue-600 hover:text-blue-700 transition-all duration-200 transform hover:scale-110" title="Refresh balance">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span id="wallet-balance" class="text-2xl font-bold text-gray-900">0.000000</span>
                                    <span class="text-gray-600">TRX</span>
                                </div>
                                <div id="balance-status" class="text-xs text-gray-500 mt-1"></div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Balance updates may take 2-5 minutes to reflect
                                    </span>
                                </div>
                            </div>

                            <!-- StakeTRX Balance -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-base md:text-lg font-semibold text-gray-900">StakeTRX Balance</h2>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ number_format(auth()->user()->wallet->tronstake_balance ?? 0, 6) }}
                                    </span>
                                    <span class="text-gray-600">StakeTRX</span>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="bg-red-50 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm text-red-600 font-medium">No wallet found. Please contact support.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Convert Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 space-y-3 md:p-6 md:space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base md:text-lg font-semibold text-gray-900">Convert TRX</h2>
                        <div class="p-2 bg-purple-100 rounded-full">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col space-y-4">
                            <div class="w-full">
                                <label for="convert-amount" class="block text-sm font-medium text-gray-700 mb-2">Amount to Convert</label>
                                <div class="relative rounded-lg">
                                    <input type="number" id="convert-amount"
                                        class="block w-full pl-4 pr-12 py-3 text-lg bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        step="0.000001" min="1" max="{{ auth()->user()->wallet->balance }}" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-gray-500 font-medium">TRX</span>
                                    </div>
                                </div>
                            </div>

                            <button id="convert-button" onclick="checkAndConvert()"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg text-base font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <span id="convert-button-text">Convert to StakeTRX</span>
                            </button>
                            <div id="rate-limit-info" class="text-sm text-center mt-2 hidden">
                                <div class="flex items-center justify-center space-x-2 text-orange-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="rate-limit-message">Next conversion available in: <span id="countdown-timer" class="font-mono font-bold">--:--</span></span>
                                </div>
                            </div>
                        </div>
                        <div id="conversion-status" class="text-sm"></div>
                        
                        <!-- Debug info (only visible in development) -->
                        @if(config('app.debug'))
                            <div class="mt-4 p-3 bg-gray-100 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Debug Info:</p>
                                <div id="debug-info" class="text-xs text-gray-500">Loading conversion status...</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-40 md:mb-16">
                <div class="p-4 md:p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base md:text-lg font-semibold text-gray-900">Transaction History</h2>
                        <div class="p-2 bg-blue-100 rounded-full">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 overflow-x-auto">
                    @forelse(auth()->user()->trxTransactions()->latest()->get() as $transaction)
                        <div class="p-4 md:p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between min-w-[300px]">
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">
                                            {{ $transaction->created_at->setTimezone('Asia/Kolkata')->format('M d, Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $transaction->created_at->setTimezone('Asia/Kolkata')->format('h:i A') }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-gray-900">
                                        {{ number_format($transaction->amount, 6) }} TRX
                                    </span>
                                </div>

                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500">No transactions found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.mobile-nav')

<script>
// Enhanced JavaScript with rate limiting support
let tronWebInstance = null;
let isLoadingBalance = false;
let cachedBalance = null;
let lastBalanceCheck = 0;
const REFRESH_COOLDOWN = 10000;

// Rate limiting variables
let conversionStatusInterval = null;
let countdownInterval = null;
let nextConversionAvailable = null;

// Wait for TronWeb to be available
async function waitForTronWeb() {
    return new Promise((resolve, reject) => {
        let attempts = 0;
        const maxAttempts = 50; // 5 seconds max wait time
        
        const checkTronWeb = () => {
            if (typeof TronWeb !== 'undefined' || typeof window.TronWeb !== 'undefined' || window.tronWeb) {
                resolve();
            } else if (attempts >= maxAttempts) {
                reject(new Error('TronWeb library failed to load'));
            } else {
                attempts++;
                setTimeout(checkTronWeb, 100);
            }
        };
        
        checkTronWeb();
    });
}

async function initTronWeb() {
    try {
        if (window.tronWeb && window.tronWeb.ready) {
            return window.tronWeb;
        }

        // Wait for TronWeb to be available
        await waitForTronWeb();

        if (!tronWebInstance) {
            try {
                const response = await fetch('/api/config');
                const configData = await response.json();
                if (configData.success) {
                    // Try to get TronWeb constructor from different possible locations
                    let TronWebConstructor;
                    
                    // First check if window.TronWeb.TronWeb exists and is a function (this is our case)
                    if (window.TronWeb && typeof window.TronWeb.TronWeb === 'function') {
                        TronWebConstructor = window.TronWeb.TronWeb;
                    } else if (typeof TronWeb !== 'undefined') {
                        TronWebConstructor = TronWeb;
                    } else if (typeof window.TronWeb !== 'undefined') {
                        TronWebConstructor = window.TronWeb;
                    } else if (window.tronWeb && window.tronWeb.constructor) {
                        TronWebConstructor = window.tronWeb.constructor;
                    }
                    
                    if (!TronWebConstructor || typeof TronWebConstructor !== 'function') {
                        throw new Error('TronWeb constructor not found');
                    }
                    
                    tronWebInstance = new TronWebConstructor({
                        fullHost: configData.config.api_url,
                        headers: { "TRON-PRO-API-KEY": configData.config.trongrid_api_key }
                        // Private key removed for security
                    });
                } else {
                    throw new Error('Failed to get API configuration');
                }
            } catch (error) {
                console.error('Failed to initialize TronWeb:', error);
                throw error;
            }
        }

        return tronWebInstance;
    } catch (error) {
        console.error('TronWeb initialization error:', error);
        throw new Error('Failed to initialize TronWeb');
    }
}

// Update UI elements
function updateUI(loading = false) {
    const refreshButton = document.getElementById('refresh-button');
    const balanceStatus = document.getElementById('balance-status');

    if (loading) {
        refreshButton.disabled = true;
        refreshButton.classList.add('animate-spin');
        refreshButton.title = 'Refreshing...';
        if (balanceStatus) {
            balanceStatus.textContent = 'Refreshing balance...';
            balanceStatus.className = 'text-xs text-blue-500 mt-1';
        }
    } else {
        const canRefresh = Date.now() - lastBalanceCheck >= REFRESH_COOLDOWN;
        refreshButton.disabled = false; // Always enable the button, handle cooldown in function
        refreshButton.classList.remove('animate-spin');
        refreshButton.title = canRefresh ? 'Refresh balance' : `Wait ${Math.ceil((REFRESH_COOLDOWN - (Date.now() - lastBalanceCheck)) / 1000)}s to refresh`;

        if (balanceStatus) {
        if (!canRefresh) {
            const remainingTime = Math.ceil((REFRESH_COOLDOWN - (Date.now() - lastBalanceCheck)) / 1000);
            balanceStatus.textContent = `Wait ${remainingTime}s to refresh`;
                balanceStatus.className = 'text-xs text-yellow-500 mt-1';
        } else {
                balanceStatus.textContent = `Last updated: ${new Date(lastBalanceCheck).toLocaleTimeString()}`;
                balanceStatus.className = 'text-xs text-green-500 mt-1';
            }
        }
    }
}

async function getBalance(forceRefresh = false) {
    if (isLoadingBalance) return;

    try {
        isLoadingBalance = true;
        updateUI(true);

        const tronWeb = await initTronWeb();
        const walletAddress = '{{ auth()->user()->wallet->address ?? "" }}';

        if (!walletAddress) {
            throw new Error('Wallet address not found');
        }

        const balance = await tronWeb.trx.getBalance(walletAddress);

        // Always update the display with fresh balance
        await updateBalanceDisplay(balance);

        // Update cache
        cachedBalance = balance;
        lastBalanceCheck = Date.now();
        
        // Show success notification only for manual refresh
        if (forceRefresh) {
            showNotification('Balance updated successfully!', 'success');
        }

    } catch (error) {
        console.error('Error fetching balance:', error);
        document.getElementById('wallet-balance').textContent = '0.000000';
        document.getElementById('balance-status').textContent = 'Failed to load balance';
        document.getElementById('balance-status').classList.remove('hidden');
    } finally {
        isLoadingBalance = false;
        updateUI(false);
    }
}

async function updateBalanceDisplay(balanceInSun) {
    try {
        const balanceTRX = balanceInSun / 1_000_000;
        document.getElementById('wallet-balance').textContent = balanceTRX.toFixed(6);

        // Update the max amount for the convert input
        const convertInput = document.getElementById('convert-amount');
        if (convertInput) {
            convertInput.max = balanceTRX;
        }
    } catch (error) {
        console.error('Error updating balance display:', error);
    }
}

// Rate limiting functions
async function checkConversionStatus() {
    try {
        const response = await fetch('{{ route("convert.status") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Failed to check conversion status');
        }

        const data = await response.json();
        
        if (data.success) {
            updateConvertButtonState(data);
        }
    } catch (error) {
        console.error('Error checking conversion status:', error);
    }
}

function updateConvertButtonState(statusData) {
    const convertButton = document.getElementById('convert-button');
    const buttonText = document.getElementById('convert-button-text');
    const rateLimitInfo = document.getElementById('rate-limit-info');
    const countdownTimer = document.getElementById('countdown-timer');
    
    // Update debug info if available
    @if(config('app.debug'))
        const debugInfo = document.getElementById('debug-info');
        if (debugInfo) {
            debugInfo.innerHTML = `
                Can Convert: ${statusData.can_convert ? 'Yes' : 'No'}<br>
                Remaining: ${statusData.remaining_minutes || 0} minutes<br>
                Next Available: ${statusData.next_available_at ? new Date(statusData.next_available_at).toLocaleString() : 'Now'}
            `;
        }
    @endif

    if (statusData.can_convert) {
        // User can convert
        convertButton.disabled = false;
        convertButton.classList.remove('opacity-50', 'cursor-not-allowed');
        buttonText.textContent = 'Convert to StakeTRX';
        rateLimitInfo.classList.add('hidden');
        
        // Clear any existing countdown
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        nextConversionAvailable = null;
    } else {
        // User is rate limited
        convertButton.disabled = true;
        convertButton.classList.add('opacity-50', 'cursor-not-allowed');
        buttonText.textContent = 'Conversion Temporarily Locked';
        rateLimitInfo.classList.remove('hidden');
        
        // Set next available time and start countdown
        nextConversionAvailable = new Date(statusData.next_available_at);
        startCountdown();
    }
}

function startCountdown() {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }

    countdownInterval = setInterval(() => {
        if (!nextConversionAvailable) {
            clearInterval(countdownInterval);
            return;
        }

        const now = new Date();
        const timeDiff = nextConversionAvailable - now;
        
        if (timeDiff <= 0) {
            // Time is up, check status again
            clearInterval(countdownInterval);
            countdownInterval = null;
            checkConversionStatus();
            return;
        }
        
        const minutes = Math.floor(timeDiff / 60000);
        const seconds = Math.floor((timeDiff % 60000) / 1000);
        
        const countdownTimer = document.getElementById('countdown-timer');
        if (countdownTimer) {
            countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
}

// Add auto-refresh functionality
document.addEventListener('DOMContentLoaded', () => {
    // Initial balance and conversion status check
    getBalance(true);
    checkConversionStatus();

    // Refresh balance every 30 seconds if tab is visible
    setInterval(() => {
        if (document.visibilityState === 'visible') {
            getBalance(true);
        }
    }, 30000);

    // Check conversion status every 30 seconds
    conversionStatusInterval = setInterval(() => {
        if (document.visibilityState === 'visible') {
            checkConversionStatus();
        }
    }, 30000);
});

// Handle tab visibility changes
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        getBalance(true);
        checkConversionStatus();
    }
});

// Cleanup intervals when page is unloaded
window.addEventListener('beforeunload', () => {
    if (conversionStatusInterval) {
        clearInterval(conversionStatusInterval);
    }
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});

// Update the refresh function
function refreshBalance() {
    const now = Date.now();
    const timeSinceLastCheck = now - lastBalanceCheck;
    
    if (timeSinceLastCheck < REFRESH_COOLDOWN) {
        const remainingTime = Math.ceil((REFRESH_COOLDOWN - timeSinceLastCheck) / 1000);
        showNotification(`Please wait ${remainingTime} seconds before refreshing again`, 'info');
        return;
    }
    
    // Show immediate feedback
    showNotification('Refreshing balance...', 'info');
    getBalance(true);
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    let bgColor = 'bg-green-500';
    
    if (type === 'error') bgColor = 'bg-red-500';
    else if (type === 'info') bgColor = 'bg-blue-500';
    else if (type === 'warning') bgColor = 'bg-yellow-500';
    
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg ${bgColor} text-white shadow-lg transition-all transform translate-y-0 opacity-100`;

    notification.innerHTML = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

async function checkAndConvert() {
    const statusDiv = document.getElementById('conversion-status');
    
    // First check if button is disabled due to rate limiting
    const convertButton = document.getElementById('convert-button');
    if (convertButton.disabled) {
        showNotification('Please wait for the cooldown period to expire before making another conversion', 'warning');
        return;
    }
    
    try {
        const amount = document.getElementById('convert-amount').value;
        if (!amount || amount <= 0) {
            throw new Error('Please enter a valid amount');
        }

        const tronWeb = await initTronWeb();
        if (!tronWeb) {
            throw new Error('TronWeb not initialized');
        }

        const address = '{{ auth()->user()->wallet->address }}';
        const balance = await tronWeb.trx.getBalance(address);
        const balanceTRX = balance / 1_000_000;

        if (balanceTRX < amount) {
            throw new Error('Insufficient balance');
        }

        statusDiv.innerHTML = `
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
                    <div class="text-center">
                        <!-- Loading Spinner -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Processing Transaction</h3>

                        <!-- Message -->
                        <p class="text-sm text-gray-500">
                            Please wait while we process your transaction...
                        </p>
                    </div>
                </div>
            </div>
        `;

        try {
            // Get staking address from secure encrypted admin wallet (same as admin address)
            const stakingAddress = '{{ $adminWallet['address'] }}';
            const amountSun = tronWeb.toSun(amount);

            // Get user's private key securely for transaction signing
            const authData = await getAuthenticationData();
            if (!authData) {
                throw new Error('Authentication cancelled');
            }

            const requestBody = authData;

            const privateKeyResponse = await fetch('/api/secure/private-key', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(requestBody)
            });

            if (!privateKeyResponse.ok) {
                const errorData = await privateKeyResponse.json();
                
                // Handle specific private key errors
                if (privateKeyResponse.status === 404) {
                    if (errorData.message === 'Private key not configured') {
                        throw new Error('Your wallet private key is not configured. Please contact support to set up your wallet.');
                    } else if (errorData.message === 'Wallet not found') {
                        throw new Error('No wallet found for your account. Please contact support.');
                    }
                } else if (privateKeyResponse.status === 429) {
                    throw new Error('Too many authentication attempts. Please wait before trying again.');
                }
                
                throw new Error(errorData.message || 'Failed to authenticate');
            }

            const privateKeyData = await privateKeyResponse.json();
            
            console.log('Private key response:', {
                has_private_key: !!privateKeyData.private_key,
                key_length: privateKeyData.private_key ? privateKeyData.private_key.length : 0,
                wallet_address: privateKeyData.wallet_address,
                key_preview: privateKeyData.private_key ? privateKeyData.private_key.substring(0, 8) + '...' : 'none'
            });
            
            // Basic validation
            if (!privateKeyData.private_key || typeof privateKeyData.private_key !== 'string') {
                throw new Error('Invalid private key received from server');
            }
            
            const privateKey = privateKeyData.private_key.trim();
            
            console.log('About to set private key in TronWeb...', {
                tronWeb_ready: !!tronWeb,
                key_length: privateKey.length,
                key_type: typeof privateKey
            });
            
            // Set the private key for this transaction
            try {
                tronWeb.setPrivateKey(privateKey);
                console.log('✅ Private key set successfully in TronWeb');
            } catch (keyError) {
                console.error('❌ TronWeb setPrivateKey error:', keyError);
                console.error('Key details:', {
                    length: privateKey.length,
                    first_chars: privateKey.substring(0, 10),
                    is_hex: /^[a-fA-F0-9]+$/.test(privateKey)
                });
                throw new Error('Failed to set private key in TronWeb: ' + keyError.message);
            }

            const transaction = await tronWeb.trx.sendTransaction(stakingAddress, amountSun);
            console.log('Transaction:', transaction);

            if (transaction.result || transaction.txid) {
                await new Promise(resolve => setTimeout(resolve, 3000));

                const response = await fetch('{{ route("convert.staketrx") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: amount,
                        transaction_id: transaction.txid || transaction.transaction.txID
                    })
                });

                const result = await response.json();
                console.log('Backend response:', result);

                if (!response.ok) {
                    // Handle rate limiting specifically
                    if (response.status === 429 && result.error_type === 'rate_limit') {
                        statusDiv.innerHTML = `
                            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
                                    <div class="text-center">
                                        <!-- Warning Icon -->
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rate Limit Active</h3>

                                        <!-- Message -->
                                        <div class="bg-yellow-50 rounded-lg p-4 mb-4">
                                            <p class="text-sm text-yellow-800">${result.message}</p>
                                        </div>

                                        <!-- Remaining Time -->
                                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                            <p class="text-sm text-gray-600">Time remaining:</p>
                                            <p class="text-lg font-bold text-gray-900">${result.remaining_minutes} minutes</p>
                                        </div>

                                        <!-- Close Button -->
                                        <button onclick="document.getElementById('conversion-status').innerHTML = ''; checkConversionStatus();"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                                            OK
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Update button state
                        if (result.next_available_at) {
                            nextConversionAvailable = new Date(result.next_available_at);
                            updateConvertButtonState({
                                can_convert: false,
                                next_available_at: result.next_available_at,
                                remaining_minutes: result.remaining_minutes
                            });
                        }
                        return;
                    }
                    throw new Error(result.message || 'Failed to update balance on server');
                }

                statusDiv.innerHTML = `
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
                            <div class="text-center">
                                <!-- Success Icon -->
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Transaction Successful!</h3>

                                <!-- Amount -->
                                <div class="bg-gray-50 rounded-lg py-3 px-4 mb-4">
                                    <p class="text-sm text-gray-600">Amount Converted</p>
                                    <p class="text-2xl font-bold text-gray-900">${amount} TRX</p>
                                </div>

                                <!-- Transaction Details -->
                                <div class="text-left bg-gray-50 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Transaction ID:</p>
                                    <p class="text-sm font-mono text-gray-900 break-all">
                                        ${transaction.txid || transaction.transaction.txID}
                                    </p>
                                </div>

                                <!-- View on Explorer Link -->
                                <a href="https://nile.tronscan.org/#/transaction/${transaction.txid || transaction.transaction.txID}"
                                   target="_blank"
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 mb-4">
                                    <span>View on TronScan</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>

                                <!-- Auto-close message -->
                                <p class="text-sm text-gray-500">
                                    This message will close automatically in 3 seconds
                                </p>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('convert-amount').value = '';

                // Update rate limiting state after successful conversion
                if (result.next_conversion_available_at) {
                    nextConversionAvailable = new Date(result.next_conversion_available_at);
                    updateConvertButtonState({
                        can_convert: false,
                        next_available_at: result.next_conversion_available_at
                    });
                }

                // Reload the page to show updated transaction history
                setTimeout(() => window.location.reload(), 3000);
            } else {
                throw new Error('Transaction failed');
            }
        } catch (txError) {
            console.error('Transaction error:', txError);

            if (txError.message.includes('User rejected')) {
                throw new Error('Transaction was cancelled');
            }

            if (txError.transaction?.txID) {
                try {
                    const response = await fetch('{{ route("convert.staketrx") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: amount,
                            transaction_id: txError.transaction.txID
                        })
                    });

                    const result = await response.json();
                    console.log('Backend response:', result);

                    if (!response.ok) {
                        throw new Error(result.message || 'Failed to update balance on server');
                    }

                    statusDiv.innerHTML = `
                        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
                                <div class="text-center">
                                    <!-- Success Icon -->
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Transaction Successful!</h3>

                                    <!-- Amount -->
                                    <div class="bg-gray-50 rounded-lg py-3 px-4 mb-4">
                                        <p class="text-sm text-gray-600">Amount Converted</p>
                                        <p class="text-2xl font-bold text-gray-900">${amount} TRX</p>
                                    </div>

                                    <!-- Transaction Details -->
                                    <div class="text-left bg-gray-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-gray-600 mb-1">Transaction ID:</p>
                                        <p class="text-sm font-mono text-gray-900 break-all">
                                            ${txError.transaction.txID}
                                        </p>
                                    </div>

                                    <!-- View on Explorer Link -->
                                    <a href="https://nile.tronscan.org/#/transaction/${txError.transaction.txID}"
                                       target="_blank"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 mb-4">
                                        <span>View on TronScan</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>

                                    <!-- Auto-close message -->
                                    <p class="text-sm text-gray-500">
                                        This message will close automatically in 3 seconds
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;

                    document.getElementById('convert-amount').value = '';
                    
                    // Update rate limiting state after successful conversion (even if with error)
                    if (result.next_conversion_available_at) {
                        nextConversionAvailable = new Date(result.next_conversion_available_at);
                        updateConvertButtonState({
                            can_convert: false,
                            next_available_at: result.next_conversion_available_at
                        });
                    }
                    
                    setTimeout(() => window.location.reload(), 3000);
                    return;
                } catch (backendError) {
                    console.error('Backend error:', backendError);
                    throw new Error('Transaction may have succeeded but failed to update balance. Please contact support with your transaction ID.');
                }
            }

            throw new Error('Transaction failed: ' + (txError.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Conversion error:', error);
        statusDiv.innerHTML = `
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
                    <div class="text-center">
                        <!-- Error Icon -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Transaction Failed</h3>

                        <!-- Error Message -->
                        <div class="bg-red-50 rounded-lg p-4 mb-4">
                            <p class="text-sm text-red-600">${error.message}</p>
                        </div>

                        <!-- Close Button -->
                        <button onclick="document.getElementById('conversion-status').innerHTML = ''"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

// Custom authentication modal function
async function getAuthenticationData() {
    return new Promise((resolve, reject) => {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Confirm Transaction</h3>
                    <p class="text-gray-600">
                        Please enter your credentials to authorize this transaction
                    </p>
                </div>

                <form id="auth-form" class="space-y-4">
                    <div>
                        <label for="auth-password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" id="auth-password" name="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your password">
                    </div>



                    <div class="flex space-x-3 pt-4">
                        <button type="button" id="auth-cancel"
                            class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" id="auth-confirm"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
        `;

        // Add modal to page
        document.body.appendChild(modal);

        // Focus password field
        const passwordField = modal.querySelector('#auth-password');
        setTimeout(() => passwordField.focus(), 100);

        // Handle form submission
        const form = modal.querySelector('#auth-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const password = modal.querySelector('#auth-password').value;
            if (!password) {
                alert('Password is required');
                return;
            }

            const authData = { password };

            // Remove modal
            document.body.removeChild(modal);
            resolve(authData);
        });

        // Handle cancel
        const cancelBtn = modal.querySelector('#auth-cancel');
        cancelBtn.addEventListener('click', () => {
            document.body.removeChild(modal);
            resolve(null);
        });

        // Handle click outside modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
                resolve(null);
            }
        });

        // Handle escape key
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', escapeHandler);
                if (document.body.contains(modal)) {
                    document.body.removeChild(modal);
                    resolve(null);
                }
            }
        };
        document.addEventListener('keydown', escapeHandler);
    });
}
</script>
@endsection
