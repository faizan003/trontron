<!-- Enhanced Mobile Bottom Navigation -->
<div class="fixed bottom-0 left-0 right-0 z-50 md:hidden">
    <!-- Glassmorphism Background -->
    <div class="bg-white/80 backdrop-blur-xl border-t border-white/20 shadow-2xl shadow-slate-200/50 px-4 py-2 pb-safe">
        <div class="flex justify-around items-center">
            <!-- Home -->
            <a href="{{ route('dashboard') }}"
               class="group flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-primary-500 to-secondary-500 shadow-lg shadow-primary-500/30' : 'hover:bg-white/60' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    @if(request()->routeIs('dashboard'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full shadow-lg animate-pulse"></div>
                    @endif
                </div>
                <span class="text-xs font-medium mt-1 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}">Home</span>
            </a>

            <!-- Plans -->
            <a href="{{ route('dashboard.plans') }}"
               class="group flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard.plans') ? 'bg-gradient-to-br from-primary-500 to-secondary-500 shadow-lg shadow-primary-500/30' : 'hover:bg-white/60' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-all duration-300 {{ request()->routeIs('dashboard.plans') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    @if(request()->routeIs('dashboard.plans'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full shadow-lg animate-pulse"></div>
                    @endif
                </div>
                <span class="text-xs font-medium mt-1 transition-all duration-300 {{ request()->routeIs('dashboard.plans') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}">Plans</span>
            </a>

            <!-- Convert -->
            <a href="{{ route('dashboard.convert') }}"
               class="group flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard.convert') ? 'bg-gradient-to-br from-primary-500 to-secondary-500 shadow-lg shadow-primary-500/30' : 'hover:bg-white/60' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-all duration-300 {{ request()->routeIs('dashboard.convert') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    @if(request()->routeIs('dashboard.convert'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full shadow-lg animate-pulse"></div>
                    @endif
                </div>
                <span class="text-xs font-medium mt-1 transition-all duration-300 {{ request()->routeIs('dashboard.convert') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}">Convert</span>
            </a>

            <!-- Withdraw -->
            <a href="{{ route('dashboard.withdraw') }}"
               class="group flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard.withdraw') ? 'bg-gradient-to-br from-primary-500 to-secondary-500 shadow-lg shadow-primary-500/30' : 'hover:bg-white/60' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-all duration-300 {{ request()->routeIs('dashboard.withdraw') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @if(request()->routeIs('dashboard.withdraw'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full shadow-lg animate-pulse"></div>
                    @endif
                </div>
                <span class="text-xs font-medium mt-1 transition-all duration-300 {{ request()->routeIs('dashboard.withdraw') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}">Withdraw</span>
            </a>

            <!-- Referrals -->
            <a href="{{ route('dashboard.referrals') }}"
               class="group flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard.referrals') ? 'bg-gradient-to-br from-primary-500 to-secondary-500 shadow-lg shadow-primary-500/30' : 'hover:bg-white/60' }}">
                <div class="relative">
                    <svg class="w-6 h-6 transition-all duration-300 {{ request()->routeIs('dashboard.referrals') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if(request()->routeIs('dashboard.referrals'))
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full shadow-lg animate-pulse"></div>
                    @endif
                </div>
                <span class="text-xs font-medium mt-1 transition-all duration-300 {{ request()->routeIs('dashboard.referrals') ? 'text-white' : 'text-neutral-600 group-hover:text-primary-500' }}">Referrals</span>
            </a>
        </div>
    </div>
</div>

<style>
    /* Enhanced Mobile Navigation Styles */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
    }
    
    /* Enhanced Glow Effect for Active States */
    .mobile-nav-active {
        animation: mobileGlow 2s ease-in-out infinite alternate;
    }
    
    @keyframes mobileGlow {
        0% {
            box-shadow: 0 0 10px rgba(14, 165, 233, 0.3);
        }
        100% {
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.6), 0 0 30px rgba(14, 165, 233, 0.4);
        }
    }
    
    /* Enhanced Backdrop Blur Support */
    @supports (backdrop-filter: blur(24px)) {
        .backdrop-blur-xl {
            backdrop-filter: blur(24px);
        }
    }
    
    /* Enhanced Haptic Feedback Simulation */
    @media (hover: none) and (pointer: coarse) {
        .group:active {
            transform: scale(0.95);
        }
    }
</style>

<script>
    // Enhanced Mobile Navigation with Haptic Feedback
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.group');
        
        navItems.forEach(item => {
            // Enhanced touch feedback
            item.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(0.95)';
                
                // Add haptic feedback if available
                if ('vibrate' in navigator) {
                    navigator.vibrate(10);
                }
            });
            
            item.addEventListener('touchend', function(e) {
                this.style.transform = 'scale(1)';
            });
            
            // Enhanced visual feedback
            item.addEventListener('mousedown', function(e) {
                this.style.transform = 'scale(0.95)';
            });
            
            item.addEventListener('mouseup', function(e) {
                this.style.transform = 'scale(1)';
            });
            
            item.addEventListener('mouseleave', function(e) {
                this.style.transform = 'scale(1)';
            });
        });
    });
    
    // Enhanced Profile Menu Functions (keeping backward compatibility)
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        if (menu && menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.querySelector('.absolute')?.classList.add('animate-slide-up');
            menu.addEventListener('click', function(e) {
                if (e.target === this) {
                    toggleProfileMenu();
                }
            });
        } else if (menu) {
            menu.classList.add('hidden');
            menu.querySelector('.absolute')?.classList.remove('animate-slide-up');
        }
    }
</script>
