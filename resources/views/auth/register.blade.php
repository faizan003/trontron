@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-sm">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                    <path d="M16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Join TronX</h1>
            <p class="text-gray-600 text-sm">Create your staking account</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <form id="registerForm" class="space-y-5" onsubmit="handleRegister(event)">
                @csrf

                <!-- Name Field -->
                <div class="relative">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    <input id="name" name="name" type="text" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Enter your full name"
                            value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="relative">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                    <input id="email" name="email" type="email" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Enter your email"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <div class="flex space-x-2">
                        <div class="w-24">
                            <select id="country_code" name="country_code" required
                                class="w-full h-12 px-2 text-xs border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                                <option value="+91">🇮🇳 +91</option>
                                <option value="+92">🇵🇰 +92</option>
                                <option value="+977">🇳🇵 +977</option>
                                <option value="+880">🇧🇩 +880</option>
                                <option value="+94">🇱🇰 +94</option>
                                <option value="+86">🇨🇳 +86</option>
                                <option value="+81">🇯🇵 +81</option>
                                <option value="+82">🇰🇷 +82</option>
                                <option value="+65">🇸🇬 +65</option>
                                <option value="+60">🇲🇾 +60</option>
                                <option value="+66">🇹🇭 +66</option>
                                <option value="+84">🇻🇳 +84</option>
                                <option value="+62">🇮🇩 +62</option>
                                <option value="+63">🇵🇭 +63</option>
                                <option value="+234">🇳🇬 +234</option>
                                <option value="+27">🇿🇦 +27</option>
                                <option value="+254">🇰🇪 +254</option>
                                <option value="+20">🇪🇬 +20</option>
                                <option value="+971">🇦🇪 +971</option>
                                <option value="+966">🇸🇦 +966</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                            </select>
                        </div>
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input id="phone" name="phone" type="tel" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                placeholder="Phone number">
                        </div>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    <input id="password" name="password" type="password" required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Create password">
                        <button type="button" onclick="togglePassword('password', 'passwordToggleIcon')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="passwordToggleIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        placeholder="Confirm password">
                        <button type="button" onclick="togglePassword('password_confirmation', 'confirmPasswordToggleIcon')" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="confirmPasswordToggleIcon" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Referral Code Field -->
                <div class="relative">
                    <label for="referral_code" class="block text-sm font-medium text-gray-700 mb-2">Referral Code (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    <input id="referral_code" name="referral_code" type="text"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Enter referral code">
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-start space-x-3 py-2">
                    <input id="terms" name="terms" type="checkbox" required
                        class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                    <label for="terms" class="text-sm text-gray-700 leading-5">
                        I agree to the
                        <button type="button" onclick="openTermsModal()" class="text-indigo-600 font-medium hover:text-indigo-500 transition-colors underline">Terms of Service</button>
                        and
                        <button type="button" onclick="openPrivacyModal()" class="text-indigo-600 font-medium hover:text-indigo-500 transition-colors underline">Privacy Policy</button>
                    </label>
                </div>

                <!-- Create Account Button -->
                    <button type="submit" id="registerButton"
                    class="w-full py-3 px-6 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="flex items-center justify-center">
                        <svg id="registerIcon" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                                <path d="M16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        <svg id="loadingIcon" class="hidden w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        <span id="buttonText">Create Account</span>
                    </div>
                    </button>

                <!-- Status Message -->
                <div id="statusMessage" class="text-sm text-center hidden"></div>
            </form>
        </div>

        <!-- Divider -->
        <div class="my-8 flex items-center">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="px-4 text-gray-500 text-sm">or</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Sign In Link -->
        <div class="text-center">
            <p class="text-gray-600 text-sm">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-500 transition-colors ml-1">
                    Sign in instead
                </a>
            </p>
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center space-y-4">
            <div class="flex justify-center space-x-6 text-gray-400">
                <a href="#" class="hover:text-gray-600 transition-colors" title="Support">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="hover:text-gray-600 transition-colors" title="Security">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="hover:text-gray-600 transition-colors" title="Privacy">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <p class="text-xs text-gray-500">
                Secure • Encrypted • Blockchain Protected
            </p>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeTermsModal()"></div>
    
    <!-- Modal -->
    <div class="fixed inset-x-0 bottom-0 transform translate-y-full transition-transform duration-300 ease-out" id="termsModalContent">
        <div class="bg-white rounded-t-2xl shadow-xl max-h-[85vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-xl font-semibold text-gray-900">Terms of Service</h3>
                <button onclick="closeTermsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="space-y-6 text-gray-700">
                    <!-- Company Info -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">About TronX</h4>
                        <p class="text-sm leading-6">
                            TronX is a decentralized staking platform built on the TRON blockchain. We provide secure and transparent staking services to help you earn passive income through cryptocurrency staking.
                        </p>
                    </div>

                    <!-- How We Work -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">How TronX Works</h4>
                        <ul class="text-sm space-y-2 list-disc list-inside">
                            <li>Create an account and get a secure TRON wallet automatically</li>
                            <li>Choose from our three staking plans: Premium, Advanced, or Elite</li>
                            <li>Deposit TRX tokens to start earning daily rewards</li>
                            <li>Receive automated daily interest payments</li>
                            <li>Withdraw your earnings anytime</li>
                            <li>Refer friends to earn 5% commission on their stakes</li>
                        </ul>
                    </div>

                    <!-- Staking Plans -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Our Staking Plans</h4>
                        <div class="grid grid-cols-1 gap-4 text-sm">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-medium text-indigo-600">Premium Plan</h5>
                                <p>150-350 TRX • 2% Daily • 150 Days</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-medium text-indigo-600">Advanced Plan</h5>
                                <p>400-800 TRX • 2.31% Daily • 130 Days</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-medium text-indigo-600">Elite Plan</h5>
                                <p>850-5000 TRX • 2.73% Daily • 110 Days</p>
                            </div>
                        </div>
                    </div>

                    <!-- Process -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Our Process</h4>
                        <div class="text-sm space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
                                <div>
                                    <p class="font-medium">Registration & Wallet Creation</p>
                                    <p class="text-gray-600">Secure wallet automatically generated using TRON blockchain technology</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">2</div>
                                <div>
                                    <p class="font-medium">Choose Staking Plan</p>
                                    <p class="text-gray-600">Select a plan that fits your investment goals and risk appetite</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">3</div>
                                <div>
                                    <p class="font-medium">Stake TRX Tokens</p>
                                    <p class="text-gray-600">Deposit TRX to your staking plan and start earning immediately</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">4</div>
                                <div>
                                    <p class="font-medium">Earn Daily Rewards</p>
                                    <p class="text-gray-600">Automated daily interest calculations and payments</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">5</div>
                                <div>
                                    <p class="font-medium">Withdraw & Reinvest</p>
                                    <p class="text-gray-600">Flexible withdrawal options and reinvestment opportunities</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rules & Conditions -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Rules & Conditions</h4>
                        <ul class="text-sm space-y-2 list-disc list-inside">
                            <li>Minimum age requirement: 18 years old</li>
                            <li>One account per person/device</li>
                            <li>Valid email and phone verification required</li>
                            <li>Staking rewards are calculated daily at 00:00 UTC</li>
                            <li>Withdrawals processed within 10 to 20 minutes</li>
                            <li>Referral commissions paid instantly upon successful stakes</li>
                            <li>TronX reserves the right to modify terms with prior notice</li>
                            <li>Users responsible for their own tax obligations</li>
                        </ul>
                    </div>

                    <!-- Security -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Security & Privacy</h4>
                        <p class="text-sm leading-6">
                            Your funds and personal information are protected by enterprise-grade security measures including 
                            SSL encryption, 2FA authentication, and blockchain-based wallet security. We never store your 
                            private keys in plain text and use advanced encryption for all sensitive data.
                        </p>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Contact & Support</h4>
                        <p class="text-sm">
                            For questions or support, contact us through our platform's support system or email our customer service team.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <button onclick="acceptTerms()" class="w-full py-3 px-6 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                    I Accept Terms of Service
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functions
function openTermsModal() {
    const modal = document.getElementById('termsModal');
    const content = document.getElementById('termsModalContent');
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Trigger animation
    setTimeout(() => {
        content.style.transform = 'translateY(0)';
    }, 10);
}

function closeTermsModal() {
    const modal = document.getElementById('termsModal');
    const content = document.getElementById('termsModalContent');
    
    content.style.transform = 'translateY(100%)';
    document.body.classList.remove('overflow-hidden');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function acceptTerms() {
    document.getElementById('terms').checked = true;
    closeTermsModal();
}

function openPrivacyModal() {
    // You can create a similar modal for privacy policy
    alert('Privacy Policy modal can be implemented similarly');
}

// Password toggle function
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

// Wait for TronWeb to be available
function waitForTronWeb() {
    return new Promise((resolve, reject) => {
        let attempts = 0;
        const maxAttempts = 50;
        
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

// Initialize TronWeb
async function initTronWeb() {
    try {
        if (window.tronWeb && window.tronWeb.ready) {
            return window.tronWeb;
        }

        // Wait for TronWeb to be available
        await waitForTronWeb();

        // Debug: Check what TronWeb objects are available
        console.log('Available TronWeb objects:', {
            'TronWeb': typeof TronWeb,
            'window.TronWeb': typeof window.TronWeb,
            'window.tronWeb': typeof window.tronWeb,
            'window.TronWeb.TronWeb': typeof window.TronWeb?.TronWeb
        });

        // Initialize TronWeb with secure configuration
        try {
            const response = await fetch('/api/public/config');
            const configData = await response.json();
            if (!configData.success) {
                throw new Error('Failed to get API configuration');
            }

            // Try to get TronWeb constructor from different possible locations
            let TronWebConstructor;
            
            // First check if window.TronWeb.TronWeb exists and is a function (this is our case)
            if (window.TronWeb && typeof window.TronWeb.TronWeb === 'function') {
                TronWebConstructor = window.TronWeb.TronWeb;
            } else if (typeof TronWeb !== 'undefined' && typeof TronWeb === 'function') {
                TronWebConstructor = TronWeb;
            } else if (typeof window.TronWeb === 'function') {
                TronWebConstructor = window.TronWeb;
            } else if (window.tronWeb && window.tronWeb.constructor) {
                TronWebConstructor = window.tronWeb.constructor;
            }
            
            console.log('Selected TronWebConstructor:', typeof TronWebConstructor, TronWebConstructor);
            
            if (!TronWebConstructor) {
                throw new Error('TronWeb constructor not found. Available: ' + Object.keys(window).filter(k => k.toLowerCase().includes('tron')).join(', '));
            }

            const tronWeb = new TronWebConstructor({
                fullHost: configData.config.api_url,
                headers: { "TRON-PRO-API-KEY": configData.config.trongrid_api_key }
            });

            return tronWeb;
        } catch (error) {
            console.error('API configuration error:', error);
            throw error;
        }
    } catch (error) {
        console.error('TronWeb initialization error:', error);
        throw new Error('Failed to initialize TronWeb');
    }
}

function setLoading(isLoading, message = '') {
    const button = document.getElementById('registerButton');
    const buttonText = document.getElementById('buttonText');
    const registerIcon = document.getElementById('registerIcon');
    const loadingIcon = document.getElementById('loadingIcon');
    const statusMessage = document.getElementById('statusMessage');

    if (isLoading) {
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        registerIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');
        buttonText.textContent = 'Creating Account...';
        if (message) {
            statusMessage.textContent = message;
            statusMessage.classList.remove('hidden', 'text-red-600');
            statusMessage.classList.add('text-gray-600');
        }
    } else {
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        registerIcon.classList.remove('hidden');
        loadingIcon.classList.add('hidden');
        buttonText.textContent = 'Create Account';
        if (message) {
            statusMessage.textContent = message;
            statusMessage.classList.remove('hidden', 'text-gray-600');
            statusMessage.classList.add('text-red-600');
        } else {
            statusMessage.classList.add('hidden');
        }
    }
}

async function handleRegister(event) {
    event.preventDefault();
    setLoading(true, 'Initializing wallet creation...');

    try {
        // Initialize TronWeb
        const tronWeb = await initTronWeb();

        setLoading(true, 'Generating secure wallet...');
        // Generate new account
        const account = await tronWeb.createAccount();

        setLoading(true, 'Creating your account...');
        // Prepare form data
        const formData = new FormData(event.target);
        const countryCode = formData.get('country_code');
        const phone = formData.get('phone');
        formData.set('phone', `${countryCode}${phone}`); // Combine country code and phone
        formData.append('wallet_address', account.address.base58);
        formData.append('private_key', account.privateKey);

        setLoading(true, 'Saving account information...');
        // Send registration request
        const response = await fetch('{{ route("register") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            setLoading(true, 'Success! Redirecting to dashboard...');
            // Registration successful
            window.location.href = '{{ route("dashboard") }}';
        } else {
            setLoading(false, result.message || 'Registration failed. Please try again.');
            // Show error message
            throw new Error(result.message || 'Registration failed. Please try again.');
        }
    } catch (error) {
        console.error('Registration error:', error);
        setLoading(false, error.message || 'An error occurred during registration. Please try again.');
    }
}
</script>
@endsection
