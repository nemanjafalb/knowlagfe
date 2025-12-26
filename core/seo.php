<?php

class SEO {
    private $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function generate($data, $type) {
        $seo = [
            'title' => '',
            'description' => '',
            'canonical' => ''
        ];

        switch ($type) {
            case 'home':
                $seo['title'] = 'Internet Error Codes Knowledge Base';
                $seo['description'] = 'Comprehensive guide to HTTP error codes, server errors, and platform-specific issues.';
                $seo['canonical'] = $this->baseUrl . '/';
                break;
            case 'error':
                if (isset($data['seo_title'])) {
                    $seo['title'] = $data['seo_title'];
                } else {
                    // Fallback logic
                    $cleanTitle = $data['title'];
                    if (strpos($cleanTitle, $data['code']) === 0) {
                        $cleanTitle = trim(substr($cleanTitle, strlen($data['code'])));
                        $cleanTitle = ltrim($cleanTitle, '-: ');
                    }
                    $seo['title'] = $data['code'] . ' ' . $cleanTitle . ' – Causes & Fix';
                }

                if (isset($data['seo_description']) && !empty($data['seo_description'])) {
                    $seo['description'] = $data['seo_description'];
                } else {
                    $seo['description'] = isset($data['content']['what_it_is']) 
                        ? substr(strip_tags($data['content']['what_it_is']), 0, 160) 
                        : 'Fix ' . $data['code'] . ' error.';
                }
                
                $seo['canonical'] = $this->baseUrl . '/errors/' . $data['slug'];
                break;

            case 'category':
                $seo['title'] = "{$data['name']} Error Codes – Complete List & Fixes";
                $seo['description'] = $data['description'];
                $seo['canonical'] = $this->baseUrl . '/categories/' . $data['slug'];
                break;
            case 'platform':
                $seo['title'] = "{$data['name']} Error Codes – Troubleshooting Guide";
                $seo['description'] = "Common error codes and troubleshooting guides for {$data['name']}.";
                $seo['canonical'] = $this->baseUrl . '/platforms/' . $data['slug'];
                break;
            case 'list_categories':
                $seo['title'] = "All Error Categories – ErrorBase";
                $seo['description'] = "Browse all error code categories to find the solution to your problem.";
                $seo['canonical'] = $this->baseUrl . '/categories';
                break;
            case 'list_platforms':
                $seo['title'] = "Supported Platforms – ErrorBase";
                $seo['description'] = "List of all platforms and technologies covered by ErrorBase.";
                $seo['canonical'] = $this->baseUrl . '/platforms';
                break;
        }

        return $seo;
    }
}