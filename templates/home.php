<!-- Schema.org -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "ErrorCode",
  "url": "<?php echo $root; ?>/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?php echo $root; ?>/search?q={search_term_string}",
    "query-input": "required name=search_term_string"
  },
  "description": "The comprehensive developer's guide to HTTP status codes, DNS issues, and platform-specific errors."
}
</script>

<!-- Hero Section -->
<section class="hero-section">
    <div class="kb-container">
        <div class="hero">
            <h1><span class="text-gradient">Internet Error Codes</span><br>Knowledge Base</h1>
            <p>The comprehensive developer's guide to HTTP status codes, DNS issues, and platform-specific errors.</p>
            <div class="search-box-wrapper">
                <form action="<?php echo $root; ?>/search" method="GET">
                    <input type="text" name="q" placeholder="Search error codes (e.g. 502, DNS_PROBE...)">
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Authority & Usage Section -->
<section class="authority-section">
    <div class="kb-container">
        <div class="authority-container">
            <h2 class="authority-title">Why ErrorCode Exists</h2>
            <p class="authority-text">
                ErrorCode is built as a technical reference for developers and site owners who need accurate, structured explanations of internet errors. Every page is organized by root cause, not guesses, and follows a consistent troubleshooting framework used in real-world production environments.
            </p>
            
            <div class="authority-features">
                <div class="authority-feature-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--kb-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>Search by error code</span>
                </div>
                <div class="authority-feature-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--kb-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Browse by category</span>
                </div>
                <div class="authority-feature-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--kb-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    <span>Filter by platform</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation Grid -->
<section class="nav-grid-section">
    <div class="kb-container">
        <div class="category-grid">
            <?php 
            $categories = is_array($categories) ? $categories : [];
            foreach ($categories as $category): 
            ?>
                <a href="<?php echo $root; ?>/categories/<?php echo $category['slug']; ?>" class="category-card">
                    <div class="icon-wrapper">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <?php echo $category['icon'] ?? '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>'; ?>
                        </svg>
                    </div>
                    <div class="category-info">
                        <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                        <span>View section &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Value Props -->
<section class="value-props-section">
    <div class="kb-container">
        <div class="value-grid">
            <div class="value-col">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    What Are Internet Error Codes?
                </h3>
                <p>Standardized messages indicating connection failures, server issues, or misconfigurations. We explain HTTP status codes, DNS failures, and platform-specific errors clearly.</p>
            </div>
            <div class="value-col">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    How This Helps You
                </h3>
                <p>Every page follows a consistent structure:</p>
                <ul>
                    <li>Clear, plain-language explanations</li>
                    <li>Common causes (Server, Browser, Network)</li>
                    <li>Step-by-step troubleshooting guides</li>
                </ul>
            </div>
            <div class="value-col">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Who It Is For
                </h3>
                <p>Built for developers, sysadmins, and site owners. Whether debugging a production outage or fixing a personal site, get reliable, structured information fast.</p>
            </div>
        </div>
    </div>
</section>

<!-- Definitions Section -->
<section class="definitions-section">
    <div class="kb-container">
        <div class="section-header">
            <h2>Error Categories Explained</h2>
            <p>Organized into clearly defined categories to make navigation and troubleshooting easier. Common issues such as <strong>502 Bad Gateway</strong>, <strong>DNS_PROBE_FINISHED_NXDOMAIN</strong>, <strong>404 Not Found</strong>, or <strong>SSL Handshake Failed</strong> are documented with clear explanations and step-by-step fixes.</p>
        </div>
        <div class="definitions-grid">
            <div class="def-card">
                <strong>HTTP Errors</strong>
                <p>Standard server response codes (404, 502) indicating configuration or availability issues.</p>
            </div>
            <div class="def-card">
                <strong>DNS Errors</strong>
                <p>Domain name resolution problems where a domain cannot be found or servers fail to respond.</p>
            </div>
            <div class="def-card">
                <strong>SSL / TLS Errors</strong>
                <p>Secure connection failures due to certificate problems, expired credentials, or protocol mismatches.</p>
            </div>
            <div class="def-card">
                <strong>Cloudflare Errors</strong>
                <p>Issues specific to Cloudflare’s network, origin server connectivity, or SSL handshakes.</p>
            </div>
            <div class="def-card">
                <strong>Browser Errors</strong>
                <p>Client-side messages from Chrome/Firefox when connections fail, timeout, or are blocked.</p>
            </div>
            <div class="def-card">
                <strong>Server Errors</strong>
                <p>Issues related to web server software (Apache, Nginx) caused by misconfigurations or backend failures.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer Nav Section -->
<section class="footer-nav-section">
    <div class="kb-container">
        <div class="footer-columns">
            <div>
                <h2>Recent Updates</h2>
                <div class="nav-list">
                    <?php 
                    $recent_errors = is_array($recent_errors) ? $recent_errors : [];
                    // Limit to 3 for this design
                    $recent_errors = array_slice($recent_errors, 0, 3);
                    foreach ($recent_errors as $error): 
                    ?>
                        <a href="<?php echo $root; ?>/errors/<?php echo $error['slug']; ?>" class="nav-item">
                            <div class="nav-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            </div>
                            <div class="nav-content">
                                <span class="nav-title"><?php echo htmlspecialchars($error['code']); ?></span>
                                <span class="nav-meta"><?php echo htmlspecialchars($error['title']); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h2>Popular Categories</h2>
                <div class="nav-list">
                     <?php 
                    // Just show first 3 categories as "Popular"
                    $pop_categories = array_slice($categories, 0, 3);
                    foreach ($pop_categories as $cat): 
                    ?>
                        <a href="<?php echo $root; ?>/categories/<?php echo $cat['slug']; ?>" class="nav-item">
                            <div class="nav-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            </div>
                            <div class="nav-content">
                                <span class="nav-title"><?php echo htmlspecialchars($cat['name']); ?></span>
                                <span class="nav-meta">View Category</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>