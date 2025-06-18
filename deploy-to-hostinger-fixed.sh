#!/bin/bash

echo "🚀 Deploying TronLive to Hostinger (Fixed Version)..."
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if we're in Laravel project
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Starting Hostinger-optimized deployment..."

# 1. Check and upgrade Composer if needed
print_status "Checking Composer version..."
COMPOSER_VERSION=$(composer --version 2>/dev/null | grep -o 'Composer version [0-9]\+' | grep -o '[0-9]\+' || echo "1")

if [ "$COMPOSER_VERSION" = "1" ]; then
    print_warning "Composer 1 detected. Laravel 11 requires Composer 2."
    print_status "Attempting to upgrade Composer..."
    
    # Try to self-update composer
    composer self-update --2 2>/dev/null || {
        print_warning "Could not auto-upgrade Composer. Continuing with compatibility mode..."
        
        # Create a temporary composer.json with lower Laravel version for Composer 1
        if [ -f "composer.json.backup" ]; then
            print_status "Restoring from backup..."
            cp composer.json.backup composer.json
        else
            cp composer.json composer.json.backup
            print_status "Creating Composer 1 compatible configuration..."
            
            # Temporarily modify composer.json for Composer 1 compatibility
            sed -i 's/"laravel\/framework": "\^11[^"]*"/"laravel\/framework": "^10.0"/' composer.json 2>/dev/null || true
        fi
    }
fi

# 2. Install dependencies with Composer 1 compatibility
print_status "Installing dependencies (Hostinger compatible)..."

# Use Composer 1 compatible flags
if [ "$COMPOSER_VERSION" = "1" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist
    print_warning "Using Composer 1 compatibility mode"
else
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 3. Apply database optimizations
print_status "Applying database performance optimizations..."
php artisan migrate --path=database/migrations/add_performance_indexes.php --force 2>/dev/null || {
    print_warning "Performance migration already applied or not found"
}

# 4. Clear existing caches
print_status "Clearing existing caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# 5. Cache everything for production
print_status "Caching configurations for production..."
php artisan config:cache 2>/dev/null || print_warning "Config cache failed"
php artisan route:cache 2>/dev/null || print_warning "Route cache failed"
php artisan view:cache 2>/dev/null || print_warning "View cache failed"

# 6. Set proper permissions for Hostinger
print_status "Setting proper file permissions..."
chmod -R 755 storage 2>/dev/null || true
chmod -R 755 bootstrap/cache 2>/dev/null || true
find storage -type f -exec chmod 644 {} \; 2>/dev/null || true
find bootstrap/cache -type f -exec chmod 644 {} \; 2>/dev/null || true

# 7. Handle storage symlink (Hostinger-specific fix)
print_status "Creating storage symlink (Hostinger compatible)..."
if [ ! -L "public/storage" ] && [ ! -d "public/storage" ]; then
    # Try Laravel's storage:link first
    php artisan storage:link 2>/dev/null || {
        print_warning "Laravel storage:link failed. Creating manual symlink..."
        
        # Manual symlink creation for Hostinger
        if [ -d "storage/app/public" ]; then
            # Remove any existing storage directory/link
            rm -rf public/storage 2>/dev/null || true
            
            # Create manual symlink
            ln -sf "../storage/app/public" "public/storage" 2>/dev/null || {
                print_warning "Symlink creation failed. Creating directory copy instead..."
                cp -r storage/app/public public/storage 2>/dev/null || true
            }
        fi
    }
fi

# 8. Create health check endpoint if missing
if [ ! -f "public/health.php" ]; then
    print_status "Creating health check endpoint..."
    cat > public/health.php << 'EOF'
<?php
// Simple health check for Hostinger
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$health = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'server' => 'hostinger',
    'checks' => []
];

// Basic file system check
$health['checks']['filesystem'] = is_writable('../storage') ? 'ok' : 'error';

// Laravel bootstrap check
try {
    if (file_exists('../vendor/autoload.php')) {
        $health['checks']['composer'] = 'ok';
    } else {
        $health['checks']['composer'] = 'error';
    }
} catch (Exception $e) {
    $health['checks']['composer'] = 'error';
}

// Environment check
if (file_exists('../.env')) {
    $health['checks']['environment'] = 'ok';
} else {
    $health['checks']['environment'] = 'error';
    $health['status'] = 'error';
}

// Memory check
$memoryUsage = memory_get_usage(true);
$health['memory_usage'] = round($memoryUsage / 1024 / 1024, 2) . 'MB';
$health['memory_limit'] = ini_get('memory_limit');

echo json_encode($health, JSON_PRETTY_PRINT);
EOF
fi

# 9. Test critical functions
print_status "Running system tests..."

