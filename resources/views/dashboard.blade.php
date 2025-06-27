@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Wallet Information -->
                <div class="bg-gradient-to-br from-blue-50 to-red-50 rounded-xl p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Wallet</h2>

                    @if(auth()->user()->wallet)
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600">Wallet Address</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ auth()->user()->wallet->address }}"
                                        class="w-full bg-white/50 rounded-lg px-4 py-2 text-gray-700" readonly>
                                    <button onclick="copyToClipboard('{{ auth()->user()->wallet->address }}')"
                                        class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <a href="https://nile.tronscan.org/#/address/{{ auth()->user()->wallet->address }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-700">
                                        View on TronScan
                                    </a>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Available Balance</label>
                                <div class="flex items-center space-x-2 bg-white/50 rounded-lg px-4 py-2">
                                    <span id="wallet-balance" class="text-2xl font-bold text-gray-800">Loading...</span>
                                    <span class="text-gray-600">TRX</span>
                                    <button onclick="refreshBalance()"
                                        class="ml-2 text-blue-600 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div id="usd-balance" class="text-sm text-gray-600 mt-1">≈ $0.00 USD</div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <a href="https://nile.tronscan.org/#/wallet/contract"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-700">
                                        Get Test TRX
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-red-600">No wallet found. Please contact support.</p>
                    @endif
                </div>

                <!-- Staking Information -->
                <div class="bg-gradient-to-br from-red-50 to-blue-50 rounded-xl p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Staking Overview</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Total Staked</label>
                            <div class="text-2xl font-bold text-gray-800">
                                @php
                                    $totalStaked = auth()->user()->stakings ?
                                        auth()->user()->stakings->where('status', 'active')->sum('amount') : 0;
                                @endphp
                                {{ number_format($totalStaked, 6) }} TRX
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Total Earned</label>
                            <div class="text-2xl font-bold text-gray-800">
                                @php
                                    $totalEarned = auth()->user()->stakings ?
                                        auth()->user()->stakings->sum('earned_amount') : 0;
                                @endphp
                                {{ number_format($totalEarned, 6) }} TRX
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">APY</label>
                            <div class="text-2xl font-bold text-emerald-600">300%</div>
                        </div>
                    </div>
                </div>

                <!-- MilesCoin Balance Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">MilesCoin Balance</h2>
                    <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-purple-800">
                                    {{ number_format(auth()->user()->wallet->miles_balance ?? 0, 6) }} MSC
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Available for staking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Convert TRX to MilesCoin Section -->
            <div class="mt-8 bg-gradient-to-br from-blue-50 to-red-50 rounded-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Convert TRX to MilesCoin</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex-1">
                            <label for="convert-amount" class="block text-sm text-gray-600 mb-2">Amount to Convert</label>
                            <input type="number" id="convert-amount"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                step="0.000001" min="1" placeholder="Enter TRX amount">
                        </div>
                        <div class="pt-8">
                            <button onclick="checkAndConvert()"
                                class="bg-gradient-to-r from-blue-600 to-red-600 hover:from-blue-700 hover:to-red-700 text-white px-6 py-2 rounded-lg transition-all transform hover:scale-105">
                                Convert to MilesCoin
                            </button>
                        </div>
                    </div>
                    <div id="conversion-status" class="text-sm"></div>
                </div>
            </div>

            <!-- Staking Form -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Stake TRX</h3>
                <form action="{{ route('stake') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="amount" class="block text-sm text-gray-600 mb-2">Amount to Stake</label>
                        <div class="flex items-center space-x-2">
                            <input type="number" id="amount" name="amount" step="0.000001" min="1"
                                class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                required>
                            <span class="text-gray-600">TRX</span>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-red-600 hover:from-blue-700 hover:to-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105">
                        Stake Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let tronWebInstance = null;

// Secure API Configuration Helper
let apiConfig = null;
let configPromise = null;

