<div class="kb-container list-page-container">
    <div class="seo-intro-box" style="margin-bottom: 3rem; max-width: 800px;">
        <h1 class="list-page-title" style="margin-bottom: 1rem;">Browse Error Categories</h1>
        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--kb-text-secondary);">
            Welcome to the comprehensive Error Code Database, meticulously organized to help you identify and resolve digital obstacles instantly. Whether you are a developer debugging a complex server configuration, a website owner facing unexpected downtime, or an everyday user trying to access a favorite webpage, understanding the <strong>type of error</strong> is the critical first step toward a solution.
        </p>
        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--kb-text-secondary); margin-top: 1rem;">
            Our taxonomy is strictly curated to separate <strong>Protocol Errors</strong> (like standard HTTP status codes), <strong>Network Issues</strong> (DNS resolution, connectivity drops), <strong>Security Warnings</strong> (SSL/TLS handshake failures), and <strong>Platform-Specific Quirks</strong>. By categorizing errors logically, we ensure you don't just find a code—you find a context. Browse the categories below to locate the specific troubleshooting guide you need. Each section contains detailed, expert-verified solutions tailored for both technical and non-technical users.
        </p>
    </div>

    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
            <a href="<?php echo $root; ?>/categories/<?php echo $category['slug']; ?>" class="category-card">
                <div class="icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?php echo $category['icon'] ?? '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>'; ?>
                    </svg>
                </div>
                <div class="category-info">
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p class="list-card-description">
                        <?php echo htmlspecialchars($category['description'] ?? ''); ?>
                    </p>
                    <span>View section &rarr;</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="seo-outro-box" style="margin-top: 4rem; padding-top: 3rem; border-top: 1px solid var(--kb-border);">
        <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem;">Why Accurate Error Categorization Matters</h2>
        <div class="seo-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; color: var(--kb-text-secondary); line-height: 1.7;">
            <div>
                <p>
                    In the complex ecosystem of the internet, an "error" is rarely just a stop sign; it is a diagnostic message. However, these messages can be cryptic. Distinguishing between a <strong>Client-Side Error</strong> (where the browser or user request is at fault) and a <strong>Server-Side Error</strong> (where the infrastructure failed) saves hours of troubleshooting time. For instance, treating a 500 Internal Server Error like a 404 Not Found will lead to frustration, as clearing your browser cache will never fix a database syntax error on the server.
                </p>
                <p>
                    Our database prioritizes this distinction. By grouping <strong>HTTP Errors</strong> separately from <strong>DNS Errors</strong>, we help you isolate the layer of the OSI model where the failure occurred. If you are experiencing a DNS_PROBE_FINISHED_NXDOMAIN, you know immediately it is a naming resolution issue, not a server crash. This structural approach is designed to educate users while solving their immediate problem, building a deeper understanding of web technologies.
                </p>
            </div>
            <div>
                <p>
                    Furthermore, the rise of secure web protocols has introduced a new layer of complexity: <strong>Security Errors</strong>. Issues like NET::ERR_CERT_AUTHORITY_INVALID are not technical failures in the traditional sense but safety mechanisms protecting user data. Categorizing these separately allows us to provide specific advice on certificates, encryption, and trust chains, which is fundamentally different from fixing a broken link or a timed-out connection.
                </p>
                <p>
                    Finally, <strong>Platform-Specific Errors</strong> address the walled gardens of modern tech. A "White Screen of Death" is iconic to WordPress, while a "522 Connection Timed Out" is a hallmark of Cloudflare's reverse proxy. By recognizing these platform signatures, we can offer solutions that involve specific configuration files (like wp-config.php) or dashboard settings, rather than generic advice that doesn't apply. Explore our categories to find the exact match for your situation.
                </p>
            </div>
        </div>
    </div>
</div>