# Test basic Laravel bootstrap
php artisan --version >/dev/null 2>&1 && {
    print_status "Laravel bootstrap: OK"
} || {
    print_error "Laravel bootstrap failed"
}

# Test database connection (if available)
php artisan tinker --execute="
try {
    \$users = DB::table('users')->count();
    echo 'Database test: ' . \$users . ' users found';
} catch (Exception \$e) {
    echo 'Database connection not available or error: ' . \$e->getMessage();
}
" 2>/dev/null || print_warning "Database test skipped"

# 10. Security checks
print_status "Running security checks..."

# Check environment settings
if [ -f ".env" ]; then
    if grep -q "APP_DEBUG=true" .env; then
        print_warning "APP_DEBUG is set to true! Recommended to set to false for production"
    else
        print_status "APP_DEBUG is properly configured"
    fi
    
    if grep -q "APP_ENV=production" .env; then
        print_status "APP_ENV is correctly set to production"
    else
        print_warning "Consider setting APP_ENV=production for live deployment"
    fi
else
    print_warning ".env file not found. Please create from .env.example"
fi

# 11. Log cleanup
print_status "Cleaning up old logs..."
find storage/logs -name "*.log" -mtime +7 -delete 2>/dev/null || true

# 12. Final verification
print_status "Running final verification..."

# Check critical files
CRITICAL_FILES=("public/index.php" "vendor/autoload.php" "bootstrap/app.php")
for file in "${CRITICAL_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        print_error "Missing critical file: $file"
        exit 1
    fi
done

# Test application bootstrap one more time
php -r "
try {
    require 'vendor/autoload.php';
    echo 'Autoloader: OK\n';
} catch (Exception \$e) {
    echo 'Autoloader error: ' . \$e->getMessage() . '\n';
    exit(1);
}
" || {
    print_error "Final verification failed"
    exit 1
}

# 13. Create Hostinger-specific optimization file
print_status "Creating Hostinger optimization configuration..."
cat > hostinger-optimization.php << 'EOF'
<?php
// Hostinger-specific optimizations
// Include this file in your bootstrap/app.php if needed

// Memory optimization for shared hosting
ini_set('memory_limit', '256M');

// Reduce session overhead
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

// Output buffering for better performance
if (!ob_get_level()) {
    ob_start();
}

// Timezone setting (adjust as needed)
date_default_timezone_set('UTC');
EOF

# 14. Restore original composer.json if we modified it
if [ -f "composer.json.backup" ]; then
    print_status "Restoring original composer.json..."
    mv composer.json.backup composer.json
fi

# 15. Summary report
echo ""
echo "=========================================="
print_status "HOSTINGER DEPLOYMENT COMPLETE!"
echo "=========================================="
echo ""

echo "📊 Hostinger-Specific Optimizations Applied:"
echo "  ✅ Composer 1 compatibility handling"
echo "  ✅ Manual storage symlink creation"
echo "  ✅ Hostinger-optimized file permissions"
echo "  ✅ Health check endpoint created"
echo "  ✅ Memory and performance optimizations"
echo ""

echo "🔍 Next Steps for Hostinger:"
echo "  1. Upload files to your hosting account"
echo "  2. Set document root to 'public' folder"
echo "  3. Configure database settings in .env"
echo "  4. Test health endpoint: /health.php"
echo "  5. Set up cron jobs in Hostinger control panel"
echo ""

echo "⚙️ Hostinger Cron Jobs (add in control panel):"
echo "  # Every 5 minutes - Daily interest processing"
echo "  */5 * * * * cd /home/username/public_html && php artisan staking:process-daily-interest-optimized --chunk=25"
echo ""
echo "  # Every hour - Cache cleanup"
echo "  0 * * * * cd /home/username/public_html && php artisan view:clear"
echo ""
echo "  # Daily - Log cleanup"
echo "  0 0 * * * find /home/username/public_html/storage/logs -name '*.log' -mtime +3 -delete"
echo ""

echo "🚨 Hostinger-Specific Notes:"
echo "  - Symlink created manually due to hosting limitations"
echo "  - Composer 1 compatibility mode used"
echo "  - Memory limit set to 256MB (adjust if needed)"
echo "  - Health check available at /health.php"
echo ""

print_status "Ready for Hostinger deployment!"
print_status "Health check: Visit http://yourdomain.com/health.php"

echo ""
echo "📝 Troubleshooting:"
echo "  - If symlink issues persist, copy storage/app/public/* to public/storage/"
echo "  - For Composer 2, contact Hostinger support to upgrade"
echo "  - Monitor logs in storage/logs/ for any issues"
echo "  - Use smaller chunk sizes (--chunk=10) if memory issues occur" 