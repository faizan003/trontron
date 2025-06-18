@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-24 pt-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Balance Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600">Available for Withdrawal</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format(auth()->user()->total_earnings ?? 0, 6) }} TRX
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Total earnings from staking</p>
                </div>
            </div>
        </div>

        @if(!auth()->user()->google2fa_enabled)
        <!-- 2FA Required Message -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-center flex-col text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Two-Factor Authentication Required</h2>
                    <p class="text-gray-600 mb-6">For your security, you must enable Two-Factor Authentication before making any withdrawals.</p>
                    <a href="{{ route('profile') }}#2fa-setup" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg text-base font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow hover:shadow-lg">
                        Setup 2FA Now
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Withdraw Form -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 space-y-3 md:p-6 md:space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base md:text-lg font-semibold text-gray-900">Withdraw TRX</h2>
                    <div class="p-2 bg-purple-100 rounded-full">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Warning Alert -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong class="font-medium">Important:</strong> Make sure to enter a valid TRX/TRC20 address.
                                Funds sent to incorrect addresses cannot be recovered. A 5% fee will be deducted from your withdrawal amount.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col space-y-4">
                        <!-- TRX Address Input -->
                        <div class="w-full">
                            <label for="trx-address" class="block text-sm font-medium text-gray-700 mb-2">
                                TRX/TRC20 Withdrawal Address
                            </label>
                            <div class="relative rounded-lg">
                                <input type="text" id="trx-address"
                                    class="block w-full px-4 py-3 text-lg bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent font-mono"
                                    placeholder="Enter TRX address starting with T..."
                                    pattern="^T[A-Za-z0-9]{33}$">
                                <p class="mt-1 text-xs text-gray-500">Example: TXXXXYourTronAddressHereXXXXXXXXXXXXXX</p>
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="w-full">
                            <label for="withdraw-amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Amount to Withdraw
                            </label>
                            <div class="relative rounded-lg">
                                <input type="number" id="withdraw-amount"
                                    class="block w-full pl-4 pr-12 py-3 text-lg bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    step="0.000001" min="1" max="{{ auth()->user()->total_earnings }}" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <span class="text-gray-500 font-medium">TRX</span>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Section -->
                        <div id="fee-section" class="w-full hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Transaction Fee (5%)</span>
                                    <span class="text-red-600">-<span id="fee-amount">0.000000</span> TRX</span>
                                </div>
                                <div class="flex justify-between text-sm font-medium">
                                    <span class="text-gray-700">You will receive</span>
                                    <span class="text-green-600"><span id="final-amount">0.000000</span> TRX</span>
                                </div>
                            </div>
                        </div>

                        <button onclick="initiateWithdraw()"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg text-base font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow hover:shadow-lg">
                            Withdraw TRX
                        </button>

                        <!-- Add this processing time info -->
                        <div class="text-center text-sm text-gray-600 mt-2">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Fast processing: Withdrawals typically complete within 5 minutes</span>
                            </div>
                        </div>
                    </div>
                    <div id="withdrawal-status" class="text-sm"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Withdrawn Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600">Total Amount Withdrawn</h3>
                    @php
                        $totalWithdrawn = auth()->user()->withdrawals()
                            ->where('status', 'completed')
                            ->sum('amount');
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalWithdrawn, 6) }} TRX
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Lifetime withdrawal amount</p>
                </div>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Withdrawal History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6 mb-20 md:mb-6">
            <div class="p-4 md:p-6 border-b border-gray-100">
                <h2 class="text-base md:text-lg font-semibold text-gray-900">Withdrawal History</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @php
                    $withdrawals = auth()->user()->withdrawals()
                        ->latest()
                        ->get();
                @endphp

                @forelse($withdrawals as $withdrawal)
                    <div class="p-4 md:p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex flex-col space-y-3">
                            <!-- Date and Amount -->
                            <div class="flex justify-between items-start">
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">
                                            {{ $withdrawal->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $withdrawal->created_at->format('h:i A') }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-gray-900">
                                        {{ number_format($withdrawal->amount, 6) }} TRX
                                    </span>
                                </div>
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </div>

                            <!-- Wallet Address -->
                            <div class="flex items-center space-x-2 bg-gray-50 rounded-lg p-2">
                                <span class="text-sm text-gray-500">Sent to:</span>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-sm font-mono text-gray-600 break-all">
                                        {{ $withdrawal->address }}
                                    </span>
                                    <button onclick="copyToClipboard('{{ $withdrawal->address }}')"
                                        class="ml-2 text-blue-600 hover:text-blue-700 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500">No withdrawal history found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('layouts.mobile-nav')

<div id="notification-container" class="fixed top-0 left-0 right-0 z-50 pointer-events-none">
    <div class="max-w-sm mx-auto px-4 mt-4">
        <!-- Notifications will be inserted here -->
    </div>
</div>

<script src="{{ asset('js/tronweb-local.js') }}"></script>

<script>
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container').firstElementChild;
    const notification = document.createElement('div');

    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

    notification.className = `notification pointer-events-auto mb-3 p-4 ${bgColor} text-white rounded-lg shadow-lg flex items-center justify-between`;

    notification.innerHTML = `
        <div class="flex items-center">
            ${type === 'success' ? `
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            ` : type === 'error' ? `
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            ` : `
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `}
            <span class="font-medium">${message}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    container.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('hide');
        setTimeout(() => notification.remove(), 3000);
    }, 5000);
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
        // Wait for TronWeb to be available
        await waitForTronWeb();

        // Create TronWeb instance with secure configuration
        const response = await fetch('/api/config');
        const configData = await response.json();
        if (!configData.success) {
            throw new Error('Failed to get API configuration');
        }

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

        const tronWeb = new TronWebConstructor({
            fullHost: configData.config.api_url,
            headers: { "TRON-PRO-API-KEY": configData.config.trongrid_api_key },
            privateKey: '{{ $adminWallet['privateKey'] }}'
        });

        // Set default address
        const address = '{{ $adminWallet['address'] }}';
        tronWeb.defaultAddress = {
            hex: tronWeb.address.toHex(address),
            base58: address
        };

        // Test connection
        const balance = await tronWeb.trx.getBalance(address);
        console.log('Admin wallet balance:', balance);

        return tronWeb;
    } catch (error) {
        console.error('TronWeb initialization error:', error);
        throw new Error('Failed to initialize TronWeb: ' + error.message);
    }
}

async function initiateWithdraw() {
    const withdrawButton = document.querySelector('button[onclick="initiateWithdraw()"]');

    try {
        // Disable button and show loading state
        withdrawButton.disabled = true;
        withdrawButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;

        const address = document.getElementById('trx-address').value.trim();
        const amount = parseFloat(document.getElementById('withdraw-amount').value);
        const fee = amount * 0.05; // 5% fee
        const finalAmount = amount - fee;
        const availableBalance = {{ auth()->user()->total_earnings ?? 0 }};

        // Basic validation
        if (!address.match(/^T[A-Za-z0-9]{33}$/)) {
            throw new Error('Please enter a valid TRX address');
        }

        if (isNaN(amount) || amount <= 0) {
            throw new Error('Please enter a valid amount');
        }

        if (amount > availableBalance) {
            throw new Error('Insufficient balance');
        }

        // If 2FA is enabled, request and validate code first
        @if(auth()->user()->google2fa_enabled)
            const authCode = await requestAuthCode();
            if (!authCode) {
                withdrawButton.disabled = false;
                withdrawButton.innerHTML = 'Withdraw TRX';
                return;
            }

            // Verify 2FA code
            const authResponse = await fetch('{{ route("2fa.validate.withdraw") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code: authCode })
            });

            const authResult = await authResponse.json();
            if (!authResult.success) {
                throw new Error('Invalid authentication code');
            }
        @endif

        // Initialize TronWeb
        const tronWeb = await initTronWeb();
        const adminAddress = '{{ $adminWallet['address'] }}';

        // Convert amount to SUN
        const withdrawalAmount = tronWeb.toSun(finalAmount);

        // Create transaction from admin wallet to user's address
        const tx = await tronWeb.transactionBuilder.sendTrx(
            address, // Recipient address (user's address)
            withdrawalAmount,
            adminAddress // Sender address (admin wallet)
        );

        // Sign and broadcast
        const signedTx = await tronWeb.trx.sign(tx);
        const result = await tronWeb.trx.sendRawTransaction(signedTx);

        if (result.result || result.txid) {
            // Process withdrawal on backend
            const response = await fetch('{{ route("withdraw.earnings") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    amount: finalAmount,
                    original_amount: amount,
                    fee: fee,
                    address: address,
                    txid: result.txid || result.transaction.txID,
                    from_address: adminAddress // Send the base58 admin address
                })
            });

            const backendResult = await response.json();

            if (backendResult.success) {
                const txLink = `https://nile.tronscan.org/#/transaction/${result.txid || result.transaction.txID}`;
                showNotification(`
                    Withdrawal successful!<br>
                    Amount: ${finalAmount} TRX<br>
                    <a href="${txLink}" target="_blank" class="underline">View Transaction</a>
                `, 'success');
                setTimeout(() => window.location.reload(), 3000);
            } else {
                throw new Error(backendResult.message || 'Failed to process withdrawal');
            }
        } else {
            throw new Error('Transaction failed');
        }

    } catch (error) {
        console.error('Withdrawal error:', error);
        showNotification(error.message || 'Failed to process withdrawal', 'error');
        withdrawButton.disabled = false;
        withdrawButton.innerHTML = 'Withdraw TRX';
    }
}

