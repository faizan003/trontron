@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-20 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Profile Header -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 mx-auto mb-4 flex items-center justify-center text-white">
                        <span class="text-3xl font-medium">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    <p class="text-gray-500">{{ auth()->user()->phone }}</p>
                    <p class="text-sm text-gray-400 mt-2">Member since {{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>

                    <!-- Add this section after the referral section -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Two-Factor Authentication</h2>

                            @if(!auth()->user()->google2fa_enabled)
                                <div id="setup2fa" class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Enhance your account security by enabling two-factor authentication.
                                        You'll need to provide an authentication code when making withdrawals.
                                    </p>

                                    <button onclick="setup2FA()"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                        Setup 2FA
                                    </button>
                                </div>

                                <div id="qrSection" class="hidden space-y-4">
                                    <div class="text-center">
                                        <img id="qrcode" class="mx-auto" alt="QR Code">
                                        <p class="mt-2 text-xs text-gray-500">Scan with Google Authenticator</p>
                                    </div>

                                    <!-- Manual Entry Section -->
                                    <div class="mt-4 text-left p-4 bg-gray-50 rounded-lg">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Manual Entry</p>
                                        <p class="text-xs text-gray-600 mb-2">If you can't scan the QR code, enter this code manually:</p>
                                        <div class="flex items-center space-x-2">
                                            <code id="manualCode" class="px-2 py-1 bg-gray-100 rounded text-sm font-mono"></code>
                                            <button onclick="copyManualCode()" class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Enter Authentication Code
                                        </label>
                                        <input type="text" id="authCode"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Enter 6-digit code">
                                    </div>

                                    <button onclick="verify2FA()"
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                        Verify & Enable 2FA
                                    </button>
                                </div>
                            @else
                                <div class="space-y-6">
                                    <!-- 2FA Status -->
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-green-800">
                                                    Two-factor authentication is enabled
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button onclick="disable2FA()"
                                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        Disable 2FA
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                <!-- Account Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-600">StakeTRX Balance</h3>
                        <p class="text-xl font-bold text-gray-900">{{ number_format(auth()->user()->wallet->tronstake_balance, 6) }} StakeTRX</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Transactions</h3>
                        <p class="text-xl font-bold text-gray-900">{{ auth()->user()->trxTransactions()->count() }}</p>
                    </div>
                </div>

                <!-- Wallet Information -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Wallet Address</h2>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 bg-white rounded-lg px-3 py-2 border border-gray-200 overflow-x-auto">
                            <span class="font-mono text-xs sm:text-sm text-gray-800 whitespace-nowrap">{{ auth()->user()->wallet->address }}</span>
                        </div>
                        <div class="flex justify-start sm:justify-end space-x-2">
                            <button onclick="copyToClipboard('{{ auth()->user()->wallet->address }}', event)"
                                class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                            </button>
                            <a href="https://nile.tronscan.org/#/address/{{ auth()->user()->wallet->address }}"
                               target="_blank"
                               class="bg-purple-600 text-white p-2 rounded-lg hover:bg-purple-700 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Secure Information Section -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Secure Information</h2>

                    <!-- Password Verification Form -->
                    <div id="passwordVerification" class="mb-4">
                        <div class="flex flex-col space-y-2">
                            <input type="password" id="securityPassword" placeholder="Enter your password"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <button onclick="verifyPassword()"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Verify
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Enter your password to view secure information</p>
                    </div>

                    <!-- Secure Content (Hidden by default) -->
                    <div id="secureContent" class="hidden space-y-4">
                        <!-- Private Key -->
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Private Key</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 relative">
                                    <input type="password" id="privateKey"
                                        value=""
                                        placeholder="Click 'Show Private Key' to reveal"
                                        class="w-full bg-gray-50 rounded-lg px-3 py-2 text-sm font-mono text-gray-700"
                                        readonly>
                                </div>
                                <button onclick="togglePrivateKey()"
                                    class="bg-gray-600 text-white p-2 rounded-lg hover:bg-gray-700">
                                    <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="copyPrivateKey()"
                                    class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-red-600 mt-1">Never share your private key with anyone!</p>
                        </div>
                    </div>
                </div>

                <!-- Referral Section -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Referral Code</h2>

                    @php
                        $hasActiveStaking = auth()->user()->stakings()
                            ->where('status', 'active')
                            ->exists();
                    @endphp

                    @if($hasActiveStaking)
                        <!-- Mobile-first layout -->
                        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-2 mb-4">
                            <!-- Referral Code -->
                            <div class="flex-1 bg-white rounded-lg px-4 py-3 border border-gray-200 overflow-x-auto">
                                <span class="font-mono text-base sm:text-lg text-gray-800">{{ auth()->user()->referral_code }}</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('dashboard.referrals') }}"
                                   class="flex-1 sm:flex-none bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span class="hidden sm:inline">View History</span>
                                </a>

                                <button onclick="copyToClipboard('{{ auth()->user()->referral_code }}', event)"
                                    class="flex-1 sm:flex-none bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span class="hidden sm:inline">Copy</span>
                                </button>

                                <button onclick="shareReferral()"
                                    class="flex-1 sm:flex-none bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center justify-center space-x-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    <span class="hidden sm:inline">Share</span>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Share your referral code and earn rewards when friends join!</p>
                    @else
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-yellow-700">Activate a staking plan to unlock your referral code and start earning rewards!</p>
                            </div>
                            <a href="{{ route('dashboard.plans') }}" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-700">
                                View Staking Plans →
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Referral Statistics -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Referral Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white rounded-lg p-4 border border-gray-100">
                            <h3 class="text-sm font-medium text-gray-600">Total Referrals</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->referrals()->count() }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-100">
                            <h3 class="text-sm font-medium text-gray-600">Total Earnings</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format(auth()->user()->referral_earnings, 6) }} TRX</p>
                        </div>
                    </div>

                    <!-- Referred Users Table -->
                    <div class="mt-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Referred Users</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse(auth()->user()->referrals as $referral)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm">
                                                        {{ strtoupper(substr($referral->name, 0, 2)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $referral->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $referral->phone }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $referral->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($referral->referralTransactions()->sum('amount') ?? 0, 6) }} TRX
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
        </div>
    </div>
