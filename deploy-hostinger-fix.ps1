# Hostinger Configuration Fix Script (PowerShell)
# This script addresses common issues when deploying to Hostinger

Write-Host "🚀 Starting Hostinger configuration fix..." -ForegroundColor Green

# Clear all cached configurations
Write-Host "📝 Clearing Laravel caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
Write-Host "⚡ Optimizing for production..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check if .env file exists and has required variables
Write-Host "🔍 Checking environment configuration..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "✅ .env file found" -ForegroundColor Green
    $envContent = Get-Content ".env" -Raw
    if ($envContent -match "TRONGRID_API_KEY=") {
        Write-Host "✅ TRONGRID_API_KEY found in .env" -ForegroundColor Green
    } else {
        Write-Host "❌ TRONGRID_API_KEY not found in .env file" -ForegroundColor Red
        Write-Host "Please add TRONGRID_API_KEY=your_api_key_here to your .env file" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ .env file not found" -ForegroundColor Red
    Write-Host "Please create .env file from .env.example" -ForegroundColor Yellow
}

Write-Host "✅ Hostinger configuration fix completed!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "1. Make sure your .env file has TRONGRID_API_KEY set" -ForegroundColor White
Write-Host "2. Test the registration page" -ForegroundColor White
Write-Host "3. Check browser console for any remaining errors" -ForegroundColor White
Write-Host "4. If issues persist, check /debug/env route for environment debugging" -ForegroundColor White 