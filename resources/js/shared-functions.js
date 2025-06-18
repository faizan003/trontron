// Shared utility functions for TronLive

// Notification system
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container') || document.body;
    const notification = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg ${bgColor} text-white shadow-lg transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
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