#!/usr/bin/env pwsh
# Navigate to the project directory
Set-Location "C:\Users\faiza\Desktop\f\tronlive"

# Run the staking interest processing command
php artisan staking:process-daily-interest --force 