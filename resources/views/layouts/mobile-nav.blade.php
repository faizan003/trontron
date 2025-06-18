<!-- Mobile Bottom Navigation -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 pb-safe md:hidden">
    <div class="grid grid-cols-5 gap-1">
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs mt-1">Home</span>
        </a>

        <a href="{{ route('dashboard.plans') }}"
           class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard.plans') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-xs mt-1">Plans</span>
        </a>

        <a href="{{ route('dashboard.convert') }}"
           class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard.convert') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            <span class="text-xs mt-1">Convert</span>
        </a>

        <a href="{{ route('dashboard.withdraw') }}"
           class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard.withdraw') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span class="text-xs mt-1">Withdraw</span>
        </a>

        <a href="{{ route('dashboard.referrals') }}"
           class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard.referrals') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-xs mt-1">Referrals</span>
        </a>
    </div>
</div>

<script>
function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.querySelector('.absolute').classList.add('animate-slide-up');
        menu.addEventListener('click', function(e) {
            if (e.target === this) {
                toggleProfileMenu();
            }
        });
    } else {
        menu.classList.add('hidden');
        menu.querySelector('.absolute').classList.remove('animate-slide-up');
    }
}
</script>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.3s ease-out forwards;
}
</style>
