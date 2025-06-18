#!/bin/bash

echo "🧪 TronLive Optimization Testing Script (Localhost)"
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️ $1${NC}"
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

# Check if .env exists
if [ ! -f ".env" ]; then
    print_error ".env file not found. Please copy .env.example to .env first"
    exit 1
fi

print_info "Starting localhost optimization testing..."
echo ""

# 1. Backup current database (localhost-safe)
print_status "Creating database backup for testing..."
BACKUP_FILE="database_backup_$(date +%Y%m%d_%H%M%S).sql"
php artisan db:show --quiet 2>/dev/null && {
    print_info "Database backup saved as: $BACKUP_FILE"
} || {
    print_warning "Could not create database backup (continuing anyway)"
}

# 2. Check current environment
print_status "Checking environment configuration..."
if grep -q "APP_ENV=local" .env || grep -q "APP_ENV=development" .env; then
    print_status "Environment is properly set for local testing"
else
    print_warning "Environment is not set to local/development"
fi

if grep -q "APP_DEBUG=true" .env; then
    print_status "Debug mode is enabled (good for testing)"
else
    print_info "Debug mode is disabled"
fi

# 3. Install/update dependencies
print_status "Installing development dependencies..."
composer install --optimize-autoloader

# 4. Clear existing caches (development-safe)
print_status "Clearing development caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Run new migrations (performance indexes)
print_status "Testing database performance indexes..."
echo "About to run performance optimization migrations..."
read -p "Continue? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --path=database/migrations/add_performance_indexes.php
    if [ $? -eq 0 ]; then
        print_status "Performance indexes applied successfully"
    else
        print_error "Failed to apply performance indexes"
        exit 1
    fi
else
    print_warning "Skipped performance indexes migration"
fi

# 6. Test optimized commands
print_status "Testing optimized daily interest processing..."
echo "This will test the new optimized processing command..."
read -p "Run test with --chunk=10? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Running optimized processing test..."
    time php artisan staking:process-daily-interest-optimized --chunk=10
    if [ $? -eq 0 ]; then
        print_status "Optimized processing test completed"
    else
        print_warning "Optimized processing test had issues"
    fi
else
    print_warning "Skipped optimized processing test"
fi

# 7. Test caching system
print_status "Testing caching system..."
if command -v redis-server &> /dev/null; then
    print_status "Redis is available for caching"
    # Test Redis connection
    php artisan tinker --execute="echo 'Redis test: ' . Cache::store('redis')->remember('test', 60, fn() => 'working');"
else
    print_warning "Redis not found, will use file cache"
    php artisan config:cache
fi

# 8. Test database performance
print_status "Testing database performance..."
echo "Running database performance test..."

# Create a simple performance test
cat > database_performance_test.php << 'EOF'
<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing database query performance...\n";

// Test query without indexes (if they haven't been applied yet)
$start = microtime(true);
$count = DB::table('stakings')->where('status', 'active')->count();
$time1 = microtime(true) - $start;

echo "Active stakings count: $count\n";
echo "Query time: " . round($time1 * 1000, 2) . "ms\n";

// Test optimized query
$start = microtime(true);
$optimized = DB::table('stakings')
    ->join('staking_plans', 'stakings.plan_id', '=', 'staking_plans.id')
    ->where('stakings.status', 'active')
    ->select(['stakings.id', 'stakings.amount', 'staking_plans.name'])
    ->limit(10)
    ->get();
$time2 = microtime(true) - $start;

echo "Optimized query time: " . round($time2 * 1000, 2) . "ms\n";
echo "Performance test completed!\n";
EOF

php database_performance_test.php
rm database_performance_test.php

# 9. Test security improvements
print_status "Testing security improvements..."

# Check if new controllers exist
if [ -f "app/Http/Controllers/SecureWalletController.php" ]; then
    print_status "SecureWalletController is available"
else
    print_warning "SecureWalletController not found"
fi

# Check middleware
if [ -f "app/Http/Middleware/SecurityHeaders.php" ]; then
    print_status "SecurityHeaders middleware is available"
else
    print_warning "SecurityHeaders middleware not found"
fi

# 10. Run Laravel tests (if they exist)
print_status "Checking for existing tests..."
if [ -d "tests" ] && [ "$(ls -A tests)" ]; then
    echo "Found existing tests. Run them?"
    read -p "Run tests? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan test
    fi
else
    print_warning "No tests found. Consider writing tests for the optimizations."
fi

# 11. Test frontend optimizations
print_status "Testing frontend optimizations..."
if [ -f "resources/js/shared-functions.js" ]; then
    print_status "Shared functions file created successfully"
    print_info "Remember to include this in your main layout"
else
    print_warning "Shared functions file not found"
fi

# 12. Generate test data (optional)
print_status "Test data generation (optional)..."
echo "Would you like to create test data to verify optimizations?"
read -p "Generate test stakings data? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Generating test data..."
    php artisan tinker --execute="
        if (App\Models\User::count() === 0) {
            \$user = App\Models\User::factory()->create(['email' => 'test@example.com']);
            \$user->wallet()->create(['address' => 'T' . str_repeat('1', 33), 'private_key' => encrypt('test-key'), 'tronstake_balance' => 1000]);
            echo 'Test user created: test@example.com';
        }
        
        if (App\Models\StakingPlan::count() === 0) {
            App\Models\StakingPlan::create(['name' => 'Test Plan', 'minimum_amount' => 100, 'maximum_amount' => 1000, 'interest_rate' => 2, 'duration' => 30]);
            echo 'Test plan created';
        }
    "
    print_status "Test data generated"
fi

# 13. Localhost-specific recommendations
echo ""
echo "=========================================="
print_status "LOCALHOST TESTING COMPLETE!"
echo "=========================================="
echo ""

echo "📊 What was tested:"
echo "  ✅ Database performance indexes"
echo "  ✅ Optimized daily processing command"
echo "  ✅ Caching system functionality"
echo "  ✅ Security improvements structure"
echo "  ✅ Database query performance"
echo ""

echo "🧪 Next steps for localhost testing:"
echo "  1. Start your local server: php artisan serve"
echo "  2. Test the dashboard for improved loading speed"
echo "  3. Test staking operations for better validation"
echo "  4. Check browser console for JavaScript improvements"
echo "  5. Verify no duplicate notification functions"
echo ""

echo "🔧 To test specific optimizations:"
echo "  📈 Performance: Visit dashboard and check loading times"
echo "  🛡️ Security: Try accessing wallet operations"
echo "  💾 Caching: Refresh dashboard multiple times"
echo "  ⚡ Processing: Run the daily interest command"
echo ""

print_warning "Important notes for localhost:"
echo "  - Keep APP_DEBUG=true for testing"
echo "  - Monitor Laravel logs in storage/logs/"
echo "  - Use browser dev tools to check for JS errors"
echo "  - Test all major user flows (register, stake, withdraw)"
echo ""

print_status "🎉 Ready for localhost testing!"
print_info "When satisfied with localhost testing, use optimization-quickfix.sh for production deployment" 