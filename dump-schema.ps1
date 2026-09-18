# ================================================================
# dump-schema.ps1 - Ekstrak schema SQL dari PostgreSQL
#
# Usage:
#   .\dump-schema.ps1
#   .\dump-schema.ps1 -EnvFile .env.production
#   .\dump-schema.ps1 -OutDir docs/schema
# ================================================================
param(
    [string]$EnvFile = ".env",
    [string]$OutDir  = "docs/schema"
)

# ── Helper: baca nilai dari file .env ────────────────────────────
function Get-EnvValue {
    param([string]$File, [string]$Key)
    $line = Get-Content $File -ErrorAction SilentlyContinue |
            Where-Object { $_ -match "^${Key}=(.+)" } |
            Select-Object -First 1
    if ($line -match "^${Key}=(.+)") { return $Matches[1].Trim().Trim('"').Trim("'") }
    return $null
}

function Coalesce {
    param([string]$a, [string]$b)
    if ($a) { return $a } else { return $b }
}

# ── Cek .env ada ─────────────────────────────────────────────────
if (-not (Test-Path $EnvFile)) {
    Write-Error "File '$EnvFile' tidak ditemukan. Jalankan dari root project."
    exit 1
}

# ── Baca konfigurasi database dari .env ──────────────────────────
$db1Host = Coalesce (Get-EnvValue $EnvFile "DB_HOST") "localhost"
$db1Port = Coalesce (Get-EnvValue $EnvFile "DB_PORT") "5432"
$db1Name = Coalesce (Get-EnvValue $EnvFile "DB_DATABASE") "lab_management"
$db1User = Coalesce (Get-EnvValue $EnvFile "DB_USERNAME") "laravel"
$db1Pass = Coalesce (Get-EnvValue $EnvFile "DB_PASSWORD") ""

$db2Host = Coalesce (Get-EnvValue $EnvFile "DB_FINANCE_HOST") $db1Host
$db2Port = Coalesce (Get-EnvValue $EnvFile "DB_FINANCE_PORT") $db1Port
$db2Name = Coalesce (Get-EnvValue $EnvFile "DB_FINANCE_DATABASE") "finance"
$db2User = Coalesce (Get-EnvValue $EnvFile "DB_FINANCE_USERNAME") $db1User
$db2Pass = Coalesce (Get-EnvValue $EnvFile "DB_FINANCE_PASSWORD") $db1Pass

$databases = @(
    @{ Label = "lab_management"; Host = $db1Host; Port = $db1Port; Database = $db1Name; Username = $db1User; Password = $db1Pass },
    @{ Label = "finance";        Host = $db2Host; Port = $db2Port; Database = $db2Name; Username = $db2User; Password = $db2Pass }
)

# ── Buat output directory ─────────────────────────────────────────
New-Item -ItemType Directory -Path $OutDir -Force | Out-Null
$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Lab Management - Schema Extractor" -ForegroundColor Cyan
Write-Host "  Timestamp : $timestamp" -ForegroundColor Gray
Write-Host "  Env file  : $EnvFile" -ForegroundColor Gray
Write-Host "  Output    : $OutDir\" -ForegroundColor Gray
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# ── Cek apakah pg_dump tersedia ──────────────────────────────────
$pgDumpPath = $null

$pgDumpCmd = Get-Command pg_dump -ErrorAction SilentlyContinue
if ($pgDumpCmd) {
    $pgDumpPath = $pgDumpCmd.Source
}

if (-not $pgDumpPath) {
    $candidates = @(
        "C:\Program Files\PostgreSQL\17\bin\pg_dump.exe",
        "C:\Program Files\PostgreSQL\16\bin\pg_dump.exe",
        "C:\Program Files\PostgreSQL\15\bin\pg_dump.exe",
        "C:\Program Files\PostgreSQL\14\bin\pg_dump.exe",
        "C:\laragon\bin\postgresql\bin\pg_dump.exe",
        "C:\laragon\bin\postgresql16\bin\pg_dump.exe"
    )
    foreach ($c in $candidates) {
        if (Test-Path $c) { $pgDumpPath = $c; break }
    }
}

