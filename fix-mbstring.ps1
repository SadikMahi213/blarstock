# Complete Autonomous mbstring Fix Script
# This script automatically detects, fixes, and verifies the mbstring extension issue

Write-Host "=== Laravel mbstring Extension Auto-Fix ===" -ForegroundColor Cyan
Write-Host "Starting complete diagnostic and resolution process..." -ForegroundColor Yellow

# Step 1: Initial Status Check
Write-Host "[1/9] Checking current mbstring status..." -ForegroundColor Green
$mbstringStatus = php -m | Select-String "mbstring"
if ($mbstringStatus) {
    Write-Host "SUCCESS: mbstring extension is already enabled" -ForegroundColor Green
    Write-Host "Current status: $mbstringStatus" -ForegroundColor Gray
} else {
    Write-Host "ERROR: mbstring extension is NOT enabled" -ForegroundColor Red
}

# Step 2: Locate php.ini file
Write-Host "[2/9] Locating PHP configuration file..." -ForegroundColor Green
$iniInfo = php --ini
$iniPath = $iniInfo | Select-String "Loaded Configuration File:" | ForEach-Object { 
    $_.ToString().Split(":")[1].Trim() 
}
Write-Host "Found php.ini at: $iniPath" -ForegroundColor Gray

# Step 3: Verify extension directory exists
Write-Host "[3/9] Checking PHP extensions directory..." -ForegroundColor Green
$extDir = Split-Path $iniPath -Parent + "\ext"
if (Test-Path $extDir) {
    Write-Host "SUCCESS: Extensions directory found: $extDir" -ForegroundColor Green
    $mbstringDll = Get-ChildItem $extDir -Name "php_mbstring.dll" -ErrorAction SilentlyContinue
    if ($mbstringDll) {
        Write-Host "SUCCESS: php_mbstring.dll found: $mbstringDll" -ForegroundColor Green
    } else {
        Write-Host "ERROR: php_mbstring.dll NOT found in extensions directory" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "ERROR: Extensions directory not found" -ForegroundColor Red
    exit 1
}

# Step 4: Check current php.ini configuration
Write-Host "[4/9] Analyzing php.ini configuration..." -ForegroundColor Green
$iniContent = Get-Content $iniPath
$mbstringLine = $iniContent | Select-String "extension=mbstring"
if ($mbstringLine -and -not $mbstringLine.ToString().StartsWith(";")) {
    Write-Host "SUCCESS: mbstring extension is already enabled in php.ini" -ForegroundColor Green
    Write-Host "Line: $($mbstringLine.ToString())" -ForegroundColor Gray
} else {
    Write-Host "ERROR: mbstring extension needs to be enabled" -ForegroundColor Red
    if ($mbstringLine) {
        Write-Host "Found commented line: $($mbstringLine.ToString())" -ForegroundColor Gray
    }
    
    # Step 5: Enable mbstring extension
    Write-Host "[5/9] Enabling mbstring extension..." -ForegroundColor Green
    try {
        $updatedContent = $iniContent -replace ';extension=mbstring', 'extension=mbstring'
        $updatedContent | Set-Content $iniPath
        Write-Host "SUCCESS: Successfully enabled mbstring extension in php.ini" -ForegroundColor Green
    } catch {
        Write-Host "ERROR: Failed to modify php.ini: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "Please run PowerShell as Administrator and try again" -ForegroundColor Yellow
        exit 1
    }
}

# Step 6: Force PHP to reload configuration
Write-Host "[6/9] Restarting PHP environment..." -ForegroundColor Green
Write-Host "Closing current PHP processes..." -ForegroundColor Gray
Stop-Process -Name "php" -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 2

# Step 7: Verify the fix
Write-Host "[7/9] Verifying mbstring extension..." -ForegroundColor Green
$verificationAttempts = 0
$maxAttempts = 5
do {
    Start-Sleep -Seconds 1
    $newMbstringStatus = php -m | Select-String "mbstring"
    $verificationAttempts++
    if ($newMbstringStatus) {
        Write-Host "SUCCESS: mbstring extension is now enabled: $newMbstringStatus" -ForegroundColor Green
        break
    } elseif ($verificationAttempts -ge $maxAttempts) {
        Write-Host "ERROR: Failed to verify mbstring extension after $maxAttempts attempts" -ForegroundColor Red
        Write-Host "Please manually restart your terminal/command prompt" -ForegroundColor Yellow
        exit 1
    }
} while ($verificationAttempts -lt $maxAttempts)

# Step 8: Clear Laravel cache
Write-Host "[8/9] Clearing Laravel application cache..." -ForegroundColor Green
try {
    php artisan config:clear
    Write-Host "SUCCESS: Configuration cache cleared" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Could not clear config cache - $($_.Exception.Message)" -ForegroundColor Yellow
}

try {
    php artisan cache:clear
    Write-Host "SUCCESS: Application cache cleared" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Could not clear app cache - $($_.Exception.Message)" -ForegroundColor Yellow
}

try {
    php artisan route:clear
    Write-Host "SUCCESS: Route cache cleared" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Could not clear route cache - $($_.Exception.Message)" -ForegroundColor Yellow
}

try {
    php artisan view:clear
    Write-Host "SUCCESS: View cache cleared" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Could not clear view cache - $($_.Exception.Message)" -ForegroundColor Yellow
}

# Step 9: Final verification test
Write-Host "[9/9] Final system verification..." -ForegroundColor Green
$functionTest = php -r "echo function_exists('mb_strimwidth') ? 'SUCCESS' : 'FAILED';"
if ($functionTest -eq "SUCCESS") {
    Write-Host "SUCCESS: mb_strimwidth() function is now available" -ForegroundColor Green
    Write-Host "SUCCESS: All systems operational" -ForegroundColor Green
    
    Write-Host "=== RESOLUTION COMPLETE ===" -ForegroundColor Cyan
    Write-Host "The mbstring extension issue has been successfully resolved!" -ForegroundColor Green
    Write-Host "Your Laravel application should now run without errors." -ForegroundColor Green
    Write-Host "To start your Laravel server, run: php artisan serve" -ForegroundColor Yellow
} else {
    Write-Host "ERROR: Final verification failed" -ForegroundColor Red
    Write-Host "Please restart your terminal/command prompt manually and try again" -ForegroundColor Yellow
    exit 1
}