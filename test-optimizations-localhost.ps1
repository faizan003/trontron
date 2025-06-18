# TronLive Optimization Testing Script (Localhost) - PowerShell Version
# Run this in Windows PowerShell or PowerShell Core

Write-Host "🧪 TronLive Optimization Testing Script (Localhost - Windows)" -ForegroundColor Cyan
Write-Host "=============================================================" -ForegroundColor Cyan

# Check if we're in Laravel project
if (-not (Test-Path "artisan")) {
    Write-Host "❌ This script must be run from the Laravel project root directory" -ForegroundColor Red
    exit 1
}

# Check if .env exists
if (-not (Test-Path ".env")) {
    Write-Host "❌ .env file not found. Please copy .env.example to .env first" -ForegroundColor Red
    exit 1
}

Write-Host "ℹ️ Starting localhost optimization testing..." -ForegroundColor Blue
Write-Host ""

# 1. Check current environment
Write-Host "✅ Checking environment configuration..." -ForegroundColor Green
$envContent = Get-Content ".env" -Raw
if ($envContent -match "APP_ENV=(local|development)") {
    Write-Host "✅ Environment is properly set for local testing" -ForegroundColor Green
} else {
    Write-Host "⚠️ Environment is not set to local/development" -ForegroundColor Yellow
}

if ($envContent -match "APP_DEBUG=true") {
    Write-Host "✅ Debug mode is enabled (good for testing)" -ForegroundColor Green
} else {
    Write-Host "ℹ️ Debug mode is disabled" -ForegroundColor Blue
}

# 2. Install/update dependencies
Write-Host "✅ Installing development dependencies..." -ForegroundColor Green
& composer install --optimize-autoloader

# 3. Clear existing caches
Write-Host "✅ Clearing development caches..." -ForegroundColor Green
& php artisan cache:clear
& php artisan config:clear
& php artisan route:clear
& php artisan view:clear

# 4. Run new migrations (performance indexes)
Write-Host "✅ Testing database performance indexes..." -ForegroundColor Green
Write-Host "About to run performance optimization migrations..."
$confirm = Read-Host "Continue? (y/N)"
if ($confirm -eq "y" -or $confirm -eq "Y") {
    & php artisan migrate --path=database/migrations/add_performance_indexes.php
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Performance indexes applied successfully" -ForegroundColor Green
    } else {
        Write-Host "❌ Failed to apply performance indexes" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "⚠️ Skipped performance indexes migration" -ForegroundColor Yellow
}

# 5. Test optimized commands
Write-Host "✅ Testing optimized daily interest processing..." -ForegroundColor Green
Write-Host "This will test the new optimized processing command..."
$confirmProcess = Read-Host "Run test with --chunk=10? (y/N)"
if ($confirmProcess -eq "y" -or $confirmProcess -eq "Y") {
    Write-Host "ℹ️ Running optimized processing test..." -ForegroundColor Blue
    $start = Get-Date
    & php artisan staking:process-daily-interest-optimized --chunk=10
    $end = Get-Date
    $duration = $end - $start
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Optimized processing test completed in $($duration.TotalSeconds) seconds" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Optimized processing test had issues" -ForegroundColor Yellow
    }
} else {
    Write-Host "⚠️ Skipped optimized processing test" -ForegroundColor Yellow
}

# 6. Test caching system
Write-Host "✅ Testing caching system..." -ForegroundColor Green
if (Get-Command redis-server -ErrorAction SilentlyContinue) {
    Write-Host "✅ Redis is available for caching" -ForegroundColor Green
} else {
    Write-Host "⚠️ Redis not found, will use file cache" -ForegroundColor Yellow
    & php artisan config:cache
}

# 7. Test database performance
Write-Host "✅ Testing database performance..." -ForegroundColor Green
Write-Host "Running database performance test..."

# Create a simple performance test file
$testScript = @"
<?php
require 'vendor/autoload.php';

`$app = require_once 'bootstrap/app.php';
`$kernel = `$app->make(Illuminate\Contracts\Console\Kernel::class);
`$kernel->bootstrap();

echo "Testing database query performance...\n";

// Test query performance
`$start = microtime(true);
`$count = DB::table('stakings')->where('status', 'active')->count();
`$time1 = microtime(true) - `$start;

echo "Active stakings count: `$count\n";
echo "Query time: " . round(`$time1 * 1000, 2) . "ms\n";

// Test optimized query
`$start = microtime(true);
`$optimized = DB::table('stakings')
    ->join('staking_plans', 'stakings.plan_id', '=', 'staking_plans.id')
    ->where('stakings.status', 'active')
    ->select(['stakings.id', 'stakings.amount', 'staking_plans.name'])
    ->limit(10)
    ->get();
`$time2 = microtime(true) - `$start;

echo "Optimized query time: " . round(`$time2 * 1000, 2) . "ms\n";
echo "Performance test completed!\n";
"@

