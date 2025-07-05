<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Miles') }}</title>
    <!-- Ghost Deployment: All libraries hosted locally -->
    <script src="{{ asset('js/tronweb-local.js') }}"></script>
    <script src="{{ asset('js/shared-functions.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Preload Inter font for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    <!-- Enhanced Background with Gradient -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-sky-50 relative overflow-hidden">
        <!-- Subtle background pattern -->
        <div class="absolute inset-0 bg-white/40 backdrop-blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/5 via-transparent to-secondary-500/5"></div>
        
        <!-- Navigation with Glassmorphism -->
        <nav class="relative bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-lg shadow-slate-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Enhanced Logo -->
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="group flex items-center space-x-3">
                                <!-- Modern Logo Icon -->
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:shadow-xl group-hover:shadow-primary-500/40 transition-all duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <!-- Enhanced Brand Text -->
                                <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent group-hover:from-primary-700 group-hover:to-secondary-700 transition-all duration-300">
                                    Miles
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation (Right Side) -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Enhanced Profile Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="group flex items-center space-x-3 focus:outline-none p-2 rounded-2xl hover:bg-white/50 transition-all duration-300">
                                    <!-- Enhanced Avatar -->
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center text-white overflow-hidden shadow-lg shadow-primary-500/30 group-hover:shadow-xl group-hover:shadow-primary-500/40 transition-all duration-300">
                                        <span class="text-sm font-semibold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <!-- Enhanced User Info -->
                                    <div class="hidden md:flex flex-col items-start">
                                        <span class="text-sm font-medium text-neutral-700 group-hover:text-neutral-900 transition-colors">{{ auth()->user()->name }}</span>
                                        <span class="text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                                    </div>
                                    <!-- Enhanced Dropdown Arrow -->
                                    <svg class="w-4 h-4 text-neutral-500 group-hover:text-neutral-700 transition-all duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Enhanced Dropdown Menu -->
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-64 bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/20 py-2 z-50"
                                     style="display: none;">
                                    
                                    <!-- User Info Header -->
                                    <div class="px-4 py-3 border-b border-white/10">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center text-white">
                                                <span class="text-xs font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-neutral-900">{{ auth()->user()->name }}</div>
                                                <div class="text-xs text-neutral-500">{{ auth()->user()->email }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Menu Items -->
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-2 text-sm text-neutral-700 hover:bg-white/60 hover:text-neutral-900 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-3 text-neutral-500 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('profile') }}" class="group flex items-center px-4 py-2 text-sm text-neutral-700 hover:bg-white/60 hover:text-neutral-900 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-3 text-neutral-500 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                        <div class="border-t border-white/10 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="group flex items-center w-full px-4 py-2 text-sm text-error-600 hover:bg-error-50 hover:text-error-700 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-3 text-error-500 group-hover:text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Enhanced Authentication Links -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('login') }}" class="text-neutral-700 hover:text-primary-600 font-medium transition-colors duration-200 px-3 py-2 rounded-xl hover:bg-white/50">
                                    Log in
                                </a>
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-2 rounded-xl font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40">
                                    Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="relative z-10">
            @yield('content')
        </main>
    </div>

    <!-- Enhanced Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Enhanced Notification System -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none">
        <!-- Dynamic notifications will be inserted here -->
    </div>
    
    <style>
        /* Enhanced Notification Styles */
        .notification {
            @apply bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 p-4 transform transition-all duration-300 ease-out pointer-events-auto;
            animation: slideInRight 0.3s ease-out;
        }
        
        .notification.success {
            @apply border-success-200 bg-success-50/90;
        }
        
        .notification.error {
            @apply border-error-200 bg-error-50/90;
        }
        
        .notification.warning {
            @apply border-warning-200 bg-warning-50/90;
        }
        
        .notification.info {
            @apply border-primary-200 bg-primary-50/90;
        }
        
        .notification.hide {
            animation: slideOutRight 0.3s ease-out forwards;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        /* Enhanced Backdrop Blur Support */
        @supports (backdrop-filter: blur(24px)) {
            .backdrop-blur-xl {
                backdrop-filter: blur(24px);
            }
        }
        
        /* Enhanced Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
</body>
</html>
