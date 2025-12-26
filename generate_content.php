<?php

// CLI-only check
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

require_once __DIR__ . '/core/validate_data.php'; // Re-use validation logic if needed, or just load env

// Load .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Configuration
$env = getenv('APP_ENV') ?: 'local';
$enabled = getenv('GENERATION_ENABLED') === 'true';
$limit = (int)(getenv('GENERATION_DAILY_LIMIT') ?: 5);
$batchSize = (int)(getenv('GENERATION_BATCH_SIZE') ?: 1);
$lockFile = __DIR__ . '/' . (getenv('GENERATION_LOCK_FILE') ?: 'generation.lock');

echo "Starting Generation Script...\n";
echo "Environment: $env\n";

if (!$enabled) {
    echo "Generation is disabled in .env. Exiting.\n";
    exit(0);
}

if (file_exists($lockFile)) {
    // Check if lock file is stale (older than 1 hour)
    if (time() - filemtime($lockFile) > 3600) {
        echo "Stale lock file found. Removing...\n";
        unlink($lockFile);
    } else {
        echo "Generation is already running (lock file exists). Exiting.\n";
        exit(0);
    }
}

// Create lock file
file_put_contents($lockFile, getmypid());

try {
    // Simulation of Generation Logic
    echo "Loading data sources...\n";
    $errorsFile = __DIR__ . '/data/errors.json';
    $errors = json_decode(file_get_contents($errorsFile), true);
    
    // In a real scenario, we would have a list of "pending" errors to generate.
    // For this system, we assume we are looking for gaps or have a queue.
    // Since the user said "System MUST stop when all valid errors are exhausted",
    // we imply there is a finite set of errors we want to cover.
    
    // For this implementation, we will just log that we are checking for work.
    echo "Checking for missing content...\n";
    
    $generatedCount = 0;
    
    // Mock Loop
    while ($generatedCount < $limit) {
        // 1. Identify next error to generate (Mock)
        $nextError = null; // fetchNextMissingError();
        
        if (!$nextError) {
            echo "No more errors to generate. System exhausted.\n";
            break;
        }
        
        // 2. Call Gemini API (Mock)
        // $json = callGemini($nextError);
        
        // 3. Validate JSON
        // validateJson($json);
        
        // 4. Save to errors.json
        // saveError($json);
        
        $generatedCount++;
        echo "Generated error: [MOCK_SLUG] ($generatedCount/$limit)\n";
        
        // Batch sleep
        if ($generatedCount % $batchSize === 0) {
            sleep(1);
        }
    }
    
    echo "Generation complete. Total generated: $generatedCount\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Remove lock file
    if (file_exists($lockFile)) {
        unlink($lockFile);
    }
}