async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showNotification('Address copied to clipboard!', 'success');
    } catch (err) {
        showNotification('Failed to copy address', 'error');
    }
}

// Add styles for notifications
const style = document.createElement('style');
style.textContent = `
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
`;
document.head.appendChild(style);

// Add this style to your existing styles
const additionalStyles = `
    @media (max-width: 640px) {
        .confirmation-dialog {
            margin: 1rem;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
    }

    input[type="text"]#auth-code-input {
        letter-spacing: 0.5em;
        font-size: 1.5rem;
    }
`;
document.head.appendChild(document.createElement('style')).textContent += additionalStyles;

// Update the showConfirmation function
function showConfirmation(message, onConfirm, onCancel) {
    const dialog = document.createElement('div');
    dialog.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';

    const [amount, address] = message.match(/Amount: (.*?) TRX\nAddress: (.*?)\n/s).slice(1);

    dialog.innerHTML = `
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-lg p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Confirm Withdrawal</h3>

                <!-- Amount -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-1">Amount to withdraw</p>
                    <p class="text-2xl font-bold text-gray-900">${amount} TRX</p>
                </div>

                <!-- Address -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-1">Recipient Address</p>
                    <p class="text-sm font-mono break-all text-gray-900">${address}</p>
                </div>

                <!-- Warning -->
                <div class="bg-yellow-50 rounded-lg p-3 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm text-yellow-800">
                            Make sure the address is correct. Funds sent to incorrect addresses cannot be recovered.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <button onclick="handleCancel(this)"
                    class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="handleConfirm(this)"
                    class="flex-1 px-4 py-3 text-white bg-blue-600 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Confirm Withdrawal
                </button>
            </div>
        </div>
    `;

    // Store callbacks
    dialog._confirmCallback = onConfirm;
    dialog._cancelCallback = onCancel;

    document.body.appendChild(dialog);

    // Add animation
    const modalContent = dialog.firstElementChild;
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';

    requestAnimationFrame(() => {
        modalContent.style.transition = 'all 0.2s ease-out';
        modalContent.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    });
}

