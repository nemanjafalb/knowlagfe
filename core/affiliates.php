<?php

class AffiliateManager {
    private $affiliates = [];

    public function __construct() {
        // Load from environment variables (assuming they are already loaded by index.php)
        $this->affiliates = [
            'cloudflare' => getenv('AFFILIATE_CLOUDFLARE') ?: 'https://www.cloudflare.com/',
            'hosting' => getenv('AFFILIATE_HOSTING') ?: 'https://www.digitalocean.com/',
            'security' => getenv('AFFILIATE_SECURITY') ?: 'https://uptimerobot.com/'
        ];
    }

    public function render($error) {
        // 1. Check if browser error -> DO NOT SHOW
        if ($error['category'] === 'browser-errors') {
            return '';
        }

        // 2. Determine Affiliate Type
        $type = 'hosting'; // Default
        $text = 'Reliable Hosting Provider';
        $desc = 'Ensure your server is always online and fast with high-performance cloud hosting.';
        $link = $this->affiliates['hosting'];

        if ($error['category'] === 'cloudflare-errors') {
            $type = 'cloudflare';
            $text = 'Cloudflare DNS & Security';
            $desc = 'Fix DNS resolution issues and protect your site from DDoS attacks with Cloudflare.';
            $link = $this->affiliates['cloudflare'];
        } elseif ($error['category'] === 'ssl-tls-errors') {
            // Randomly choose between Cloudflare (SSL) or Security (Monitoring)
            if (rand(0, 1) === 0) {
                $type = 'cloudflare';
                $text = 'Cloudflare Universal SSL';
                $desc = 'Get free, automatic SSL certificates and fix handshake errors instantly.';
                $link = $this->affiliates['cloudflare'];
            } else {
                $type = 'security';
                $text = 'Uptime & SSL Monitoring';
                $desc = 'Get alerted immediately when your SSL certificate expires or your site goes down.';
                $link = $this->affiliates['security'];
            }
        } elseif (in_array($error['category'], ['http-errors', 'platform-specific-errors'])) {
            $type = 'hosting';
            $text = 'High-Performance Cloud Hosting';
            $desc = 'Prevent server timeouts and resource exhaustion with scalable cloud infrastructure.';
            $link = $this->affiliates['hosting'];
        }

        // 3. Render Component
        // Using CSS classes from style.css
        ob_start();
        ?>
        <div class="affiliate-box">
            <div class="affiliate-content">
                <h3 class="affiliate-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="affiliate-icon"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    Recommended Tool to Prevent This Error
                </h3>
                <p class="affiliate-desc">
                    <?php echo htmlspecialchars($desc); ?>
                </p>
            </div>
            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" rel="nofollow sponsored" class="affiliate-btn">
                <?php echo htmlspecialchars($text); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}
