# PowerShell script to enable mbstring extension in PHP
# This script will uncomment the mbstring extension in php.ini

$phpIniPath = "C:\laragon\bin\php\php-8.3.10-Win32-vs16-x64\php.ini"

Write-Host "Enabling mbstring extension in PHP..." -ForegroundColor Green

# Read the php.ini file
$content = Get-Content $phpIniPath

# Replace the commented mbstring line with uncommented version
$updatedContent = $content -replace ';extension=mbstring', 'extension=mbstring'

# Write the updated content back to the file
$updatedContent | Set-Content $phpIniPath

Write-Host "mbstring extension has been enabled!" -ForegroundColor Green
Write-Host "Please restart your PHP server for changes to take effect." -ForegroundColor Yellow