<div class="kb-container list-page-container">
    <div class="seo-intro-box" style="margin-bottom: 3rem; max-width: 800px;">
        <h1 class="list-page-title" style="margin-bottom: 1rem;">Supported Platforms & Technologies</h1>
        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--kb-text-secondary);">
            Modern web development relies on a diverse stack of technologies, from robust server software like <strong>Nginx</strong> and <strong>Apache</strong> to dynamic content management systems like <strong>WordPress</strong> and <strong>Shopify</strong>. Each platform speaks its own language when things go wrong, often using unique error codes or specific phrasing that can be baffling to the uninitiated.
        </p>
        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--kb-text-secondary); margin-top: 1rem;">
            This section indexes error codes by the <strong>specific technology or service</strong> that generated them. If you know the source of your problem—whether it's a specific AWS cloud service, a Google Chrome browser crash, a Windows system failure, or a database connection issue—start your search here. We have compiled platform-specific documentation that goes beyond generic advice, offering configuration snippets, log file locations, and dashboard settings unique to each ecosystem.
        </p>
    </div>

    <div class="grid category-grid">
        <?php foreach ($platforms as $platform): ?>
            <a href="<?php echo $root; ?>/platforms/<?php echo $platform['slug']; ?>" class="category-card">
                <div class="icon-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <div class="category-info">
                    <h3><?php echo htmlspecialchars($platform['name']); ?></h3>
                    <span>View section &rarr;</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="seo-outro-box" style="margin-top: 4rem; padding-top: 3rem; border-top: 1px solid var(--kb-border);">
        <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem;">Navigating Platform-Specific Error Architecture</h2>
        <div class="seo-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; color: var(--kb-text-secondary); line-height: 1.7;">
            <div>
                <p>
                    Every software platform handles exceptions differently. A <strong>WordPress</strong> site, for example, is built on PHP and MySQL. When it fails, it might throw a generic "Critical Error" to the user while hiding the true PHP fatal error in a `debug.log` file. Understanding this architecture is key to troubleshooting. Our WordPress section guides you through enabling debugging modes and checking theme conflicts, steps that are irrelevant if you are debugging a static site on <strong>Cloudflare</strong>.
                </p>
                <p>
                    Similarly, <strong>Browser Errors</strong> (like those in Chrome or Firefox) are client-side issues. They often indicate that the request never left your computer or was blocked by a local firewall. In contrast, <strong>Web Server Errors</strong> (Apache/Nginx) prove the request reached the destination but the server couldn't fulfill it. Recognizing which platform is "speaking" helps you determine if you need to fix your internet connection, your computer settings, or the remote server configuration.
                </p>
            </div>
            <div>
                <p>
                    Cloud platforms like <strong>AWS</strong> and <strong>Google Cloud</strong> add another layer of complexity with their own API error codes and permission models. An "Access Denied" on AWS S3 is a very specific permission error involving IAM policies, which is completely different from a "403 Forbidden" on a standard Apache server. Our platform guides dive deep into these nuances, providing CLI commands and console screenshots relevant to the specific environment you are working in.
                </p>
                <p>
                    By organizing errors by platform, we aim to provide a more targeted troubleshooting experience. Instead of wading through irrelevant solutions, you can focus on the tools and settings available to you. Whether you are managing a cPanel hosting account, debugging a React application in the browser, or configuring a Windows server, our platform-specific indexes ensure you have the right map for the territory.
                </p>
            </div>
        </div>
    </div>
</div>