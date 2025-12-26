<?php

class SitemapGenerator {
    private $baseUrl;
    private $dataPath;
    private $publicPath;
    private $maxUrlsPerFile = 500;

    public function __construct($baseUrl, $dataPath, $publicPath) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->dataPath = rtrim($dataPath, '/');
        $this->publicPath = rtrim($publicPath, '/');
    }

    public function generate() {
        $sitemapFiles = [];

        // 1. Categories Sitemap
        $categoryUrls = [];
        $categoryUrls[] = [
            'loc' => $this->baseUrl . '/categories',
            'priority' => '0.8',
            'changefreq' => 'weekly'
        ];
        $this->collectUrls($categoryUrls, 'categories.json', 'categories', 'slug', '0.7', 'monthly');
        $this->writeSitemapFile('sitemap-categories.xml', $categoryUrls);
        $sitemapFiles[] = 'sitemap-categories.xml';

        // 2. Platforms Sitemap
        $platformUrls = [];
        $platformUrls[] = [
            'loc' => $this->baseUrl . '/platforms',
            'priority' => '0.8',
            'changefreq' => 'weekly'
        ];
        $this->collectUrls($platformUrls, 'platforms.json', 'platforms', 'slug', '0.6', 'monthly');
        $this->writeSitemapFile('sitemap-platforms.xml', $platformUrls);
        $sitemapFiles[] = 'sitemap-platforms.xml';

        // 3. Errors Sitemaps (Chunked)
        $errorUrls = [];
        $this->collectUrls($errorUrls, 'errors.json', 'errors', 'slug', '0.9', 'weekly');
        
        $chunks = array_chunk($errorUrls, $this->maxUrlsPerFile);
        foreach ($chunks as $index => $chunk) {
            $filename = 'sitemap-errors-' . ($index + 1) . '.xml';
            $this->writeSitemapFile($filename, $chunk);
            $sitemapFiles[] = $filename;
        }

        // 4. Static/Home Sitemap (Optional, or include in categories?)
        // Let's put home in categories or a separate 'misc' one. 
        // For simplicity, let's add home to categories sitemap or create a core one.
        // The user asked for separate sitemaps for errors, categories, platforms.
        // Let's create a sitemap-core.xml for the homepage.
        $coreUrls = [];
        $coreUrls[] = [
            'loc' => $this->baseUrl . '/',
            'priority' => '1.0',
            'changefreq' => 'daily'
        ];
        $this->writeSitemapFile('sitemap-core.xml', $coreUrls);
        array_unshift($sitemapFiles, 'sitemap-core.xml');

        // Generate Index
        $this->writeSitemapIndex($sitemapFiles);
    }

    private function collectUrls(&$urls, $filename, $prefix, $slugKey, $priority, $changefreq) {
        $file = $this->dataPath . '/' . $filename;
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item[$slugKey])) {
                        $urls[] = [
                            'loc' => $this->baseUrl . "/$prefix/" . $item[$slugKey],
                            'priority' => $priority,
                            'changefreq' => $changefreq
                        ];
                    }
                }
            }
        }
    }

    private function writeSitemapFile($filename, $urls) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';
        
        file_put_contents($this->publicPath . '/' . $filename, $xml);
    }

    private function writeSitemapIndex($files) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($files as $file) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($this->baseUrl . '/' . $file) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . date('c') . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        file_put_contents($this->publicPath . '/sitemap.xml', $xml);
    }
}
