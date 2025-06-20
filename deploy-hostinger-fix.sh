#!/bin/bash

# Hostinger Configuration Fix Script
# This script addresses common issues when deploying to Hostinger

echo "🚀 Starting Hostinger configuration fix..."

# Clear all cached configurations
echo "📝 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check if .env file exists and has required variables
echo "🔍 Checking environment configuration..."
if [ -f .env ]; then
    echo "✅ .env file found"
    if grep -q "TRONGRID_API_KEY=" .env; then
        echo "✅ TRONGRID_API_KEY found in .env"
    else
        echo "❌ TRONGRID_API_KEY not found in .env file"
        echo "Please add TRONGRID_API_KEY=your_api_key_here to your .env file"
    fi
else
    echo "❌ .env file not found"
    echo "Please create .env file from .env.example"
fi

# Set proper permissions for Hostinger
echo "🔐 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env 2>/dev/null || echo "⚠️  .env file not found or already has correct permissions"

# Test API routes
echo "🧪 Testing API routes..."
echo "Testing /api/public/test route..."
curl -s -o /dev/null -w "%{http_code}" "$(php artisan route:list --name=api.public.test --columns=uri | tail -n 1 | tr -d ' ')" || echo "Route test skipped (curl not available)"

echo "✅ Hostinger configuration fix completed!"
echo ""
echo "📋 Next steps:"
echo "1. Make sure your .env file has TRONGRID_API_KEY set"
echo "2. Test the registration page"
echo "3. Check browser console for any remaining errors"
echo "4. If issues persist, check /debug/env route for environment debugging" 