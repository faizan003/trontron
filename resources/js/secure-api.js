/**
 * Secure API Configuration Helper
 * Fetches API configuration securely from backend
 */

let apiConfig = null;
let configPromise = null;

/**
 * Get API configuration securely from backend
 */
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
    })
    .catch(error => {
        console.error('API Config Error:', error);
        throw error;
    });

    return await configPromise;
}

/**
 * Initialize TronWeb with secure configuration
 */
async function initSecureTronWeb(privateKey = null) {
    try {
        const config = await getApiConfig();
        
        const tronWebConfig = {
            fullHost: config.api_url,
            headers: { "TRON-PRO-API-KEY": config.trongrid_api_key }
        };

        if (privateKey) {
            tronWebConfig.privateKey = privateKey;
        }

        return new TronWeb(tronWebConfig);
    } catch (error) {
        console.error('TronWeb initialization error:', error);
        throw new Error('Failed to initialize TronWeb: ' + error.message);
    }
}

/**
 * Clear cached configuration (useful for logout)
 */
function clearApiConfig() {
    apiConfig = null;
    configPromise = null;
}

// Export functions for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getApiConfig,
        initSecureTronWeb,
        clearApiConfig
    };
} else {
    // Browser globals
    window.getApiConfig = getApiConfig;
    window.initSecureTronWeb = initSecureTronWeb;
    window.clearApiConfig = clearApiConfig;
} 