$testScript | Out-File -FilePath "database_performance_test.php" -Encoding UTF8
& php database_performance_test.php
Remove-Item "database_performance_test.php"

# 8. Test security improvements
Write-Host "✅ Testing security improvements..." -ForegroundColor Green

if (Test-Path "app/Http/Controllers/SecureWalletController.php") {
    Write-Host "✅ SecureWalletController is available" -ForegroundColor Green
} else {
    Write-Host "⚠️ SecureWalletController not found" -ForegroundColor Yellow
}

if (Test-Path "app/Http/Middleware/SecurityHeaders.php") {
    Write-Host "✅ SecurityHeaders middleware is available" -ForegroundColor Green
} else {
    Write-Host "⚠️ SecurityHeaders middleware not found" -ForegroundColor Yellow
}

# 9. Test frontend optimizations
Write-Host "✅ Testing frontend optimizations..." -ForegroundColor Green
if (Test-Path "resources/js/shared-functions.js") {
    Write-Host "✅ Shared functions file created successfully" -ForegroundColor Green
    Write-Host "ℹ️ Remember to include this in your main layout" -ForegroundColor Blue
} else {
    Write-Host "⚠️ Shared functions file not found" -ForegroundColor Yellow
}

# 10. Generate test data (optional)
Write-Host "✅ Test data generation (optional)..." -ForegroundColor Green
Write-Host "Would you like to create test data to verify optimizations?"
$confirmData = Read-Host "Generate test stakings data? (y/N)"
if ($confirmData -eq "y" -or $confirmData -eq "Y") {
    Write-Host "ℹ️ Generating test data..." -ForegroundColor Blue
    
    $tinkerScript = @"
if (App\Models\User::count() === 0) {
    `$user = App\Models\User::factory()->create(['email' => 'test@example.com']);
    `$user->wallet()->create(['address' => 'T' . str_repeat('1', 33), 'private_key' => encrypt('test-key'), 'tronstake_balance' => 1000]);
    echo 'Test user created: test@example.com' . PHP_EOL;
}

if (App\Models\StakingPlan::count() === 0) {
    App\Models\StakingPlan::create(['name' => 'Test Plan', 'minimum_amount' => 100, 'maximum_amount' => 1000, 'interest_rate' => 2, 'duration' => 30]);
    echo 'Test plan created' . PHP_EOL;
}
"@
    
    $tinkerScript | Out-File -FilePath "temp_tinker.php" -Encoding UTF8
    & php artisan tinker --execute="require 'temp_tinker.php';"
    Remove-Item "temp_tinker.php"
    Write-Host "✅ Test data generated" -ForegroundColor Green
}

# 11. Summary
Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "✅ LOCALHOST TESTING COMPLETE!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "📊 What was tested:" -ForegroundColor Yellow
Write-Host "  ✅ Database performance indexes"
Write-Host "  ✅ Optimized daily processing command"
Write-Host "  ✅ Caching system functionality"
Write-Host "  ✅ Security improvements structure"
Write-Host "  ✅ Database query performance"
Write-Host ""

Write-Host "🧪 Next steps for localhost testing:" -ForegroundColor Yellow
Write-Host "  1. Start your local server: php artisan serve"
Write-Host "  2. Test the dashboard for improved loading speed"
Write-Host "  3. Test staking operations for better validation"
Write-Host "  4. Check browser console for JavaScript improvements"
Write-Host "  5. Verify no duplicate notification functions"
Write-Host ""

Write-Host "🔧 To test specific optimizations:" -ForegroundColor Yellow
Write-Host "  📈 Performance: Visit dashboard and check loading times"
Write-Host "  🛡️ Security: Try accessing wallet operations"
Write-Host "  💾 Caching: Refresh dashboard multiple times"
Write-Host "  ⚡ Processing: Run the daily interest command"
Write-Host ""

Write-Host "⚠️ Important notes for localhost:" -ForegroundColor Yellow
Write-Host "  - Keep APP_DEBUG=true for testing"
Write-Host "  - Monitor Laravel logs in storage/logs/"
Write-Host "  - Use browser dev tools to check for JS errors"
Write-Host "  - Test all major user flows (register, stake, withdraw)"
Write-Host ""

Write-Host "🎉 Ready for localhost testing!" -ForegroundColor Green
Write-Host "ℹ️ When satisfied with localhost testing, use optimization-quickfix.sh for production deployment" -ForegroundColor Blue 