@extends('layouts.app')

@section('content')
<div class="relative min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-purple-900/20 to-transparent"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 pt-20 pb-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight animate-fade-in">
                    <span class="block bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Earn Up To 2.73% Daily
                    </span>
                    <span class="block text-3xl md:text-4xl lg:text-5xl mt-2 animate-slide-up">
                        With Miles Staking
                    </span>
                </h1>
                <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-2xl mx-auto animate-fade-in">
                    Join thousands of investors earning passive income through our secure staking platform
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 animate-slide-up">
                    <a href="{{ route('register') }}"
                        class="btn-primary touch-target transform hover:scale-105 transition-all duration-200">
                        Start Earning Now →
                    </a>
                    <a href="#how-it-works"
                        class="btn-secondary touch-target">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mt-16 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="glass-card p-4 sm:p-6 text-center touch-target">
                    <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">$10M+</div>
                    <div class="text-blue-100 text-sm sm:text-base">Total Value Locked</div>
                </div>
                <div class="glass-card p-4 sm:p-6 text-center touch-target">
                    <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">25K+</div>
                    <div class="text-blue-100 text-sm sm:text-base">Active Users</div>
                </div>
                <div class="glass-card p-4 sm:p-6 text-center touch-target">
                    <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">2.73%</div>
                    <div class="text-blue-100 text-sm sm:text-base">Max Daily Returns</div>
                </div>
                <div class="glass-card p-4 sm:p-6 text-center touch-target">
                    <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">24/7</div>
                    <div class="text-blue-100 text-sm sm:text-base">Live Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="bg-gray-900 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-white">
                Start Earning in 3 Simple Steps
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="glass-card bg-gray-800/50 p-6 sm:p-8 relative overflow-hidden">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center text-2xl font-bold text-white shadow-lg">1</div>
                    <div class="mt-4">
                        <h3 class="text-xl font-semibold text-white mb-3">Register & Deposit</h3>
                        <p class="text-gray-400">Create your account and deposit TRX tokens to get started (minimum 150 TRX)</p>
                    </div>
                </div>
                <div class="glass-card bg-gray-800/50 p-6 sm:p-8 relative overflow-hidden">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center text-2xl font-bold text-white shadow-lg">2</div>
                    <div class="mt-4">
                        <h3 class="text-xl font-semibold text-white mb-3">Choose Your Plan</h3>
                        <p class="text-gray-400">Select from our high-yield staking plans with daily returns up to 2.73%</p>
                    </div>
                </div>
                <div class="glass-card bg-gray-800/50 p-6 sm:p-8 relative overflow-hidden">
                    <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center text-2xl font-bold text-white shadow-lg">3</div>
                    <div class="mt-4">
                        <h3 class="text-xl font-semibold text-white mb-3">Earn & Withdraw Daily</h3>
                        <p class="text-gray-400">Watch your earnings grow with daily payments. Withdraw as little as 10 TRX within 5 minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How We Generate Returns Section -->
    <div class="bg-gradient-to-b from-gray-900 to-gray-800 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    How We Generate 2-3% Daily Returns
                </h2>
                <p class="text-lg text-gray-300 max-w-3xl mx-auto">
                    Our proven crypto lending model that generates consistent high returns through strategic partnerships
                </p>
            </div>

            <!-- Main Explanation Card -->
            <div class="glass-card p-6 sm:p-8 md:p-12 mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-white">Crypto Lending Business Model</h3>
                        </div>
                        <p class="text-gray-300 text-base sm:text-lg leading-relaxed mb-6">
                            We provide crypto loans to verified professional traders who need capital for high-frequency trading, 
                            arbitrage opportunities, and market making activities. Due to extended repayment terms and flexible 
                            conditions, we charge premium interest rates that enable us to share substantial returns with our investors.
                        </p>
                        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
                            <p class="text-emerald-300 font-medium text-sm sm:text-base">
                                💡 All borrowers undergo strict KYC verification and provide collateral for loan security
                            </p>
                        </div>
                    </div>
                    
                    <!-- 3D Money Flow Diagram -->
                    <div class="relative">
                        <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                            <h4 class="text-lg sm:text-xl font-semibold text-white mb-6 text-center">Money Flow Diagram</h4>
                            
                            <!-- Mobile-First Vertical Flow -->
                            <div class="flex flex-col items-center space-y-4 sm:space-y-6 py-4">
                                
                                <!-- Step 1: Investors -->
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="relative">
                                        <div class="w-20 h-12 sm:w-28 sm:h-16 rounded-xl shadow-lg flex items-center justify-center float-animation bg-gradient-to-br from-blue-500 to-blue-600">
                                            <span class="text-white text-xs sm:text-sm font-bold">Investors</span>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-20 h-12 sm:w-28 sm:h-16 rounded-xl -z-10 bg-blue-800/70"></div>
                                    </div>
                                </div>

                                <!-- Arrow Down with Label -->
                                <div class="flex flex-col items-center space-y-2">
                                    <div class="text-emerald-400 text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 rounded-full bg-emerald-500/10">💰 Deposit TRX</div>
                                    <svg class="w-6 h-8 text-emerald-400 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v10.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                                <!-- Step 2: Miles Pool -->
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="relative">
                                        <div class="w-24 h-14 sm:w-32 sm:h-20 rounded-xl shadow-lg flex items-center justify-center float-animation bg-gradient-to-br from-purple-500 to-purple-600" style="animation-delay: 0.5s;">
                                            <span class="text-white text-xs sm:text-sm font-bold">Miles Pool</span>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-24 h-14 sm:w-32 sm:h-20 rounded-xl -z-10 bg-purple-800/70"></div>
                                    </div>
                                </div>

                                <!-- Horizontal Flow for Loans -->
                                <div class="w-full max-w-xs sm:max-w-sm">
                                    <div class="flex items-center justify-between">
                                        <!-- Loan Out -->
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="text-yellow-400 text-xs font-medium px-2 py-1 rounded bg-yellow-500/10">🏦 Loans</div>
                                            <svg class="w-6 h-6 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Interest Back -->
                                        <div class="flex flex-col items-center space-y-2">
                                            <svg class="w-6 h-6 text-green-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20" style="animation-delay: 1s;">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="text-green-400 text-xs font-medium px-2 py-1 rounded bg-green-500/10">💎 Interest</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Traders -->
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="relative">
                                        <div class="w-24 h-12 sm:w-32 sm:h-16 rounded-xl shadow-lg flex items-center justify-center float-animation bg-gradient-to-br from-orange-500 to-orange-600" style="animation-delay: 1s;">
                                            <span class="text-white text-xs sm:text-sm font-bold">KYC Traders</span>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-24 h-12 sm:w-32 sm:h-16 rounded-xl -z-10 bg-orange-800/70"></div>
                                    </div>
                                </div>

                                <!-- Return Flow -->
                                <div class="flex flex-col items-center space-y-4 mt-6">
                                    <div class="w-px h-8 sm:h-12 bg-gradient-to-b from-emerald-400 to-transparent"></div>
                                    <div class="text-emerald-400 text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 rounded-full bg-emerald-500/10">📈 Daily Returns</div>
                                    <svg class="w-6 h-8 text-emerald-400 animate-bounce" fill="currentColor" viewBox="0 0 20 20" style="animation-delay: 2s;">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v10.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="glass-card bg-gradient-to-r from-emerald-900/30 to-blue-900/30 p-6 sm:p-8">
                <div class="text-center">
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-4">Why Our Lending Model Works</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 mt-8">
                        <div class="text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">$50M+</div>
                            <p class="text-gray-300 text-sm sm:text-base">Total Loans Disbursed</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">98.5%</div>
                            <p class="text-gray-300 text-sm sm:text-base">Loan Recovery Rate</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-400 mb-2">500+</div>
                            <p class="text-gray-300 text-sm sm:text-base">Verified Trader Partners</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consolidated Features Section -->
    <div class="bg-gray-800 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-white">Why Choose Miles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Low Minimums</h3>
                    <p class="text-gray-400">Start with just 150 TRX and withdraw as little as 10 TRX</p>
                </div>
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Lightning Fast</h3>
                    <p class="text-gray-400">Withdrawals processed within 5 minutes, 24/7</p>
                </div>
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">High Returns</h3>
                    <p class="text-gray-400">Earn up to 2.73% daily returns on your staked TRX</p>
                </div>
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Bank-Grade Security</h3>
                    <p class="text-gray-400">2FA protection and enterprise-grade security with KYC verification</p>
                </div>
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">24/7 Live Support</h3>
                    <p class="text-gray-400">Round-the-clock customer support via chat</p>
                </div>
                <div class="glass-card bg-gray-700/50 p-6 hover:bg-gray-700/70 group">
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">5% Referral Bonus</h3>
                    <p class="text-gray-400">Earn 5% commission on every referral you bring</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-900 py-16 sm:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-300">Get answers to the most common questions about Miles staking</p>
            </div>

            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(1)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">How does Miles staking work?</h3>
                        <svg id="faq-icon-1" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-1" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p>Miles operates a crypto lending business model. When you stake TRX with us, your funds are pooled with other investors and lent to verified professional traders who need capital for trading activities. These traders pay premium interest rates due to our flexible repayment terms, and we share these profits with our stakers as daily returns of up to 2.73%.</p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(2)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">How do I deposit TRX to Miles?</h3>
                        <svg id="faq-icon-2" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-2" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p><strong>Step-by-step deposit process:</strong></p>
                        <ol class="list-decimal list-inside mt-2 space-y-2">
                            <li>Register and log into your Miles account</li>
                            <li>Click the "Deposit" button in your dashboard</li>
                            <li>Copy your unique TRX/TRC20 deposit address</li>
                            <li>Go to your wallet (TronLink, Trust Wallet) or exchange (Binance, etc.)</li>
                            <li>Send TRX tokens to the provided address (minimum 150 TRX)</li>
                            <li>Wait for blockchain confirmation (usually 1-3 minutes)</li>
                            <li>Your balance will update automatically once confirmed</li>
                        </ol>
                        <div class="mt-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg">
                            <p class="text-emerald-300 text-sm"><strong>Important:</strong> Only send TRX or TRC20 tokens to this address. Sending other cryptocurrencies may result in permanent loss.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(3)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">What are the minimum deposit and withdrawal amounts?</h3>
                        <svg id="faq-icon-3" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-3" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p><strong>Minimum Deposit:</strong> 150 TRX - This allows you to start earning daily returns immediately.</p>
                        <p class="mt-2"><strong>Minimum Withdrawal:</strong> 10 TRX - You can withdraw your earnings daily with this low minimum, ensuring you have access to your profits without waiting for large amounts to accumulate.</p>
                        <p class="mt-2 text-emerald-300">Our low minimums make Miles accessible to investors of all sizes, from beginners to large-scale investors.</p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(4)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">How fast are withdrawals processed?</h3>
                        <svg id="faq-icon-4" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-4" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p>Withdrawals are processed within <strong>5 minutes</strong>, 24/7. This is one of the fastest processing times in the industry.</p>
                        <p class="mt-2">Our automated system ensures that once you request a withdrawal, your TRX is sent to your wallet almost instantly, allowing you to access your earnings whenever you need them.</p>
                        <div class="mt-3 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <p class="text-blue-300 text-sm">💡 <strong>Pro Tip:</strong> You can withdraw daily earnings immediately - no waiting periods or complex procedures required.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(5)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">What staking plans are available and what returns can I expect?</h3>
                        <svg id="faq-icon-5" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-5" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p>We offer multiple staking plans with daily returns ranging from <strong>1.5% to 2.73%</strong> depending on your chosen plan duration and amount staked.</p>
                        <p class="mt-2"><strong>Plan Features:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Daily interest payments automatically credited to your account</li>
                            <li>Flexible plan durations from short-term to long-term options</li>
                            <li>Higher returns for longer commitment periods</li>
                            <li>Compound interest available - reinvest earnings for exponential growth</li>
                        </ul>
                        <p class="mt-3 text-emerald-300">Visit your dashboard after registration to see all available plans with detailed terms and projected earnings.</p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(6)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">Is Miles safe and secure? What security measures do you have?</h3>
                        <svg id="faq-icon-6" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-6" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p><strong>Security Measures:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong>2FA Authentication:</strong> Two-factor authentication for all accounts</li>
                            <li><strong>KYC Verification:</strong> All borrowers undergo strict identity verification</li>
                            <li><strong>Collateral-Backed Loans:</strong> All loans are secured with crypto collateral</li>
                            <li><strong>Enterprise-Grade Security:</strong> Bank-level encryption and security protocols</li>
                            <li><strong>Risk Management:</strong> Diversified lending portfolio to minimize risk</li>
                            <li><strong>98.5% Recovery Rate:</strong> Proven track record of loan recovery</li>
                        </ul>
                        <p class="mt-3 text-emerald-300">With over $50M+ in loans disbursed and 500+ verified trader partners, we have a proven track record of security and reliability.</p>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(7)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">How does the referral program work?</h3>
                        <svg id="faq-icon-7" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-7" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p>Earn <strong>5% commission</strong> on every person you refer to Miles!</p>
                        <p class="mt-2"><strong>How it works:</strong></p>
                        <ol class="list-decimal list-inside mt-2 space-y-1">
                            <li>Get your unique referral link from your dashboard</li>
                            <li>Share it with friends, family, or on social media</li>
                            <li>When someone registers using your link and makes their first deposit</li>
                            <li>You earn 5% of their total staking earnings as commission</li>
                            <li>Commissions are paid daily along with your regular staking returns</li>
                        </ol>
                        <p class="mt-3 text-emerald-300">The more active referrals you have, the more passive income you can generate. There's no limit to how much you can earn from referrals!</p>
                    </div>
                </div>

                <!-- FAQ Item 8 -->
                <div class="glass-card bg-gray-800/50 p-4 sm:p-6">
                    <button class="flex items-center justify-between w-full text-left touch-target" onclick="toggleFAQ(8)">
                        <h3 class="text-lg sm:text-xl font-semibold text-white pr-4">What support is available if I need help?</h3>
                        <svg id="faq-icon-8" class="w-6 h-6 text-emerald-400 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-content-8" class="mt-4 text-gray-300 text-sm sm:text-base hidden">
                        <p>We provide <strong>24/7 live support</strong> to assist you with any questions or issues.</p>
                        <p class="mt-2"><strong>Support Channels:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong>Live Chat:</strong> Instant support available 24/7 on our website</li>
                            <li><strong>Email Support:</strong> Detailed assistance for complex queries</li>
                            <li><strong>FAQ Section:</strong> Comprehensive answers to common questions</li>
                            <li><strong>Video Tutorials:</strong> Step-by-step guides for all platform features</li>
                        </ul>
                        <p class="mt-3 text-emerald-300">Our dedicated support team is trained to help with deposits, withdrawals, staking plans, referrals, and any technical issues you might encounter.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Start Earning?</h2>
            <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of investors already earning passive income with Miles
            </p>
            <a href="{{ route('register') }}"
                class="btn-primary touch-target transform hover:scale-105 transition-all duration-200">
                Create Account Now →
            </a>
        </div>
    </div>
