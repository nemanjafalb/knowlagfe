<?php

require_once __DIR__ . '/seo.php';

class Router {
    private $routes = [];
    private $baseUrl;
    private $basePath;
    private $seo;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
        $this->seo = new SEO($baseUrl);
        $this->basePath = parse_url($baseUrl, PHP_URL_PATH) ?: '';
        $this->basePath = trim($this->basePath, '/');
    }

    public function add($pattern, $callback) {
        $this->routes[$pattern] = $callback;
    }

    public function dispatch($uri) {
        // Remove query string and trim slashes
        $uriPath = parse_url($uri, PHP_URL_PATH);
        $uriPath = trim($uriPath, '/');
        
        // Remove base path from URI if it exists (case-insensitive)
        if ($this->basePath && stripos($uriPath, $this->basePath) === 0) {
            $uriPath = substr($uriPath, strlen($this->basePath));
            $uriPath = trim($uriPath, '/');
        }
        
        if ($uriPath === '') {
            $this->handleHome();
            return;
        }

        // Handle Legal Page
        if ($uriPath === 'legal') {
            $this->render('legal.php', [], 'legal');
            return;
        }

        foreach ($this->routes as $pattern => $callback) {
            if (preg_match("#^$pattern$#", $uriPath, $matches)) {
                array_shift($matches); // Remove full match
                call_user_func_array($callback, $matches);
                return;
            }
        }

        $this->handle404();
    }

    private function loadData($file) {
        $path = __DIR__ . '/../data/' . $file;
        if (!file_exists($path)) return [];
        $data = json_decode(file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    private function render($template, $data = [], $seoType = 'home', $seoData = []) {
        $seo = $this->seo->generate($seoData ?: $data, $seoType);
        $root = rtrim(parse_url($this->baseUrl, PHP_URL_PATH) ?? '', '/');
        $baseUrl = $this->baseUrl; // Pass full base URL
        extract($data);
        require __DIR__ . '/../partials/header.php';
        require __DIR__ . '/../templates/' . $template;
        require __DIR__ . '/../partials/footer.php';
    }

    public function handleHome() {
        $categories = $this->loadData('categories.json');
        $platforms = $this->loadData('platforms.json');
        $errors = $this->loadData('errors.json');
        
        $this->render('home.php', [
            'categories' => $categories,
            'platforms' => $platforms,
            'recent_errors' => array_slice($errors, 0, 10)
        ], 'home');
    }

    public function handleError($slug) {
        $errors = $this->loadData('errors.json');
        $categories = $this->loadData('categories.json');
        $platforms = $this->loadData('platforms.json');
        $error = null;
        foreach ($errors as $e) {
            if ($e['slug'] === $slug) {
                $error = $e;
                break;
            }
        }

        if (!$error) {
            $this->handle404();
            return;
        }

        // Fetch related errors
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

        // Fallback: If no related errors defined, fetch from same platform or category
        if (empty($relatedErrors)) {
            $relatedErrors = array_filter($errors, function($e) use ($error) {
                // Exclude self
                if ($e['slug'] === $error['slug']) return false;
                
                // Check platform overlap
                $platformMatch = !empty(array_intersect($e['platforms'], $error['platforms']));
                
                // Check category match
                $categoryMatch = $e['category'] === $error['category'];
                
                return $platformMatch || $categoryMatch;
            });
            
            // Limit to 5 random related errors
            if (count($relatedErrors) > 5) {
                $relatedKeys = array_rand($relatedErrors, 5);
                $randomRelated = [];
                foreach ($relatedKeys as $key) {
                    $randomRelated[] = $relatedErrors[$key];
                }
                $relatedErrors = $randomRelated;
            }
        }

        // SEO Title Logic
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

        $seoData = $error;
        $seoData['seo_title'] = $seoTitle; // Pass explicit title
        $seoData['seo_description'] = $error['meta_description'] ?? null;

        $this->render('error.php', [
            'error' => $error,
            'related_errors' => $relatedErrors,
            'categories' => $categories,
            'platforms' => $platforms,
            'seoTitle' => $seoTitle // Pass to template as well
        ], 'error', $seoData);
    }

    public function handleCategory($slug) {
        $categories = $this->loadData('categories.json');
        $platforms = $this->loadData('platforms.json');
        $category = null;
        foreach ($categories as $c) {
            if ($c['slug'] === $slug) {
                $category = $c;
                break;
            }
        }

        if (!$category) {
            $this->handle404();
            return;
        }

        $allErrors = $this->loadData('errors.json');
        $categoryErrors = array_filter($allErrors, function($e) use ($category) {
            return $e['category'] === $category['slug']; // Fixed: use slug comparison as per new schema
        });

        $this->render('category.php', [
            'category' => $category,
            'errors' => $categoryErrors,
            'categories' => $categories,
            'platforms' => $platforms
        ], 'category', $category);
    }

    public function handlePlatform($slug) {
        $platforms = $this->loadData('platforms.json');
        $categories = $this->loadData('categories.json');
        $platform = null;
        foreach ($platforms as $p) {
            if ($p['slug'] === $slug) {
                $platform = $p;
                break;
            }
        }

        if (!$platform) {
            $this->handle404();
            return;
        }

        $allErrors = $this->loadData('errors.json');
        $platformErrors = array_filter($allErrors, function($e) use ($platform) {
            return in_array($platform['id'], $e['platforms']);
        });

        $this->render('platform.php', [
            'platform' => $platform,
            'errors' => $platformErrors,
            'categories' => $categories,
            'platforms' => $platforms
        ], 'platform', $platform);
    }
    
    public function handleListCategories() {
        $categories = $this->loadData('categories.json');
        $this->render('list_categories.php', ['categories' => $categories], 'list_categories');
    }

    public function handleListPlatforms() {
        $platforms = $this->loadData('platforms.json');
        $this->render('list_platforms.php', ['platforms' => $platforms], 'list_platforms');
    }

    public function handleSearch() {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $errors = $this->loadData('errors.json');
        $results = [];

        if ($query) {
            foreach ($errors as $error) {
                if (stripos($error['title'], $query) !== false || 
                    stripos($error['code'], $query) !== false || 
                    stripos($error['content']['what_it_is'], $query) !== false) {
                    $results[] = $error;
                }
            }
        }

        $this->render('search.php', [
            'query' => $query,
            'results' => $results
        ], 'home');
    }

    private function handle404() {
        http_response_code(404);
        echo "404 Not Found";
    }
}
