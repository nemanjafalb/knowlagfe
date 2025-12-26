<?php

// Increase time limit for long generation
set_time_limit(300);

// Load Environment Variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[$name] = trim($value);
        }
    }
}

$apiKey = $_ENV['GEMINI_API_KEY'] ?? null;
$dailyLimit = isset($_ENV['DAILY_LIMIT']) ? (int)$_ENV['DAILY_LIMIT'] : 50; // Default to 50 if not set

if (!$apiKey) {
    die("Error: GEMINI_API_KEY not found in .env\n");
}

// Paths
$errorsFile = __DIR__ . '/../data/errors.json';
$platformsFile = __DIR__ . '/../data/platforms.json';
$categoriesFile = __DIR__ . '/../data/categories.json';

// Load Data
$errors = json_decode(file_get_contents($errorsFile), true);
$platforms = json_decode(file_get_contents($platformsFile), true);
$categories = json_decode(file_get_contents($categoriesFile), true);

// Check Daily Limit
$today = date('Y-m-d');
$generatedToday = 0;
foreach ($errors as $error) {
    if (isset($error['created_at']) && strpos($error['created_at'], $today) === 0) {
        $generatedToday++;
    }
}

if ($generatedToday >= $dailyLimit) {
    die("Daily limit of $dailyLimit reached. Generated today: $generatedToday. Stopping.\n");
}

echo "Daily Progress: $generatedToday / $dailyLimit\n";

// Extract existing codes to avoid duplicates
$existingCodes = array_map(function($e) { return $e['code']; }, $errors);

// Randomize existing codes to give Gemini a better sample of what we have
shuffle($existingCodes);

// Helper: Call Gemini API
function callGemini($prompt, $apiKey) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
    
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.7,
            "maxOutputTokens" => 2000,
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "Curl Error: " . curl_error($ch) . "\n";
        return null;
    }
    
    curl_close($ch);
    
    $json = json_decode($response, true);
    
    if (!isset($json['candidates'][0]['content']['parts'][0]['text'])) {
        echo "API Error Response: " . $response . "\n";
        return null;
    }

    return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
}

// Helper: Clean JSON string from Markdown
function cleanJson($text) {
    $text = preg_replace('/^```json/', '', $text);
    $text = preg_replace('/^```/', '', $text);
    $text = preg_replace('/```$/', '', $text);
    return trim($text);
}

// Helper: Normalize Category Slug
function normalizeCategorySlug($slug) {
    // Convert to lowercase
    $slug = strtolower($slug);
    // Replace underscores with hyphens
    $slug = str_replace('_', '-', $slug);
    // Remove 'error' or 'errors' suffix to standardize (e.g. cloudflare-errors -> cloudflare)
    // But wait, we want 'cloudflare-errors' as the standard? Or just 'cloudflare'?
    // The user mentioned "cloudflare-error" vs "cloudflare-errors".
    // Let's standardize on plural "-errors" if it ends with error/errors.
    
    if (preg_match('/-error$/', $slug)) {
        $slug = $slug . 's';
    }
    
    // If it doesn't have -errors suffix, and it's a platform name, maybe add it?
    // Actually, let's just ensure singular 'error' becomes 'errors'.
    
    return $slug;
}

// 1. Select a random platform to focus on
$maxAttempts = 3;
$attempt = 0;
$generatedCount = 0;

