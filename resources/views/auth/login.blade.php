@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-primary-50 flex items-center justify-center px-4 py-8 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/5 via-transparent to-secondary-500/5"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
    
    <div class="relative z-10 w-full max-w-6xl mx-auto">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Illustration/Branding -->
            <div class="hidden md:flex flex-col items-center justify-center text-center p-8">
                <div class="relative mb-8">
                    <!-- Modern Illustration -->
                    <div class="w-64 h-64 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-3xl transform rotate-6 shadow-2xl shadow-primary-500/20"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-3xl transform -rotate-6 shadow-2xl shadow-secondary-500/20"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-secondary-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-primary-500/30">
                            <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent mb-4">
                            Welcome to Miles
                        </h1>
                        <p class="text-xl text-neutral-600 leading-relaxed">
                            Your gateway to professional staking and cryptocurrency earning opportunities
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-neutral-700">
                            <div class="w-10 h-10 bg-success-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span>Secure blockchain technology</span>
                        </div>
                        <div class="flex items-center space-x-3 text-neutral-700">
                            <div class="w-10 h-10 bg-primary-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span>Daily earning opportunities</span>
                        </div>
                        <div class="flex items-center space-x-3 text-neutral-700">
                            <div class="w-10 h-10 bg-secondary-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <span>Professional grade security</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="w-full max-w-md mx-auto">
                <!-- Enhanced Logo for Mobile -->
                <div class="md:hidden text-center mb-8">
                    <div class="inline-flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">Miles</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Welcome Back</h1>
                        <p class="text-neutral-600">Sign in to your account</p>
                    </div>
                </div>
                
                <!-- Enhanced Login Form -->
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-slate-200/50 border border-white/20 p-8">
                    <div class="hidden md:block text-center mb-8">
                        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Welcome Back</h2>
                        <p class="text-neutral-600">Please sign in to your account</p>
                    </div>
                    
                    <form id="loginForm" class="space-y-6" action="{{ route('login') }}" method="POST" onsubmit="handleLogin(event)">
                        @csrf

                        <!-- Enhanced Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-neutral-700">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" required
                                    class="w-full pl-12 pr-4 py-3 bg-white/60 border border-neutral-200 rounded-2xl text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                    placeholder="Enter your email address"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="text-sm text-error-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Enhanced Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-neutral-700">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="w-full pl-12 pr-12 py-3 bg-white/60 border border-neutral-200 rounded-2xl text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                    placeholder="Enter your password">
                                <button type="button" onclick="togglePassword('password', 'toggleIcon')" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg id="toggleIcon" class="h-5 w-5 text-neutral-400 hover:text-neutral-600 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm text-error-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Enhanced Forgot Password Link -->
                        <div class="text-right">
                            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                Forgot your password?
                            </a>
                        </div>

                        <!-- Enhanced Sign In Button -->
                        <button type="submit" id="loginButton"
                            class="w-full py-4 px-6 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none">
                            <div class="flex items-center justify-center">
                                <svg id="loginIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <svg id="loginLoadingIcon" class="hidden w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="loginButtonText">Sign In</span>
                            </div>
                        </button>

                        <!-- Enhanced Status Message -->
                        <div id="loginStatusMessage" class="text-sm text-center hidden"></div>
                    </form>
                </div>

                <!-- Enhanced Divider -->
                <div class="my-8 flex items-center">
                    <div class="flex-1 border-t border-neutral-200"></div>
                    <span class="px-4 text-neutral-500 text-sm font-medium">or</span>
                    <div class="flex-1 border-t border-neutral-200"></div>
                </div>

                <!-- Enhanced Sign Up Link -->
                <div class="text-center">
                    <p class="text-neutral-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors ml-1">
                            Create one now
                        </a>
                    </p>
                </div>

                <!-- Enhanced Footer Links -->
                <div class="mt-8 text-center space-y-4">
                    <div class="flex justify-center space-x-6">
                        <a href="#" class="text-neutral-400 hover:text-neutral-600 transition-colors" title="Support">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-neutral-400 hover:text-neutral-600 transition-colors" title="Security">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-neutral-400 hover:text-neutral-600 transition-colors" title="Privacy">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                    </div>
                    <p class="text-xs text-neutral-500">
                        Secure • Encrypted • Trusted
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced login form handling with improved loading state
function handleLogin(event) {
    event.preventDefault();
    setLoginLoading(true, 'Signing you in...');
    
    // Add a small delay to show loading state, then submit
    setTimeout(() => {
        event.target.submit();
    }, 500);
}

function setLoginLoading(isLoading, message = '') {
    const button = document.getElementById('loginButton');
    const buttonText = document.getElementById('loginButtonText');
    const loginIcon = document.getElementById('loginIcon');
    const loadingIcon = document.getElementById('loginLoadingIcon');
    const statusMessage = document.getElementById('loginStatusMessage');

    if (isLoading) {
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        loginIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');
        buttonText.textContent = 'Signing In...';
        if (message) {
            statusMessage.textContent = message;
            statusMessage.classList.remove('hidden', 'text-error-600');
            statusMessage.classList.add('text-neutral-600');
        }
    } else {
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        loginIcon.classList.remove('hidden');
        loadingIcon.classList.add('hidden');
        buttonText.textContent = 'Sign In';
        if (message) {
            statusMessage.textContent = message;
            statusMessage.classList.remove('hidden', 'text-neutral-600');
            statusMessage.classList.add('text-error-600');
        } else {
            statusMessage.classList.add('hidden');
        }
    }
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-.742.742m4.242 4.242l.742.742m0 0l1.414 1.414M14.122 14.122l1.414 1.414" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection
