# 🥷 GHOST DEPLOYMENT VERIFICATION SCRIPT
# PowerShell script to verify TronLive is ready for untraceable hosting

Write-Host "🥷 GHOST DEPLOYMENT VERIFICATION - TronLive" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

$ghostScore = 0
$maxScore = 100

# 1. Check logging configuration
Write-Host "📋 Checking Laravel Logging..." -ForegroundColor Yellow
try {
    $configContent = Get-Content "config\logging.php" -Raw
    if ($configContent -match "LOG_CHANNEL.*null" -and $configContent -match "NullHandler") {
        Write-Host "✅ Logging disabled (null handlers)" -ForegroundColor Green
        $ghostScore += 20
    } else {
        Write-Host "❌ Logging still enabled - GHOST VIOLATION" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠️ Could not check logging config" -ForegroundColor Yellow
}

# 2. Check session configuration
Write-Host "📋 Checking Session Storage..." -ForegroundColor Yellow
try {
    $sessionContent = Get-Content "config\session.php" -Raw
    if ($sessionContent -match "SESSION_DRIVER.*file") {
        Write-Host "✅ Session storage: File-based (no IP tracking)" -ForegroundColor Green
        $ghostScore += 15
    } else {
        Write-Host "❌ Session storage: Database (tracks IPs) - GHOST VIOLATION" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠️ Could not check session config" -ForegroundColor Yellow
}

# 3. Check local dependencies
Write-Host "📋 Checking Local Dependencies..." -ForegroundColor Yellow

# Check TronWeb
if (Test-Path "public\js\tronweb-local.js") {
    $tronwebSize = (Get-Item "public\js\tronweb-local.js").Length
    if ($tronwebSize -gt 500000) {
        Write-Host "✅ TronWeb hosted locally" -ForegroundColor Green
        $ghostScore += 15
    } else {
        Write-Host "⚠️ TronWeb file too small - may be placeholder" -ForegroundColor Yellow
        $ghostScore += 5
    }
} else {
    Write-Host "❌ TronWeb not found locally - GHOST VIOLATION" -ForegroundColor Red
}

# Check QRCode
if (Test-Path "public\js\qrcode-local.js") {
    Write-Host "✅ QRCode hosted locally" -ForegroundColor Green
    $ghostScore += 10
} else {
    Write-Host "❌ QRCode not found locally - GHOST VIOLATION" -ForegroundColor Red
}

# 4. Check for external CDN references
Write-Host "📋 Checking for External CDN References..." -ForegroundColor Yellow
$cdnFound = $false

$filesToCheck = @(
    "resources\views\layouts\app.blade.php",
    "resources\views\dashboard\overview.blade.php",
    "resources\views\dashboard\withdraw.blade.php",
    "resources\views\profile\index.blade.php"
)

foreach ($file in $filesToCheck) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        if ($content -match "cdnjs\.cloudflare\.com|cdn\.jsdelivr\.net|googleapis\.com") {
            Write-Host "❌ CDN reference found in $file - GHOST VIOLATION" -ForegroundColor Red
            $cdnFound = $true
        }
    }
}

if (-not $cdnFound) {
    Write-Host "✅ No external CDN references found" -ForegroundColor Green
    $ghostScore += 15
}

# 5. Check security headers
Write-Host "📋 Checking Security Headers..." -ForegroundColor Yellow
try {
    $securityContent = Get-Content "config\security.php" -Raw
    if ($securityContent -notmatch "cloudflare\.com") {
        Write-Host "✅ Security headers cleaned (no Cloudflare refs)" -ForegroundColor Green
        $ghostScore += 10
    } else {
        Write-Host "❌ Cloudflare references in security headers - GHOST VIOLATION" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠️ Could not check security headers" -ForegroundColor Yellow
}

# 6. Check for debug files
Write-Host "📋 Checking for Debug Files..." -ForegroundColor Yellow
$debugFiles = @("public\test.php", "public\phpinfo.php", "public\debug.php")
$debugFound = $false

foreach ($file in $debugFiles) {
    if (Test-Path $file) {
        Write-Host "❌ Debug file found: $file - GHOST VIOLATION" -ForegroundColor Red
        $debugFound = $true
    }
}

if (-not $debugFound) {
    Write-Host "✅ No debug files found" -ForegroundColor Green
    $ghostScore += 10
}

# 7. Check ghost environment file
Write-Host "📋 Checking Ghost Environment..." -ForegroundColor Yellow
if (Test-Path "env.ghost.example") {
    Write-Host "✅ Ghost environment template available" -ForegroundColor Green
    $ghostScore += 5
} else {
    Write-Host "⚠️ Ghost environment template missing" -ForegroundColor Yellow
}

# Calculate final score
Write-Host ""
Write-Host "🎯 GHOST DEPLOYMENT SCORE" -ForegroundColor Cyan
Write-Host "=========================" -ForegroundColor Cyan
Write-Host "Score: $ghostScore / $maxScore" -ForegroundColor White

if ($ghostScore -ge 90) {
    Write-Host "🥷 STATUS: LEGEND - Ready for ghost deployment!" -ForegroundColor Green
    Write-Host "   Your app is 99% untraceable at the application layer." -ForegroundColor Green
} elseif ($ghostScore -ge 75) {
    Write-Host "🕵️ STATUS: SHADOW - Almost ready for ghost deployment" -ForegroundColor Yellow
    Write-Host "   Fix the violations above to reach legend status." -ForegroundColor Yellow
} elseif ($ghostScore -ge 50) {
    Write-Host "👤 STATUS: VISIBLE - Major work needed" -ForegroundColor Red
    Write-Host "   Multiple ghost violations detected." -ForegroundColor Red
} else {
    Write-Host "🔥 STATUS: EXPOSED - Critical violations" -ForegroundColor Red
    Write-Host "   Application is easily traceable." -ForegroundColor Red
}

Write-Host ""
Write-Host "📋 FINAL CHECKLIST FOR DEPLOYMENT:" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan
Write-Host "[ ] Use Tails OS for deployment" -ForegroundColor White
Write-Host "[ ] Connect via Tor or public Wi-Fi only" -ForegroundColor White
Write-Host "[ ] Use anonymous hosting (Njalla, FlokiNET)" -ForegroundColor White
Write-Host "[ ] Pay with non-KYC crypto created in Tails" -ForegroundColor White
Write-Host "[ ] Enable domain privacy protection" -ForegroundColor White
Write-Host "[ ] Configure web server log removal" -ForegroundColor White
Write-Host "[ ] Set up auto-destruct schedule" -ForegroundColor White
Write-Host "[ ] Never access from personal devices" -ForegroundColor White

Write-Host ""
Write-Host "🌫️ 'Walk like a legend. Vanish like vapor.'" -ForegroundColor Magenta
Write-Host "" 