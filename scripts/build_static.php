<?php

// scripts/build_static.php

// Configuration
define('DIST_DIR', __DIR__ . '/../dist');
define('BASE_URL', 'https://errorcode.help'); // Production URL

// Ensure dist directory exists
if (is_dir(DIST_DIR)) {
    // Recursive delete function
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(DIST_DIR, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir(DIST_DIR);
}
mkdir(DIST_DIR, 0755, true);

// Load Data
$categories = json_decode(file_get_contents(__DIR__ . '/../data/categories.json'), true);
$platforms = json_decode(file_get_contents(__DIR__ . '/../data/platforms.json'), true);
$errors = json_decode(file_get_contents(__DIR__ . '/../data/errors.json'), true);

// Helper to render template
function render_static($template, $data, $outputPath) {
    global $categories, $platforms, $errors; // Make available to templates
    
    // Mock Router/SEO classes if needed, or just extract data
    // We need to simulate the environment for the templates
    
    // Setup SEO mock
    require_once __DIR__ . '/../core/seo.php';
    $seoGen = new SEO(BASE_URL);
    
    // Determine SEO type and data
    $seoType = 'home';
    $seoData = $data;
    
    if (strpos($template, 'error.php') !== false) {
        $seoType = 'error';
        // Logic from Router::handleError
        $error = $data['error'];
        $seoData = $error; // Pass the error array directly
        
        $platformName = '';
        if (!empty($error['platforms'])) {
            foreach ($platforms as $p) {
                if ($p['slug'] === $error['platforms'][0]) {
                    $platformName = $p['name'];
                    break;
                }
            }
        }
        $displayTitle = ($error['code'] === $error['title']) ? $error['code'] : $error['code'] . ' ' . $error['title'];
        $seoTitle = $displayTitle;
        if ($platformName && stripos($displayTitle, $platformName) === false) {
            $seoTitle = $platformName . ' ' . $error['code'] . ': ' . $error['title'] . ' – Causes & Fix';
        } else {
            $seoTitle = $displayTitle . ' – Causes & How to Fix';
        }
        $seoData['seo_title'] = $seoTitle;
        $data['seoTitle'] = $seoTitle; // Pass to template
    } elseif (strpos($template, 'category.php') !== false) {
        $seoType = 'category';
        $seoData = $data['category']; // Pass the category array
    } elseif (strpos($template, 'platform.php') !== false) {
        $seoType = 'platform';
        $seoData = $data['platform']; // Pass the platform array
    } elseif (strpos($template, 'list_categories.php') !== false) {
        $seoType = 'list_categories';
    } elseif (strpos($template, 'list_platforms.php') !== false) {
        $seoType = 'list_platforms';
    } elseif (strpos($template, 'legal.php') !== false) {
        $seoType = 'legal';
    }

    $seo = $seoGen->generate($seoData, $seoType);
    
    // Variables expected by templates
    $root = ''; // Relative root for static site? No, let's use absolute BASE_URL or empty for root
    // Actually, templates use $root. If we deploy to root domain, $root should be empty string or BASE_URL.
    // Let's use empty string to make links relative to domain root.
    $root = ''; 
    $baseUrl = BASE_URL;

    extract($data);
    
    ob_start();
    require __DIR__ . '/../partials/header.php';
    require __DIR__ . '/../templates/' . $template;
    require __DIR__ . '/../partials/footer.php';
    $content = ob_get_clean();
    
    // Fix links for static hosting
    // Convert /errors/slug to /errors/slug/index.html (pretty URLs)
    // But Cloudflare Pages handles /errors/slug automatically if we create /errors/slug/index.html
    // The links in templates are like "$root/errors/$slug".
    // If $root is empty, it becomes "/errors/$slug". This works on CF Pages.
    
    $dir = dirname($outputPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($outputPath, $content);
    echo "Generated: $outputPath\n";
}

// 1. Home Page
render_static('home.php', [
    'categories' => $categories,
    'platforms' => $platforms,
    'recent_errors' => array_slice($errors, 0, 10)
], DIST_DIR . '/index.html');

// 2. List Pages
render_static('list_categories.php', ['categories' => $categories], DIST_DIR . '/categories/index.html');
render_static('list_platforms.php', ['platforms' => $platforms], DIST_DIR . '/platforms/index.html');

// 3. Categories
foreach ($categories as $category) {
    $categoryErrors = array_filter($errors, function($e) use ($category) {
        return $e['category'] === $category['slug'];
    });
    render_static('category.php', [
        'category' => $category,
        'errors' => $categoryErrors,
        'categories' => $categories,
        'platforms' => $platforms
    ], DIST_DIR . '/categories/' . $category['slug'] . '/index.html');
}

// 4. Platforms
foreach ($platforms as $platform) {
    $platformErrors = array_filter($errors, function($e) use ($platform) {
        return in_array($platform['id'], $e['platforms']);
    });
    render_static('platform.php', [
        'platform' => $platform,
        'errors' => $platformErrors,
        'categories' => $categories,
        'platforms' => $platforms
    ], DIST_DIR . '/platforms/' . $platform['slug'] . '/index.html');
}

// 5. Errors
foreach ($errors as $error) {
    // Logic for related errors (copied from Router)
    $relatedErrors = [];
    if (isset($error['content']['related_errors']) && is_array($error['content']['related_errors'])) {
        foreach ($error['content']['related_errors'] as $relatedSlug) {
            foreach ($errors as $e) {
                if ($e['slug'] === $relatedSlug) {
                    $relatedErrors[] = $e;
                    break;
                }
            }
        }
    }
    if (empty($relatedErrors)) {
        $relatedErrors = array_filter($errors, function($e) use ($error) {
            if ($e['slug'] === $error['slug']) return false;
            $platformMatch = !empty(array_intersect($e['platforms'], $error['platforms']));
            $categoryMatch = $e['category'] === $error['category'];
            return $platformMatch || $categoryMatch;
        });
        if (count($relatedErrors) > 5) {
            $relatedKeys = array_rand($relatedErrors, 5);
            $randomRelated = [];
            foreach ($relatedKeys as $key) {
                $randomRelated[] = $relatedErrors[$key];
            }
            $relatedErrors = $randomRelated;
        }
    }

    render_static('error.php', [
        'error' => $error,
        'related_errors' => $relatedErrors,
        'categories' => $categories,
        'platforms' => $platforms
    ], DIST_DIR . '/errors/' . $error['slug'] . '/index.html');
}

// 6. Copy Assets
function copy_dir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                copy_dir($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
if (is_dir(__DIR__ . '/../assets')) {
    copy_dir(__DIR__ . '/../assets', DIST_DIR . '/assets');
}

// 7. Copy Sitemaps & Robots
copy(__DIR__ . '/../sitemap.xml', DIST_DIR . '/sitemap.xml');
copy(__DIR__ . '/../robots.txt', DIST_DIR . '/robots.txt');
// Copy other sitemaps if they exist
foreach (glob(__DIR__ . '/../sitemap-*.xml') as $sitemap) {
    copy($sitemap, DIST_DIR . '/' . basename($sitemap));
}

// 8. Create Search Page (Static Version)
// We need a JS based search.
// Let's create a simple search.html that loads errors.json
$searchContent = file_get_contents(__DIR__ . '/../templates/search.php');
// We can't use the PHP template directly because it expects PHP logic.
// We'll create a special static search template or just render the PHP one with empty results and add JS.

// Let's create a search index json
$searchIndex = array_map(function($e) {
    return [
        'title' => $e['title'],
        'code' => $e['code'],
        'slug' => $e['slug'],
        'desc' => substr(strip_tags($e['content']['what_it_is']), 0, 100)
    ];
}, $errors);
file_put_contents(DIST_DIR . '/search_index.json', json_encode($searchIndex));

// Render search page
render_static('search.php', [
    'query' => '',
    'results' => []
], DIST_DIR . '/search/index.html');

// 9. Legal Page
render_static('legal.php', [], DIST_DIR . '/legal/index.html');

// 10. Cloudflare Headers (Optional but good for SEO)
$headers = "/sitemap.xml\n  Content-Type: application/xml\n";
$headers .= "/sitemap-*.xml\n  Content-Type: application/xml\n";
$headers .= "/robots.txt\n  Content-Type: text/plain\n";
file_put_contents(DIST_DIR . '/_headers', $headers);

echo "Build Complete! Output in /dist\n";