</div>

<script>
// Enhanced FAQ functionality with better mobile support
function toggleFAQ(id) {
    const content = document.getElementById(`faq-content-${id}`);
    const icon = document.getElementById(`faq-icon-${id}`);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        content.classList.add('animate-slide-down');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        content.classList.remove('animate-slide-down');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in');
        }
    });
}, observerOptions);

// Observe all sections
document.querySelectorAll('section, .glass-card').forEach(section => {
    observer.observe(section);
});
</script>

<style>
/* Essential CSS for animations and mobile improvements */
html {
    scroll-behavior: smooth;
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out 0.2s both;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 0; max-height: 0; }
    to { opacity: 1; max-height: 200px; }
}

/* Enhanced mobile touch targets */
.touch-target {
    min-height: 48px;
    min-width: 48px;
    touch-action: manipulation;
}

/* Better button press feedback */
.btn-primary:active, .btn-secondary:active {
    transform: scale(0.98);
}

/* Improved glass card interactions */
.glass-card {
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-2px);
}

/* FAQ mobile improvements */
@media (max-width: 640px) {
    .glass-card {
        margin-bottom: 1rem;
    }
    
    .touch-target {
        min-height: 52px;
        padding: 0.75rem;
    }
}

/* Floating animation for diagram elements */
.float-animation {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}
</style>
@endsection
