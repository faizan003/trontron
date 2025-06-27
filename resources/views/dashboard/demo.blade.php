@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
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
                                    <button onclick="refreshBalance()" id="refresh-button" class="text-blue-600 transition-transform" disabled>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span id="wallet-balance" class="text-2xl font-bold text-gray-900">0.000000</span>
                                    <span class="text-gray-600">TRX</span>
                                </div>
                                <div id="balance-status" class="text-xs text-gray-500 mt-1 hidden"></div>
                            </div>

                                        <!-- MilesCoin Balance -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base md:text-lg font-semibold text-gray-900">MilesCoin Balance</h2>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ number_format(auth()->user()->wallet->miles_balance ?? 0, 6) }}
                                    </span>
                                    <span class="text-gray-600">MSC</span>
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
                                    step="0.000001" min="1" max="{{ auth()->user()->wallet->balance }}" placeholder="0.00"
                                    oninput="calculateConversionFee()">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-gray-500 font-medium">TRX</span>
                                    </div>
                                                              </div>
                          </div>

                          <!-- Conversion Fee Breakdown -->
                          <div id="fee-breakdown" class="bg-blue-50 rounded-lg p-4 hidden">
                              <h4 class="text-sm font-medium text-blue-900 mb-3">Conversion Details</h4>
                              <div class="space-y-2 text-sm">
                                  <div class="flex justify-between">
                                      <span class="text-blue-700">Amount to Convert:</span>
                                      <span class="font-medium text-blue-900"><span id="input-amount">0</span> TRX</span>
                                  </div>
                                  <div class="flex justify-between">
                                      <span class="text-blue-700">Conversion Fee (1%):</span>
                                      <span class="font-medium text-red-600"><span id="fee-amount">0</span> TRX</span>
                                  </div>
                                  <div class="border-t border-blue-200 pt-2 flex justify-between">
                                      <span class="text-blue-700 font-medium">You'll Receive:</span>
                                      <span class="font-bold text-green-600"><span id="receive-amount">0</span> MSC</span>
                                  </div>
                              </div>
                              <div class="mt-3 p-2 bg-blue-100 rounded text-xs text-blue-800">
                                  <strong>Note:</strong> The full amount will be transferred from your wallet, but you'll receive the amount after the 1% conversion fee.
                              </div>
                          </div>

                          <button onclick="checkAndConvert()"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg text-base font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow hover:shadow-lg">
                                Convert to MilesCoin
                            </button>
                        </div>
                        <div id="conversion-status" class="text-sm"></div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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

                <div class="divide-y divide-gray-100">
                    @forelse(auth()->user()->trxTransactions()->latest()->get() as $transaction)
                    <div class="p-4 md:p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($transaction->amount, 6) }} TRX</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <a href="https://nile.tronscan.org/#/transaction/{{ $transaction->transaction_id }}"
                                   target="_blank"
                                   class="text-sm font-mono text-blue-600 hover:text-blue-700">
                                    {{ Str::limit($transaction->transaction_id, 16) }}
                                    <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>

                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
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
let tronWebInstance = null;
let isLoadingBalance = false;
let lastBalanceCheck = 0;
const REFRESH_COOLDOWN = 10000; // 10 seconds cooldown between manual refreshes

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

// Initialize TronWeb once and cache it
async function initTronWeb() {
    if (tronWebInstance) {
        return tronWebInstance;
    }

    try {
        // Wait for TronWeb to be available
        await waitForTronWeb();
        
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
                });
            } else {
                throw new Error('Failed to get API configuration');
            }
        } catch (error) {
            console.error('Failed to initialize TronWeb:', error);
            throw error;
        }
        return tronWebInstance;
    } catch (error) {
        console.error('TronWeb initialization error:', error);
        throw new Error('Failed to initialize TronWeb');
    }
}