async function getApiConfig() {
    if (apiConfig) {
        return apiConfig;
    }

    if (configPromise) {
        return await configPromise;
    }

    configPromise = fetch('/api/config', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch API configuration');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            apiConfig = data.config;
            return apiConfig;
        } else {
            throw new Error(data.message || 'Failed to get API configuration');
        }
    });

    return await configPromise;
}

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

        // Initialize TronWeb with secure configuration
        if (!tronWebInstance) {
            try {
                const config = await getApiConfig();
                
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
                
                console.log('Selected TronWebConstructor:', TronWebConstructor);
                
                tronWebInstance = new TronWebConstructor({
                    fullHost: config.api_url,
                    headers: { "TRON-PRO-API-KEY": config.trongrid_api_key }
                    // Private key removed for security - use secure API when needed
                });
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

async function getBalance() {
    try {
        const tronWeb = await initTronWeb();
        if (!tronWeb) {
            throw new Error('TronWeb not initialized');
        }

        const address = '{{ auth()->user()->wallet->address }}';

        // Get account information
        const account = await tronWeb.trx.getAccount(address);
        console.log('Account info:', account);

        // Get balance directly
        const balance = await tronWeb.trx.getBalance(address);
        console.log('Balance:', balance);

        updateBalanceDisplay(balance);
    } catch (error) {
        console.error('Error fetching balance:', error);
        document.getElementById('wallet-balance').textContent = 'Error';
        document.getElementById('usd-balance').textContent = 'Failed to load balance';
    }
}

async function updateBalanceDisplay(balanceInSun) {
    try {
        // Convert from SUN to TRX (1 TRX = 1,000,000 SUN)
        const balanceTRX = balanceInSun / 1_000_000;

        // Update TRX balance
        document.getElementById('wallet-balance').textContent = balanceTRX.toFixed(6);

        // For mainnet, fetch real TRX price (you can implement real price API later)
        const trxPrice = 0.08; // You can replace this with real price API call
        const usdValue = (balanceTRX * trxPrice).toFixed(2);
        document.getElementById('usd-balance').textContent = `≈ $${usdValue} USD`;
    } catch (error) {
        console.error('Error updating balance display:', error);
        document.getElementById('usd-balance').textContent = 'Failed to load USD value';
    }
}

function refreshBalance() {
    document.getElementById('wallet-balance').textContent = 'Loading...';
    document.getElementById('usd-balance').textContent = 'Loading...';
    getBalance();
}

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

// Initial balance load with retry
async function initializeWithRetry(maxRetries = 3) {
    for (let i = 0; i < maxRetries; i++) {
        try {
            await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1))); // Increasing delay
            await getBalance();
            break;
        } catch (error) {
            console.log(`Retry ${i + 1} failed:`, error);
            if (i === maxRetries - 1) {
                document.getElementById('wallet-balance').textContent = 'Error';
                document.getElementById('usd-balance').textContent = 'Failed to load balance';
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', initializeWithRetry);

// Refresh balance every 30 seconds
setInterval(getBalance, 30000);

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

        // Get current balance
        const address = '{{ auth()->user()->wallet->address }}';
        const balance = await tronWeb.trx.getBalance(address);
        const balanceTRX = balance / 1_000_000;

        if (balanceTRX < amount) {
            throw new Error('Insufficient balance');
        }

        // Show confirmation
        if (!confirm(`Are you sure you want to convert ${amount} TRX to MilesCoin?`)) {
            return;
        }

        // Update status
        statusDiv.innerHTML = `
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg">
                Processing transaction...
            </div>
        `;

        try {
            // Send TRX to staking address
            const stakingAddress = 'TKkTTc3Miu8eEisgUXUNpiPy3KinfEySnd';
            const amountSun = tronWeb.toSun(amount);

            const transaction = await tronWeb.trx.sendTransaction(stakingAddress, amountSun);
            console.log('Transaction:', transaction);

            if (transaction.result || transaction.txid) {
                // Wait for transaction confirmation
                await new Promise(resolve => setTimeout(resolve, 3000));

                // Send transaction info to backend
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
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        Successfully converted ${amount} TRX to MilesCoin!
                        <div class="text-sm mt-2">
                            Transaction ID: ${transaction.txid || transaction.transaction.txID}
                        </div>
                    </div>
                `;

                // Clear input
                document.getElementById('convert-amount').value = '';

                // Refresh balances
                await getBalance();
                setTimeout(() => window.location.reload(), 3000);
            } else {
                throw new Error('Transaction failed');
            }
        } catch (txError) {
            console.error('Transaction error:', txError);

            // Check if the error is due to user rejection
            if (txError.message.includes('User rejected')) {
                throw new Error('Transaction was cancelled');
            }

            // If we have a transaction ID, the transaction might have succeeded
            if (txError.transaction?.txID) {
                // Proceed with backend update
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
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            Successfully converted ${amount} TRX to MilesCoin!
                            <div class="text-sm mt-2">
                                Transaction ID: ${txError.transaction.txID}
                            </div>
                        </div>
                    `;

                    // Clear input
                    document.getElementById('convert-amount').value = '';

                    // Refresh balances
                    await getBalance();
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                Error: ${error.message}
            </div>
        `;
    }
}
</script>
@endsection