$useDockerFallback = $false
$pgContainer = $null

if (-not $pgDumpPath) {
    Write-Host "pg_dump tidak ditemukan di lokal, cek Docker container..." -ForegroundColor Yellow
    $pgContainer = docker ps --filter "name=postgres" --format "{{.Names}}" 2>$null | Select-Object -First 1
    if ($pgContainer) {
        Write-Host "Menggunakan Docker container: $pgContainer" -ForegroundColor Yellow
        $useDockerFallback = $true
    } else {
        Write-Error "pg_dump tidak ditemukan dan tidak ada container postgres yang running."
        Write-Host "Solusi: install PostgreSQL client atau jalankan container postgres dulu."
        exit 1
    }
} else {
    Write-Host "Menggunakan pg_dump: $pgDumpPath" -ForegroundColor Gray
}

Write-Host ""

# ── Ekstrak per database ──────────────────────────────────────────
$allSuccess = $true

foreach ($db in $databases) {
    $label      = $db.Label
    $outFile    = Join-Path $OutDir "${label}_schema_${timestamp}.sql"
    $latestFile = Join-Path $OutDir "${label}_schema_latest.sql"

    Write-Host "[$label]" -ForegroundColor Green -NoNewline
    Write-Host " $($db.Host):$($db.Port)/$($db.Database) ..." -ForegroundColor White

    $env:PGPASSWORD = $db.Password

    if ($useDockerFallback) {
        $dumpOutput = docker exec `
            -e "PGPASSWORD=$($db.Password)" `
            $pgContainer `
            pg_dump `
            --host=$($db.Host) `
            --port=$($db.Port) `
            --username=$($db.Username) `
            --dbname=$($db.Database) `
            --schema-only `
            --no-owner `
            --no-acl 2>&1
    } else {
        # Host "postgres" hanya resolve di dalam Docker network
        # Dari lokal, gunakan localhost
        $resolvedHost = $db.Host
        if ($resolvedHost -eq "postgres") { $resolvedHost = "localhost" }

        $dumpOutput = & $pgDumpPath `
            --host=$resolvedHost `
            --port=$($db.Port) `
            --username=$($db.Username) `
            --dbname=$($db.Database) `
            --schema-only `
            --no-owner `
            --no-acl 2>&1
    }

    $env:PGPASSWORD = ""

    if ($LASTEXITCODE -eq 0 -and $dumpOutput) {
        $header = @"
-- ================================================================
-- Schema  : $label
-- Database: $($db.Database)
-- Extracted: $timestamp
-- Host    : $($db.Host):$($db.Port)
-- ================================================================

"@
        $content = $header + ($dumpOutput -join "`n")

        # Simpan dengan timestamp (history)
        $content | Set-Content -Path $outFile -Encoding UTF8

        # Simpan sebagai latest (mudah di-diff)
        $content | Set-Content -Path $latestFile -Encoding UTF8

        $sizeKB = [math]::Round((Get-Item $outFile).Length / 1KB, 1)
        Write-Host "  OK  Snapshot : $outFile ($sizeKB KB)" -ForegroundColor Green
        Write-Host "  OK  Latest   : $latestFile" -ForegroundColor Green
    } else {
        Write-Host "  GAGAL ekstrak schema '$label'" -ForegroundColor Red
        if ($dumpOutput) {
            Write-Host "  Error: $($dumpOutput -join ' ')" -ForegroundColor Red
        }
        $allSuccess = $false
    }

    Write-Host ""
}

# ── Summary ──────────────────────────────────────────────────────
if ($allSuccess) {
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  Selesai! Output: $OutDir\" -ForegroundColor Green
    Write-Host ""
    Get-ChildItem $OutDir -Filter "*.sql" | Sort-Object Name | ForEach-Object {
        $sizeKB = [math]::Round($_.Length / 1KB, 1)
        Write-Host "  $($_.Name)  ($sizeKB KB)" -ForegroundColor Gray
    }
    Write-Host "========================================" -ForegroundColor Cyan
} else {
    Write-Host "Beberapa database gagal. Cek pesan error di atas." -ForegroundColor Yellow
    exit 1
}
