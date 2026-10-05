# share-online.ps1
# Membagikan portfolio lokal ke internet lewat Cloudflare Tunnel (gratis, tanpa akun).
# Jalankan dari root project:  powershell -ExecutionPolicy Bypass -File .\share-online.ps1
# Berhenti berbagi: tekan Ctrl+C di jendela ini.
$ErrorActionPreference = 'Stop'
if (-not (Test-Path '.\artisan')) { throw 'Jalankan skrip ini dari folder root project Laravel (yang ada file artisan).' }
$root = (Get-Location).Path

function Find-Cloudflared {
    $cmd = Get-Command cloudflared -ErrorAction SilentlyContinue
    if ($cmd) { return $cmd.Source }
    foreach ($p in @(
        "$env:LOCALAPPDATA\cloudflared\cloudflared.exe",
        "$env:ProgramFiles\cloudflared\cloudflared.exe",
        "${env:ProgramFiles(x86)}\cloudflared\cloudflared.exe",
        "$env:LOCALAPPDATA\Microsoft\WinGet\Links\cloudflared.exe"
    )) { if ($p -and (Test-Path $p)) { return $p } }
    return $null
}

# 1. Pastikan cloudflared terpasang
Write-Host '[1/4] Mengecek cloudflared...' -ForegroundColor Cyan
$cf = Find-Cloudflared
if (-not $cf) {
    # Unduh langsung dari rilis resmi Cloudflare di GitHub (tidak butuh winget / admin)
    $dir = Join-Path $env:LOCALAPPDATA 'cloudflared'
    $cf  = Join-Path $dir 'cloudflared.exe'
    $url = 'https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-windows-amd64.exe'
    Write-Host "      Belum ada, mengunduh ke $cf ..."
    New-Item -ItemType Directory -Force -Path $dir | Out-Null
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    $ProgressPreference = 'SilentlyContinue'
    Invoke-WebRequest -Uri $url -OutFile $cf -UseBasicParsing
    $ProgressPreference = 'Continue'
    if (-not (Test-Path $cf) -or (Get-Item $cf).Length -lt 1MB) { throw "Gagal mengunduh cloudflared. Unduh manual dari $url lalu simpan sebagai $cf" }
}
Write-Host "      OK: $cf"

# 2. Supaya Laravel mengenali HTTPS dari tunnel (sekali saja)
Write-Host '[2/4] Mengecek trustProxies...' -ForegroundColor Cyan
$appFile = Join-Path $root 'bootstrap\app.php'
$c = [IO.File]::ReadAllText($appFile)
if ($c -notmatch 'trustProxies') {
    $c = $c.Replace('$middleware->web(append: [', "`$middleware->trustProxies(at: '*');`n        `$middleware->web(append: [")
    [IO.File]::WriteAllText($appFile, $c, (New-Object System.Text.UTF8Encoding($false)))
    Write-Host '      trustProxies ditambahkan ke bootstrap\app.php'
} else { Write-Host '      Sudah ada' }

# 3. Build aset (mode dev Vite tidak bisa diakses orang lain)
Write-Host '[3/4] Build aset (npm run build)...' -ForegroundColor Cyan
Remove-Item (Join-Path $root 'public\hot') -ErrorAction SilentlyContinue
npm run build
if ($LASTEXITCODE -ne 0) { throw 'npm run build gagal. Cek pesan error di atas.' }
php artisan optimize:clear | Out-Null

# 4. Jalankan server Laravel di port yang kosong, lalu buka tunnel
$port = 8000
while (Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue) { $port++ }
Write-Host "[4/4] Menjalankan server di port $port dan membuka tunnel..." -ForegroundColor Cyan
$server = Start-Process php -ArgumentList 'artisan', 'serve', '--host=127.0.0.1', "--port=$port" -PassThru -WindowStyle Hidden
Start-Sleep -Seconds 2

Write-Host ''
Write-Host '============================================================' -ForegroundColor Green
Write-Host ' Tunggu beberapa detik, lalu cari baris berisi link:' -ForegroundColor Green
Write-Host '   https://xxxx-xxxx.trycloudflare.com' -ForegroundColor Yellow
Write-Host ' Bagikan link itu. Tekan Ctrl+C untuk berhenti berbagi.' -ForegroundColor Green
Write-Host '============================================================' -ForegroundColor Green
Write-Host ''

$ErrorActionPreference = 'Continue'
try {
    & $cf tunnel --url "http://127.0.0.1:$port"
}
finally {
    if ($server -and -not $server.HasExited) { taskkill /PID $server.Id /T /F | Out-Null }
    Write-Host ''
    Write-Host 'Berbagi dihentikan. Untuk lanjut ngoding jalankan: npm run dev' -ForegroundColor Cyan
}
