<?php
/**
 * CrackAEEE Live Server Migration & Maintenance Runner
 * Use this on cPanel / Shared hosting where SSH terminal is not available.
 * 
 * URL: https://crackaeee.com/run-migrate.php
 */

// Set unlimited execution time and display all errors
ini_set('max_execution_time', 300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bootstrap Laravel
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php',
];

$autoloadFile = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloadFile = $path;
        break;
    }
}

if (!$autoloadFile) {
    die('<h3 style="color:red; font-family:sans-serif;">Error: Could not find vendor/autoload.php. Please ensure files are uploaded in the correct directory.</h3>');
}

require $autoloadFile;

$appPaths = [
    __DIR__ . '/../bootstrap/app.php',
    __DIR__ . '/bootstrap/app.php',
];

$appFile = null;
foreach ($appPaths as $path) {
    if (file_exists($path)) {
        $appFile = $path;
        break;
    }
}

if (!$appFile) {
    die('<h3 style="color:red; font-family:sans-serif;">Error: Could not find bootstrap/app.php.</h3>');
}

$app = require_once $appFile;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure all seeder classes are loaded into memory (critical for cPanel without composer dump-autoload)
$seederDirs = [
    __DIR__ . '/../database/seeders',
    __DIR__ . '/database/seeders',
];
foreach ($seederDirs as $dir) {
    if (is_dir($dir)) {
        foreach (glob($dir . '/*.php') as $seederFile) {
            require_once $seederFile;
        }
    }
}

$action = $_REQUEST['action'] ?? null;
$customSeeder = trim($_REQUEST['custom_seeder'] ?? '');
$output = '';
$hasRun = false;

if ($action) {
    $hasRun = true;
    try {
        if ($action === 'migrate' || $action === 'all') {
            Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output .= "=== MIGRATION OUTPUT ===\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
        }

        if ($action === 'seed_database' || $action === 'all') {
            Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
            $output .= "=== DATABASE SEEDER OUTPUT ===\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
        }

        if ($action === 'seed_aeee1') {
            Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'AeeeMockTest1Seeder', '--force' => true]);
            $output .= "=== AEEE MOCK TEST 1 SEEDER OUTPUT ===\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
        }

        if ($action === 'seed_mock') {
            Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'MockTestSeeder', '--force' => true]);
            $output .= "=== MOCK TEST SEEDER OUTPUT ===\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
        }

        if ($action === 'seed_custom' && !empty($customSeeder)) {
            // Strip any namespace prefix if user types Database\Seeders\X or bare X
            $seederName = basename(str_replace('\\', '/', $customSeeder));
            Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => $seederName, '--force' => true]);
            $output .= "=== CUSTOM SEEDER OUTPUT ({$seederName}) ===\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
        }

        if ($action === 'clear_cache' || $action === 'all') {
            Illuminate\Support\Facades\Artisan::call('cache:clear');
            Illuminate\Support\Facades\Artisan::call('view:clear');
            Illuminate\Support\Facades\Artisan::call('config:clear');
            Illuminate\Support\Facades\Artisan::call('route:clear');
            $output .= "=== CACHE CLEARED SUCCESSFULLY ===\n";
        }
    } catch (\Throwable $e) {
        $output .= "\n❌ ERROR: " . $e->getMessage() . "\n\n" . $e->getTraceAsString();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrackAEEE - Live Server Migration Runner</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: ui-monospace, Menlo, Monaco, Consolas, monospace; background: #0b1120; color: #f8fafc; padding: 24px; margin: 0; }
        .card { max-width: 860px; margin: 20px auto; background: #1e293b; border: 1px solid #334155; border-radius: 18px; padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        h1 { font-size: 22px; color: #38bdf8; margin: 0 0 10px 0; display: flex; align-items: center; gap: 10px; font-weight: 800; }
        p.desc { color: #94a3b8; font-size: 13.5px; line-height: 1.5; margin: 0 0 20px 0; }
        .btn-group { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 800; font-size: 13px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-all { background: #9d174d; color: white; box-shadow: 0 4px 14px rgba(157, 23, 77, 0.4); font-size: 14px; }
        .btn-all:hover { background: #831843; transform: translateY(-1px); }
        .btn-migrate { background: #059669; color: white; }
        .btn-migrate:hover { background: #047857; transform: translateY(-1px); }
        .btn-seed { background: #d97706; color: white; }
        .btn-seed:hover { background: #b45309; transform: translateY(-1px); }
        .btn-cache { background: #475569; color: white; }
        .btn-cache:hover { background: #334155; transform: translateY(-1px); }
        pre { background: #050814; color: #67e8f9; padding: 20px; border-radius: 12px; font-size: 13px; line-height: 1.6; overflow-x: auto; border: 1px solid #1e293b; white-space: pre-wrap; word-break: break-word; }
        .badge { background: #10b981; color: white; font-size: 11px; padding: 3px 8px; border-radius: 6px; font-weight: 800; }
        .footer { font-size: 12px; color: #64748b; margin-top: 24px; border-top: 1px solid #334155; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🚀 CrackAEEE Live Migration Runner <span class="badge">READY</span></h1>
        <p class="desc">Click any button below to execute database migrations, seed questions, or clear cache on your live server.</p>

        <form method="POST" action="" class="btn-group">
            <button type="submit" name="action" value="all" class="btn btn-all">
                ⚡ Run All (Migrate + DatabaseSeeder + Clear Cache)
            </button>
            <button type="submit" name="action" value="migrate" class="btn btn-migrate">
                📦 Run Migrations Only
            </button>
            <button type="submit" name="action" value="seed_database" class="btn btn-seed">
                🌱 Run DatabaseSeeder (All Seeders)
            </button>
            <button type="submit" name="action" value="seed_aeee1" class="btn" style="background:#7c3aed; color:white;">
                🎯 Run AeeeMockTest1Seeder Only
            </button>
            <button type="submit" name="action" value="seed_mock" class="btn" style="background:#0284c7; color:white;">
                📚 Run MockTestSeeder
            </button>
            <button type="submit" name="action" value="clear_cache" class="btn btn-cache">
                🧹 Clear Cache
            </button>

            <div style="width: 100%; margin-top: 14px; display: flex; gap: 10px; align-items: center;">
                <input type="text" name="custom_seeder" placeholder="Enter seeder name, e.g. AeeeMockTest1Seeder" style="flex: 1; padding: 12px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 10px; color: #f8fafc; font-family: monospace; font-size: 13px;">
                <button type="submit" name="action" value="seed_custom" class="btn" style="background: #e11d48; color: white;">
                    ▶ Run Custom Seeder
                </button>
            </div>
        </form>

        <?php if ($hasRun): ?>
            <h3 style="font-size: 13px; color: #e2e8f0; text-transform: uppercase; letter-spacing: 0.05em; margin: 15px 0 8px 0;">Execution Output:</h3>
            <pre><?= htmlspecialchars($output) ?></pre>
        <?php else: ?>
            <div style="background: #0f172a; border: 1px dashed #334155; border-radius: 12px; padding: 20px; text-align: center; color: #94a3b8; font-size: 13px;">
                👆 Click <strong>"⚡ Run All (Migrate + Seed Mock Tests + Clear Cache)"</strong> above to start!
            </div>
        <?php endif; ?>

        <div class="footer">
            🔒 <strong>Note:</strong> Once your database is updated, you can safely delete <code>public/run-migrate.php</code> from your cPanel File Manager.
        </div>
    </div>
</body>
</html>