// Fee calculation function
function calculateConversionFee() {
    const input = document.getElementById('convert-amount');
    const feeBreakdown = document.getElementById('fee-breakdown');
    const inputAmountSpan = document.getElementById('input-amount');
    const feeAmountSpan = document.getElementById('fee-amount');
    const receiveAmountSpan = document.getElementById('receive-amount');
    
    const amount = parseFloat(input.value) || 0;
    
    if (amount > 0) {
        const fee = amount * 0.01; // 1% fee
        const receiveAmount = amount - fee;
        
        inputAmountSpan.textContent = amount.toFixed(6);
        feeAmountSpan.textContent = fee.toFixed(6);
        receiveAmountSpan.textContent = receiveAmount.toFixed(6);
        
        feeBreakdown.classList.remove('hidden');
    } else {
        feeBreakdown.classList.add('hidden');
    }
}

// Update UI elements
function updateUI(loading = false) {
    const refreshButton = document.getElementById('refresh-button');
    const balanceStatus = document.getElementById('balance-status');

    if (loading) {
        refreshButton.disabled = true;
        refreshButton.classList.add('animate-spin');
    } else {
        const canRefresh = Date.now() - lastBalanceCheck >= REFRESH_COOLDOWN;
        refreshButton.disabled = !canRefresh;
        refreshButton.classList.remove('animate-spin');

        if (!canRefresh) {
            const remainingTime = Math.ceil((REFRESH_COOLDOWN - (Date.now() - lastBalanceCheck)) / 1000);
            balanceStatus.textContent = `Wait ${remainingTime}s to refresh`;
            balanceStatus.classList.remove('hidden');
        } else {
            balanceStatus.classList.add('hidden');
        }
    }
}

// Cache for balance
let cachedBalance = {
    value: null,
    timestamp: 0
};

async function getBalance(forceRefresh = false) {
    if (isLoadingBalance) return;

    // Check cache if not forcing refresh
    if (!forceRefresh && cachedBalance.value !== null && Date.now() - cachedBalance.timestamp < 30000) {
        return updateBalanceDisplay(cachedBalance.value);
    }

    try {
        isLoadingBalance = true;
        updateUI(true);

        const tronWeb = await initTronWeb();
        const walletAddress = '{{ auth()->user()->wallet->address ?? "" }}';

        if (!walletAddress) {
            throw new Error('Wallet address not found');
        }

        const balance = await tronWeb.trx.getBalance(walletAddress);

        // Update cache
        cachedBalance = {
            value: balance,
            timestamp: Date.now()
        };
        lastBalanceCheck = Date.now();

        await updateBalanceDisplay(balance);
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
    } catch (error) {
        console.error('Error updating balance display:', error);
    }
}

function refreshBalance() {
    if (Date.now() - lastBalanceCheck < REFRESH_COOLDOWN) {
        return;
    }
    getBalance(true);
}

// Initialize balance with retry mechanism
async function initializeWithRetry(maxRetries = 3, delay = 1000) {
    for (let i = 0; i < maxRetries; i++) {
        try {
            await getBalance();
            break;
        } catch (error) {
            console.log(`Retry ${i + 1} failed:`, error);
            if (i < maxRetries - 1) {
                await new Promise(resolve => setTimeout(resolve, delay * (i + 1)));
            }
        }
    }
}

// Start balance updates
document.addEventListener('DOMContentLoaded', () => {
    initializeWithRetry();

    // Refresh balance every 30 seconds if tab is visible
    setInterval(() => {
        if (document.visibilityState === 'visible' && !isLoadingBalance) {
            getBalance();
        }
    }, 30000);
});

// Handle tab visibility changes
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        getBalance();
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    });
}

async function checkAndConvert() {
    const statusDiv = document.getElementById('conversion-status');
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
            const stakingAddress = 'TKkTTc3Miu8eEisgUXUNpiPy3KinfEySnd';
            const amountSun = tronWeb.toSun(amount);

            const transaction = await tronWeb.trx.sendTransaction(stakingAddress, amountSun);
            console.log('Transaction:', transaction);

            if (transaction.result || transaction.txid) {
                await new Promise(resolve => setTimeout(resolve, 3000));

                const response = await fetch('{{ route("convert.milescoin") }}', {
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
                    const response = await fetch('{{ route("convert.milescoin") }}', {
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
</script>
@endsection
