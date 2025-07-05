@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 md:pb-8">
    <!-- Hero Section with Glassmorphism -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-pink-500/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-gray-600 text-lg">Manage your staking and track your earnings</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Staked Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-600 mb-1">Total Staked</div>
                            <div class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalStaked = auth()->user()->stakings ?
                                        auth()->user()->stakings->where('status', 'active')->sum('amount') : 0;
                                @endphp
                                {{ number_format($totalStaked, 2) }}
                            </div>
                            <div class="text-xs text-gray-500">TRX</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earned Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-500 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-600 mb-1">Total Earned</div>
                            <div class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalEarned = auth()->user()->stakings ?
                                        auth()->user()->stakings->sum('earned_amount') : 0;
                                @endphp
                                {{ number_format($totalEarned, 2) }}
                            </div>
                            <div class="text-xs text-gray-500">TRX</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APY Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-600 mb-1">Current APY</div>
                            <div class="text-2xl font-bold text-gray-900">2.73%</div>
                            <div class="text-xs text-gray-500">Daily</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MilesCoin Balance Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-600 mb-1">MilesCoin</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format(auth()->user()->wallet->miles_balance ?? 0, 2) }}
                            </div>
                            <div class="text-xs text-gray-500">MSC</div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Wallet Information Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Your Wallet</h2>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>

                    @if(auth()->user()->wallet)
                        <div class="space-y-6">
                            <!-- Wallet Address -->
                            <div class="bg-gray-50/50 rounded-2xl p-4">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Wallet Address</label>
                                <div class="flex items-center space-x-3">
                                    <input type="text" value="{{ auth()->user()->wallet->address }}"
                                        class="flex-1 bg-white/70 rounded-xl px-4 py-3 text-gray-800 text-sm font-mono border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" readonly>
                                    <button onclick="copyToClipboard('{{ auth()->user()->wallet->address }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                                <a href="https://nile.tronscan.org/#/address/{{ auth()->user()->wallet->address }}"
                                   target="_blank"
                                   class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    View on TronScan
                                </a>
                            </div>

                            <!-- Balance Display -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Available Balance</label>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span id="wallet-balance" class="text-3xl font-bold text-gray-900">Loading...</span>
                                            <span class="text-lg text-gray-600">TRX</span>
                                        </div>
                                        <div id="usd-balance" class="text-sm text-gray-500 mt-1">≈ $0.00 USD</div>
                                    </div>
                                    <button onclick="refreshBalance()"
                                        class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                <a href="https://nile.tronscan.org/#/wallet/contract"
                                   target="_blank"
                                   class="inline-flex items-center mt-3 text-sm text-green-600 hover:text-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Get Test TRX
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-red-600 font-medium">No wallet found. Please contact support.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Staking Actions Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Staking Actions</h2>
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Convert TRX to MilesCoin -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Convert TRX to MilesCoin</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="convert-amount" class="block text-sm font-medium text-gray-700 mb-2">Amount to Convert</label>
                                <div class="flex items-center space-x-3">
                                    <input type="number" id="convert-amount"
                                        class="flex-1 bg-white/70 rounded-xl px-4 py-3 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        step="0.000001" min="1" placeholder="Enter TRX amount">
                                    <button onclick="checkAndConvert()"
                                        class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium">
                                        Convert
                                    </button>
                                </div>
                            </div>
                            <div id="conversion-status" class="text-sm"></div>
                        </div>
                    </div>

                    <!-- Stake TRX -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Stake TRX</h3>
                        <form action="{{ route('stake') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount to Stake</label>
                                <div class="flex items-center space-x-3">
                                    <input type="number" id="amount" name="amount" step="0.000001" min="1"
                                        class="flex-1 bg-white/70 rounded-xl px-4 py-3 border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                                        required placeholder="Enter amount">
                                    <span class="text-gray-600 font-medium">TRX</span>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Stake Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition-opacity"></div>
            <div class="relative bg-white/70 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Recent Activity</h2>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Placeholder for recent transactions -->
                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Staking Rewards</p>
                                <p class="text-sm text-gray-500">Daily rewards credited</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">+12.45 TRX</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Staking Deposit</p>
                                <p class="text-sm text-gray-500">New stake created</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-blue-600">500 TRX</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>

                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">More detailed activity tracking coming soon</p>
                    </div>
                </div>
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

<style>
/* Mobile-First Responsive Enhancements */
@media (max-width: 640px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-8 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}

/* Touch-friendly button sizing */
button, input[type="submit"] {
    min-height: 48px;
    min-width: 48px;
}

/* Improved focus states for accessibility */
button:focus, input:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Smooth transitions for better UX */
* {
    transition: all 0.2s ease-in-out;
}

/* Neumorphism effects */
.neumorphism {
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    box-shadow: 20px 20px 60px #d0d0d0, -20px -20px 60px #ffffff;
}

.neumorphism-inset {
    background: linear-gradient(145deg, #f0f0f0, #ffffff);
    box-shadow: inset 20px 20px 60px #d0d0d0, inset -20px -20px 60px #ffffff;
}

/* Glassmorphism effects */
.glassmorphism {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

/* Loading animations */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 2s infinite;
}

/* Card hover effects */
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Improved scrollbar styling */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #3b82f6, #8b5cf6);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #2563eb, #7c3aed);
}
</style>
@endsection