while ($attempt < $maxAttempts && $generatedCount === 0) {
    $attempt++;
    $randomPlatform = $platforms[array_rand($platforms)];
    $platformName = $randomPlatform['name'];
    $platformSlug = $randomPlatform['slug'];

    echo "Attempt $attempt/$maxAttempts: Targeting Platform: $platformName ($platformSlug)\n";

    // 2. Ask Gemini for new error codes
    $promptDiscovery = "I have a database of internet error codes. 
    I am focusing on the platform: '$platformName'.
    Existing codes I already have: " . implode(", ", array_slice($existingCodes, 0, 50)) . " (and others).
    Please list 3 REAL, TECHNICAL error codes specifically for '$platformName' that are NOT in the list above.
    Return ONLY a JSON array of strings. Example: [\"ERR_CODE_1\", \"ERR_CODE_2\"]";

    echo "Discovering new errors...\n";
    $discoveryResponse = callGemini($promptDiscovery, $apiKey);

    if (!$discoveryResponse) {
        echo "Failed to get response from Gemini for discovery. Retrying...\n";
        continue;
    }

    $newCodes = json_decode(cleanJson($discoveryResponse), true);

    if (!is_array($newCodes) || empty($newCodes)) {
        echo "No valid new codes found or JSON parse error. Retrying...\n";
        continue;
    }

    echo "Found potential new codes: " . implode(", ", $newCodes) . "\n";

    // 3. Generate content for each new code
    foreach ($newCodes as $code) {
        if (in_array($code, $existingCodes)) {
            echo "Skipping $code (already exists)\n";
            continue;
        }

        echo "Generating content for: $code...\n";

        $allowedCategories = [
            'http-errors',
            'dns-errors',
            'ssl-tls-errors',
            'cloudflare-errors',
            'browser-errors',
            'server-errors',
            'platform-specific-errors',
            'permission-errors'
        ];
        $allowedCatsString = implode("', '", $allowedCategories);

        $promptContent = "Generate a detailed JSON object for the error code '$code' on platform '$platformName'.
        The JSON must strictly follow this schema:
        {
            \"slug\": \"$code-human-readable-title-slugified\",
            \"code\": \"$code\",
            \"title\": \"Human Readable Title of Error\",
            \"meta_description\": \"A concise SEO description (max 155 chars) including the error code, platform, and main cause. Do NOT use generic phrases like 'This error occurs when'. Write it as a direct answer.\",
            \"category\": \"Select ONE from this STRICT whitelist: '$allowedCatsString'. Do NOT invent new categories. If unsure, use 'platform-specific-errors'.\",
            \"subcategory\": \"A short subcategory string (e.g. 'Connection Errors', 'Permission Issues')\",
            \"platforms\": [\"$platformSlug\"],
            \"content\": {
                \"what_it_is\": \"Detailed explanation (2-3 sentences).\",
                \"common_causes\": [\"Cause 1\", \"Cause 2\", \"Cause 3\"],
                \"how_to_fix_users\": [\"Step 1\", \"Step 2\"],
                \"how_to_fix_owners\": [\"Technical Step 1\", \"Technical Step 2\"],
                \"when_not_your_fault\": \"Explanation when it is not the user's fault.\",
                \"related_errors\": []
            }
        }
        IMPORTANT: 
        1. The 'slug' should NOT start with 'error-' unless the code itself starts with 'error'. It should be clean, e.g., '1004-connection-timed-out'.
        2. Ensure the content is high quality, technical, and accurate. 
        3. Return ONLY the JSON.";

        $contentResponse = callGemini($promptContent, $apiKey);
        
        if ($contentResponse) {
            $newErrorData = json_decode(cleanJson($contentResponse), true);
            
            if ($newErrorData && isset($newErrorData['code'])) {
                // Basic validation
                if (!isset($newErrorData['platforms']) || !in_array($platformSlug, $newErrorData['platforms'])) {
                    $newErrorData['platforms'][] = $platformSlug;
                }

                // Enforce Category Whitelist
                $categorySlug = normalizeCategorySlug($newErrorData['category']);
                
                if (!in_array($categorySlug, $allowedCategories)) {
                    echo "Warning: AI suggested invalid category '$categorySlug'. Fallback to 'platform-specific-errors'.\n";
                    $categorySlug = 'platform-specific-errors';
                }
                
                $newErrorData['category'] = $categorySlug;
                $newErrorData['created_at'] = date('Y-m-d H:i:s'); // Add timestamp for daily limit tracking

                // No dynamic category creation allowed.
                
                $errors[] = $newErrorData;
                $existingCodes[] = $code; // Add to local list to prevent dupes in same run
                $generatedCount++;
                echo "Successfully generated: $code\n";
            } else {
                echo "Failed to parse JSON for $code\n";
            }
        } else {
            echo "Failed to get content for $code\n";
        }
        
        // Sleep to avoid rate limits
        sleep(2);
    }
}

// 4. Save updated database
if ($generatedCount > 0) {
    file_put_contents($errorsFile, json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "Saved $generatedCount new errors to database.\n";
    
    // 5. Regenerate Sitemap
    require_once __DIR__ . '/../core/sitemap-generator.php';
    // Need to reconstruct base URL or hardcode it for CLI
    // For CLI, we might need to guess or use a config. Let's assume localhost for now or read from .env if available
    $baseUrl = $_ENV['APP_URL'] ?? 'https://errorcode.help'; 
    
    $generator = new SitemapGenerator($baseUrl, __DIR__ . '/../data', __DIR__ . '/../');
    $generator->generate();
    echo "Sitemap regenerated.\n";
} else {
    echo "No new errors were added.\n";
}
