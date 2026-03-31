# SAV Mikem - Script d'installation
# Exécutez: powershell -ExecutionPolicy Bypass -File setup.ps1

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "   SAV Mikem - Installation Setup" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

$projectDir = $PSScriptRoot

# Step 1: Backup custom files
Write-Host "[1/5] Sauvegarde des fichiers personnalisés..." -ForegroundColor Yellow
$backupDir = Join-Path $projectDir "_custom_backup"
if (Test-Path $backupDir) { Remove-Item $backupDir -Recurse -Force }
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

$filesToBackup = @(
    "app", "database", "resources", "routes", "bootstrap", "public\css", "INSTALL.md"
)

foreach ($item in $filesToBackup) {
    $source = Join-Path $projectDir $item
    if (Test-Path $source) {
        $dest = Join-Path $backupDir $item
        if ((Get-Item $source).PSIsContainer) {
            Copy-Item $source $dest -Recurse -Force
        } else {
            $destDir = Split-Path $dest -Parent
            if (!(Test-Path $destDir)) { New-Item -ItemType Directory -Force -Path $destDir | Out-Null }
            Copy-Item $source $dest -Force
        }
    }
}

# Step 2: Create Laravel project
Write-Host "[2/5] Création du projet Laravel..." -ForegroundColor Yellow
$tempDir = Join-Path $projectDir "_laravel_temp"
if (Test-Path $tempDir) { Remove-Item $tempDir -Recurse -Force }

Set-Location $projectDir
composer create-project laravel/laravel $tempDir --prefer-dist --no-interaction

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERREUR: L'installation de Laravel a échoué!" -ForegroundColor Red
    exit 1
}

# Step 3: Copy Laravel files to project directory
Write-Host "[3/5] Copie des fichiers Laravel..." -ForegroundColor Yellow
Get-ChildItem $tempDir | ForEach-Object {
    $dest = Join-Path $projectDir $_.Name
    if ($_.PSIsContainer) {
        if (Test-Path $dest) { Remove-Item $dest -Recurse -Force }
        Copy-Item $_.FullName $dest -Recurse -Force
    } else {
        Copy-Item $_.FullName $dest -Force
    }
}

# Step 4: Restore custom files (overwriting Laravel defaults)
Write-Host "[4/5] Restauration des fichiers personnalisés..." -ForegroundColor Yellow
Get-ChildItem $backupDir | ForEach-Object {
    $dest = Join-Path $projectDir $_.Name
    if ($_.PSIsContainer) {
        Copy-Item $_.FullName $dest -Recurse -Force
    } else {
        Copy-Item $_.FullName $dest -Force
    }
}

# Step 5: Cleanup
Write-Host "[5/5] Nettoyage..." -ForegroundColor Yellow
Remove-Item $tempDir -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item $backupDir -Recurse -Force -ErrorAction SilentlyContinue

# Configure .env
Write-Host ""
Write-Host "Configuration du fichier .env..." -ForegroundColor Yellow
$envFile = Join-Path $projectDir ".env"
$envExampleFile = Join-Path $projectDir ".env.example"

if (Test-Path $envExampleFile) {
    Copy-Item $envExampleFile $envFile -Force
}

if (Test-Path $envFile) {
    $envContent = Get-Content $envFile -Raw
    $envContent = $envContent -replace 'DB_DATABASE=laravel', 'DB_DATABASE=savmikem_bd'
    $envContent = $envContent -replace 'DB_USERNAME=root', 'DB_USERNAME=root'
    $envContent = $envContent -replace 'DB_PASSWORD=', 'DB_PASSWORD='
    $envContent = $envContent -replace 'APP_NAME=Laravel', 'APP_NAME="SAV Mikem"'
    $envContent = $envContent -replace 'MAIL_MAILER=log', 'MAIL_MAILER=log'
    Set-Content $envFile $envContent
}

Write-Host ""
Write-Host "======================================" -ForegroundColor Green
Write-Host "   Installation terminée !" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Green
Write-Host ""
Write-Host "Prochaines étapes :" -ForegroundColor Cyan
Write-Host "  1. Créez la base de données 'savmikem_bd' dans phpMyAdmin" -ForegroundColor White
Write-Host "  2. php artisan key:generate" -ForegroundColor White
Write-Host "  3. php artisan migrate --seed" -ForegroundColor White
Write-Host "  4. php artisan storage:link" -ForegroundColor White
Write-Host "  5. php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Comptes par défaut :" -ForegroundColor Cyan
Write-Host "  Admin:      admin@savmikem.com / admin123" -ForegroundColor White
Write-Host "  Technicien: technicien@savmikem.com / tech123" -ForegroundColor White
Write-Host ""