// Update the handleConfirm function
function handleConfirm(button) {
    const dialog = button.closest('.fixed');
    const callback = dialog._confirmCallback;

    // Add loading state
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;

    // Animate out
    const modalContent = dialog.firstElementChild;
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';

    setTimeout(() => {
        dialog.remove();
        if (typeof callback === 'function') {
            callback();
        }
    }, 200);
}

// Update the handleCancel function
function handleCancel(button) {
    const dialog = button.closest('.fixed');
    const callback = dialog._cancelCallback;

    // Animate out
    const modalContent = dialog.firstElementChild;
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';

    setTimeout(() => {
        dialog.remove();
        if (typeof callback === 'function') {
            callback();
        }
    }, 200);
}

// Add this function to calculate and display fee
function calculateFee() {
    const amount = parseFloat(document.getElementById('withdraw-amount').value) || 0;
    const fee = amount * 0.05; // 5% fee
    const finalAmount = amount - fee;

    // Update fee display
    document.getElementById('fee-amount').textContent = fee.toFixed(6);
    document.getElementById('final-amount').textContent = finalAmount.toFixed(6);
    document.getElementById('fee-section').classList.remove('hidden');
}

// Add input event listener
document.getElementById('withdraw-amount').addEventListener('input', calculateFee);

async function requestAuthCode() {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-lg p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">2FA Verification</h3>
                    <p class="text-sm text-gray-600 mb-6">Enter the 6-digit code from your authenticator app</p>
                </div>

                <div class="space-y-4">
                    <input type="text" id="auth-code-input"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest font-mono bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="000000"
                        maxlength="6"
                        autocomplete="one-time-code">

                    <div class="flex space-x-3">
                        <button onclick="closeAuthModal(this, null)"
                            class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 rounded-lg font-medium hover:bg-gray-200">
                            Cancel
                        </button>
                        <button onclick="submitAuthCode(this)"
                            class="flex-1 px-4 py-3 text-white bg-blue-600 rounded-lg font-medium hover:bg-blue-700">
                            Verify
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Handle close
        window.closeAuthModal = (button, code) => {
            button.closest('.fixed').remove();
            resolve(code);
        };

        // Handle submit
        window.submitAuthCode = (button) => {
            const code = document.getElementById('auth-code-input').value;
            if (!code || code.length !== 6) {
                showNotification('Please enter a valid 6-digit code', 'error');
                return;
            }
            closeAuthModal(button, code);
        };

        // Auto-focus input
        setTimeout(() => {
            document.getElementById('auth-code-input').focus();
        }, 100);
    });
}
</script>
@endsection