</div>

@include('layouts.mobile-nav')
@endsection

<!-- QRCode loaded from layout -->

<script>
async function setup2FA() {
    try {
        const response = await fetch('{{ route("2fa.setup") }}');
        const data = await response.json();

        if (data.success) {
            document.getElementById('setup2fa').classList.add('hidden');
            document.getElementById('qrSection').classList.remove('hidden');

            // Use the QR code image URL directly from server
            if (data.qrCodeImageUrl) {
                document.getElementById('qrcode').src = data.qrCodeImageUrl;
                document.getElementById('qrcode').style.display = 'block';
            } else {
                // Fallback: show manual entry only
                document.getElementById('qrcode').style.display = 'none';
            }

            // Set manual entry code
            if (data.secret) {
                document.getElementById('manualCode').textContent = data.secret;
            }
        }
    } catch (error) {
        console.error('Error setting up 2FA:', error);
        showNotification('Failed to setup 2FA. Please try again.', 'error');
    }
}

async function verify2FA() {
    const code = document.getElementById('authCode').value;

    try {
        const response = await fetch('{{ route("2fa.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code })
        });

        const result = await response.json();

        if (result.success) {
            alert('2FA enabled successfully!');
            window.location.reload();
        } else {
            alert(result.message || 'Invalid code. Please try again.');
        }
    } catch (error) {
        console.error('Error verifying 2FA:', error);
        alert('Failed to verify code. Please try again.');
    }
}

async function disable2FA() {
    if (!confirm('Are you sure you want to disable two-factor authentication?')) {
        return;
    }

    try {
        const response = await fetch('{{ route("2fa.disable") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            alert('2FA disabled successfully!');
            window.location.reload();
        } else {
            alert('Failed to disable 2FA. Please try again.');
        }
    } catch (error) {
        console.error('Error disabling 2FA:', error);
        alert('Failed to disable 2FA. Please try again.');
    }
}

async function copyManualCode() {
    const code = document.getElementById('manualCode').textContent;
    try {
        await navigator.clipboard.writeText(code);
        alert('Code copied to clipboard!');
    } catch (err) {
        alert('Failed to copy code');
    }
}

