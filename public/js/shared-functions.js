// Shared utility functions for TronLive

// Mobile-first notification system
function showNotification(message, type = 'success') {
    // Remove any existing notifications to prevent stacking
    const existingNotifications = document.querySelectorAll('.tronlive-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    
    // Icon based on type
    const icons = {
        success: `<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>`,
        error: `<svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`,
        warning: `<svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>`,
        info: `<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>`
    };
    
    const icon = icons[type] || icons.info;
    
    // Mobile-first styling with bottom positioning
    notification.className = `tronlive-notification fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-sm z-50 bg-white border border-gray-200 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-y-full opacity-0`;
    
    notification.innerHTML = `
        <div class="p-3 md:p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 leading-tight">${message}</p>
                </div>
                <button onclick="this.closest('.tronlive-notification').remove()" class="flex-shrink-0 ml-2 p-1 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in from bottom
    setTimeout(() => {
        notification.classList.remove('translate-y-full', 'opacity-0');
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 4000);
}

// Copy to clipboard function
async function copyToClipboard(text, successMessage = 'Copied to clipboard!') {
    try {
        await navigator.clipboard.writeText(text);
        showNotification(successMessage, 'success');
        return true;
    } catch (err) {
        console.error('Copy failed:', err);
        showNotification('Failed to copy text', 'error');
        return false;
    }
}

// Confirmation dialog
function showConfirmation(message, onConfirm, onCancel = null) {
    return new Promise((resolve) => {
        const dialog = document.createElement('div');
        dialog.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50';
        
        dialog.innerHTML = `
            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6 transform scale-95 transition-transform duration-200">
                <div class="text-center mb-6">
                    <svg class="w-12 h-12 mx-auto text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-900 font-medium whitespace-pre-line">${message}</p>
                </div>
                <div class="flex space-x-3">
                    <button class="cancel-btn flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200">
                        Cancel
                    </button>
                    <button class="confirm-btn flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(dialog);
        
        // Animate in
        setTimeout(() => dialog.querySelector('.bg-white').classList.remove('scale-95'), 10);
        
        // Event handlers
        dialog.querySelector('.confirm-btn').onclick = () => {
            dialog.remove();
            if (onConfirm) onConfirm();
            resolve(true);
        };
        
        dialog.querySelector('.cancel-btn').onclick = () => {
            dialog.remove();
            if (onCancel) onCancel();
            resolve(false);
        };
        
        // Close on backdrop click
        dialog.onclick = (e) => {
            if (e.target === dialog) {
                dialog.remove();
                if (onCancel) onCancel();
                resolve(false);
            }
        };
    });
}

// Loading state management
function setLoading(element, loading = true, originalText = null) {
    if (loading) {
        element.disabled = true;
        element.dataset.originalText = element.innerHTML;
        element.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading...
        `;
    } else {
        element.disabled = false;
        element.innerHTML = originalText || element.dataset.originalText || 'Submit';
    }
}

// Format numbers consistently
function formatNumber(number, decimals = 6) {
    return parseFloat(number).toFixed(decimals);
}

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Theme management
function initTheme() {
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    return theme;
}

// Export functions for modules
window.TronLiveUtils = {
    showNotification,
    copyToClipboard,
    showConfirmation,
    setLoading,
    formatNumber,
    debounce,
    initTheme
}; 