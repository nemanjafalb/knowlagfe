<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$categoriesFile = __DIR__ . '/../data/categories.json';
$platformsFile = __DIR__ . '/../data/platforms.json';
$errorsFile = __DIR__ . '/../data/errors.json';

// Colors for CLI output
const RED = "\033[31m";
const GREEN = "\033[32m";
const YELLOW = "\033[33m";
const RESET = "\033[0m";

function fail($message) {
    echo RED . "[FAIL] " . $message . RESET . PHP_EOL;
    exit(1);
}

function pass($message) {
    echo GREEN . "[PASS] " . $message . RESET . PHP_EOL;
}

// 1. Load Categories
if (!file_exists($categoriesFile)) fail("categories.json not found.");
$categoriesData = json_decode(file_get_contents($categoriesFile), true);
if (json_last_error() !== JSON_ERROR_NONE) fail("categories.json is invalid JSON: " . json_last_error_msg());

$validCategories = [];
$validSubcategories = [];

foreach ($categoriesData as $cat) {
    if (!isset($cat['slug'])) fail("Category missing slug.");
    $validCategories[] = $cat['slug'];
    
    if (isset($cat['subcategories'])) {
        foreach ($cat['subcategories'] as $sub) {
            $validSubcategories[$cat['slug']][] = $sub['name'];
        }
    }
}
pass("Loaded " . count($validCategories) . " categories.");

// 2. Load Platforms
if (!file_exists($platformsFile)) fail("platforms.json not found.");
$platformsData = json_decode(file_get_contents($platformsFile), true);
if (json_last_error() !== JSON_ERROR_NONE) fail("platforms.json is invalid JSON: " . json_last_error_msg());

$validPlatforms = [];
foreach ($platformsData as $p) {
    if (!isset($p['id'])) fail("Platform missing id.");
    $validPlatforms[] = $p['id'];
}
pass("Loaded " . count($validPlatforms) . " platforms.");

// 3. Load Errors
if (!file_exists($errorsFile)) fail("errors.json not found.");
$errorsData = json_decode(file_get_contents($errorsFile), true);
if (json_last_error() !== JSON_ERROR_NONE) fail("errors.json is invalid JSON: " . json_last_error_msg());

pass("Loaded " . count($errorsData) . " errors.");

// 4. Validate Each Error
$slugs = [];

foreach ($errorsData as $index => $error) {
    echo "Checking error $index...\n";
    $id = $error['slug'] ?? "Index $index";
    
    // Check Required Fields
    $required = ['slug', 'code', 'title', 'category', 'subcategory', 'platforms', 'content'];
    
    // Check for unknown fields
    $unknown = array_diff(array_keys($error), $required);
    if (!empty($unknown)) {
        fail("Error '$id' has unknown fields: " . implode(', ', $unknown));
    }

    foreach ($required as $field) {
        if (!isset($error[$field])) {
            fail("Error '$id' missing required field: $field");
        }
    }

    // Check Slug Uniqueness
    if (in_array($error['slug'], $slugs)) {
        fail("Duplicate slug found: " . $error['slug']);
    }
    $slugs[] = $error['slug'];

    // Check Category
    if (!in_array($error['category'], $validCategories)) {
        fail("Error '$id' has invalid category: " . $error['category']);
    }

    // Check Subcategory
    if (!empty($error['subcategory'])) {
        if (!isset($validSubcategories[$error['category']]) || !in_array($error['subcategory'], $validSubcategories[$error['category']])) {
            fail("Error '$id' has invalid subcategory '{$error['subcategory']}' for category '{$error['category']}'");
        }
    }

    // Check Platforms
    if (!is_array($error['platforms'])) {
        fail("Error '$id' platforms must be an array.");
    }
    foreach ($error['platforms'] as $plat) {
        if (!in_array($plat, $validPlatforms)) {
            fail("Error '$id' has invalid platform: $plat");
        }
    }

    // Check Content Object
    if (!is_array($error['content'])) {
        fail("Error '$id' content must be an object.");
    }
    
    $contentRequired = ['what_it_is', 'common_causes', 'how_to_fix_users', 'how_to_fix_owners', 'when_not_your_fault', 'related_errors'];
    
    // Check for unknown content fields
    $unknownContent = array_diff(array_keys($error['content']), $contentRequired);
    if (!empty($unknownContent)) {
        fail("Error '$id' has unknown content fields: " . implode(', ', $unknownContent));
    }

    foreach ($contentRequired as $field) {
        if (!isset($error['content'][$field])) {
            fail("Error '$id' missing content field: $field");
        }
    }

    // Check Related Errors (Structure only)
    if (!is_array($error['content']['related_errors'])) {
        fail("Error '$id' related_errors must be an array.");
    }

    // Check for URLs in text (Markdown links or raw URLs)
    $textFields = [
        $error['content']['what_it_is'],
        $error['content']['when_not_your_fault']
    ];
    
    // Add array fields flattened
    $textFields = array_merge($textFields, $error['content']['common_causes'], $error['content']['how_to_fix_users'], $error['content']['how_to_fix_owners']);

    foreach ($textFields as $text) {
        if (preg_match('/https?:\/\//', $text) || preg_match('/\[.*\]\(.*\)/', $text)) {
            fail("Error '$id' contains URL or Markdown link in content: " . substr($text, 0, 50) . "...");
        }
    }
}

// 5. Validate Related Errors Integrity (Second Pass)
echo "Starting second pass...\n";
foreach ($errorsData as $index => $error) {
    if (isset($error['content']['related_errors'])) {
        foreach ($error['content']['related_errors'] as $relatedSlug) {
            if (!in_array($relatedSlug, $slugs)) {
                fail("Error '{$error['slug']}' links to non-existent related error: $relatedSlug");
            }
        }
    }
}

pass("Validated all errors against strict schema.");