async function verifyPassword() {
    const button = document.querySelector('button[onclick="verifyPassword()"]');
    const password = document.getElementById('securityPassword').value;

    // Disable button and show loading state
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Verifying...
    `;

    try {
        const response = await fetch('{{ route("verify.password") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ password })
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('passwordVerification').classList.add('hidden');
            document.getElementById('secureContent').classList.remove('hidden');
            showNotification('Secure access granted. Private key can be revealed on demand.', 'success');
            
            // Add a button to load private key on demand
            addPrivateKeyLoadButton(password);
        } else {
            showNotification('Invalid password', 'error');
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to verify password', 'error');
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

function addPrivateKeyLoadButton(password) {
    const privateKeyContainer = document.getElementById('privateKey').parentElement;
    
    // Add load button if not exists
    if (!document.getElementById('loadPrivateKeyBtn')) {
        const loadButton = document.createElement('button');
        loadButton.id = 'loadPrivateKeyBtn';
        loadButton.className = 'bg-yellow-600 text-white p-2 rounded-lg hover:bg-yellow-700 text-sm';
        loadButton.innerHTML = 'Load Key';
        loadButton.onclick = () => loadPrivateKey(password);
        
        privateKeyContainer.appendChild(loadButton);
    }
}

async function loadPrivateKey(password) {
    const loadBtn = document.getElementById('loadPrivateKeyBtn');
    const originalText = loadBtn.innerHTML;
    
    loadBtn.disabled = true;
    loadBtn.innerHTML = 'Loading...';
    
    try {
        // This would use the SecureWalletController - we'll add this route next
        const response = await fetch('/api/secure/private-key', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ password })
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('privateKey').value = data.private_key;
            showNotification('Private key loaded (will auto-clear in 5 minutes)', 'success');
            
            // Auto-clear after 5 minutes for security
            setTimeout(() => {
                document.getElementById('privateKey').value = '';
                showNotification('Private key cleared for security', 'info');
            }, 300000);
            
            loadBtn.style.display = 'none';
        } else {
            showNotification(data.message || 'Failed to load private key', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to load private key', 'error');
    }
    
    loadBtn.disabled = false;
    loadBtn.innerHTML = originalText;
}

function togglePrivateKey() {
    const input = document.getElementById('privateKey');
    const eyeIcon = document.getElementById('eyeIcon');

    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

async function copyToClipboard(text, event = null) {
    try {
        await navigator.clipboard.writeText(text);
        showNotification('Copied to clipboard!', 'success');
    } catch (err) {
        showNotification('Failed to copy text', 'error');
    }
}

async function copyPrivateKey() {
    const privateKeyInput = document.getElementById('privateKey');
    const privateKeyValue = privateKeyInput.value;
    
    if (!privateKeyValue || privateKeyValue === '') {
        showNotification('Private key not loaded. Click "Load Key" first.', 'error');
        return;
    }
    
    try {
        await navigator.clipboard.writeText(privateKeyValue);
        showNotification('Private key copied to clipboard!', 'success');
    } catch (err) {
        showNotification('Failed to copy private key', 'error');
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function shareReferral() {
    const referralCode = '{{ auth()->user()->referral_code }}';
    const shareText = `🚀 Join TronX - The Future of Staking!\n\n` +
                     `💎 Use my referral code: ${referralCode}\n` +
                     `💰 Daily Returns up to 2%\n` +
                     `💫 3x Returns on Investment\n` +
                     `🔄 Easy TRX to StakeTRX conversion\n` +
                     `🎁 Get bonuses for referrals\n\n` +
                     `📱 Join now:\n` +
                     `{{ url('/register') }}?ref=${referralCode}`;

    try {
        if (navigator.share) {
            // For mobile devices with native share
            await navigator.share({
                title: 'Join TronX',
                text: shareText,
                url: `{{ url('/register') }}?ref=${referralCode}`
            });
        } else {
            // For desktop - copy to clipboard
            await navigator.clipboard.writeText(shareText);
            showNotification('Referral link copied to clipboard! Share it with your friends.', 'success');
        }
    } catch (error) {
        console.error('Error sharing:', error);
        // Fallback to copying
        try {
            await navigator.clipboard.writeText(shareText);
            showNotification('Referral link copied to clipboard!', 'success');
        } catch (clipboardError) {
            showNotification('Failed to share. Please copy your referral code manually.', 'error');
        }
    }
}
</script>
