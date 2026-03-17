<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "---------------------------------\n";
echo "1. Checking Database Connection\n";
echo "---------------------------------\n";
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    echo "✅ DB Connection: OK (Database: $dbName)\n";
} catch (\Exception $e) {
    echo "❌ DB Connection: ERROR - " . $e->getMessage() . "\n";
}

echo "\n---------------------------------\n";
echo "2. Checking PHP Syntax\n";
echo "---------------------------------\n";
$dirs = ['app', 'routes', 'resources/views', 'config'];
$hasError = false;

function lintDir($dir) {
    global $hasError;
    if (!is_dir($dir)) return;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $output = [];
            $return = 0;
            exec("php -l \"" . $file->getPathname() . "\" 2>&1", $output, $return);
            if ($return !== 0) {
                // If there's an error, echo it
                echo "❌ Syntax error in: " . $file->getPathname() . "\n   " . implode("\n   ", $output) . "\n";
                $hasError = true;
            }
        }
    }
}

foreach ($dirs as $dir) {
    echo "Scanning $dir...\n";
    lintDir(__DIR__ . DIRECTORY_SEPARATOR . $dir);
}

if (!$hasError) {
    echo "✅ All syntax checks passed! Zero errors found.\n";
}
