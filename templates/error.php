<?php
require_once __DIR__ . '/../core/affiliates.php';
$affiliateManager = new AffiliateManager();
$categoryName = 'Unknown Category';
foreach ($categories as $cat) {
    if ($cat['slug'] === $error['category']) {
        $categoryName = $cat['name'];
        break;
    }
}

// Helper to allow specific HTML tags while escaping others
function safe_html($text) {
    $escaped = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $allowed_tags = ['strong', 'code', 'em', 'b', 'i'];
    
    foreach ($allowed_tags as $tag) {
        $escaped = str_replace(
            ["&lt;$tag&gt;", "&lt;/$tag&gt;"], 
            ["<$tag>", "</$tag>"], 
            $escaped
        );
    }
    return $escaped;
}

// Logic to prevent duplicate titles (e.g. ERR_TOO_MANY_REDIRECTS ERR_TOO_MANY_REDIRECTS)
$displayTitle = ($error['code'] === $error['title']) ? $error['code'] : $error['code'] . ' ' . $error['title'];

// Use the SEO title passed from controller if available, otherwise fallback
$pageTitle = $seoTitle ?? $displayTitle;
?>
<div class="kb-container">
    <nav class="breadcrumbs">
        <a href="<?php echo $root; ?>/">Home</a> <span>/</span> 
        <a href="<?php echo $root; ?>/categories/<?php echo $error['category']; ?>"><?php echo htmlspecialchars($categoryName); ?></a> <span>/</span> 
        <?php echo htmlspecialchars($displayTitle); ?>
    </nav>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TechArticle",
      "headline": "<?php echo htmlspecialchars($displayTitle); ?>: Comprehensive Fix Guide",
      "description": "<?php echo htmlspecialchars(strip_tags($error['content']['what_it_is'])); ?>",
      "articleSection": "<?php echo htmlspecialchars($categoryName); ?>",
      "proficiencyLevel": "Beginner",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo $seo['canonical']; ?>"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "<?php echo $baseUrl; ?>/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "<?php echo htmlspecialchars($categoryName); ?>",
        "item": "<?php echo $baseUrl; ?>/categories/<?php echo $error['category']; ?>"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "<?php echo htmlspecialchars($displayTitle); ?>"
      }]
    }
    </script>

    <div class="page-layout-sidebar">
        <main>
            <article class="article-card-pro article-card-transparent">
                <div class="error-tag-wrapper">
                    <span class="error-tag"><?php echo htmlspecialchars($error['code']); ?></span>
                </div>
                
                <h1 class="error-title"><?php echo htmlspecialchars($displayTitle); ?> Error</h1>
                
                <div class="error-meta">
                    <span class="meta-label">Category:</span>
                    <a href="<?php echo $root; ?>/categories/<?php echo $error['category']; ?>" class="category-tag"><?php echo htmlspecialchars($categoryName); ?></a>
                    
                    <span class="meta-separator">|</span>
                    
                    <span class="meta-label">Platforms:</span>
                    <?php foreach ($error['platforms'] as $platformId): ?>
                        <a href="<?php echo $root; ?>/platforms/<?php echo $platformId; ?>" class="platform-tag"><?php echo htmlspecialchars($platformId); ?></a>
                    <?php endforeach; ?>
                </div>

                <section class="error-section">
                    <h2 class="section-title">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        What This Error Means
                    </h2>
                    <div class="section-content">
                        <?php echo safe_html($error['content']['what_it_is']); ?>
                    </div>
                </section>

                <section class="error-section">
                    <h2 class="section-title">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Common Causes
                    </h2>
                    <ul class="cause-list">
                        <?php foreach ($error['content']['common_causes'] as $cause): ?>
                            <li class="cause-item">
                                <span class="cause-bullet"></span>
                                <?php echo safe_html($cause); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <div class="fix-box-user">
                    <h2 class="fix-title">How to Fix It (For Users)</h2>
                    <ol class="steps-list-user">
                        <?php foreach ($error['content']['how_to_fix_users'] as $index => $step): ?>
                            <li class="step-item-user">
                                <span class="step-number"><?php echo $index + 1; ?></span>
                                <span class="step-text"><?php echo safe_html($step); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>

                <div class="fix-box-owner">
                    <h2 class="fix-title-owner">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        For Site Owners / Developers
                    </h2>
                    <ol class="steps-list-owner">
                        <?php foreach ($error['content']['how_to_fix_owners'] as $step): ?>
                            <li class="step-item-owner"><?php echo safe_html($step); ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>

                <section class="error-section">
                    <h2 class="section-title">When It Is NOT Your Fault</h2>
                    <p class="not-fault-text"><?php echo safe_html($error['content']['when_not_your_fault']); ?></p>
                </section>

                <?php if (isset($_ENV['RELATED_SITE_URL'])): ?>
                <?php 
                    // Logic to customize CTA based on error category
                    $isBrowserError = ($error['category'] === 'browser-errors');
                    // Stronger CTA for server/network issues
                    $isServerSide = in_array($error['category'], ['dns-errors', 'cloudflare-errors', 'server-errors', 'http-errors', 'ssl-tls-errors']);
                    
                    $ctaTitle = $isServerSide ? "Check if this outage affects everyone globally" : "Is the website actually down?";
                    $ctaDesc = $isServerSide ? "This error often indicates a wider problem. Use our tool to verify if the site is down for everyone." : "Sometimes the issue is just on your end. Check if the website is down for everyone or just you.";
                    
                    // Visual Hierarchy: Primary for Server/Network, Secondary (Outline) for Browser/Client
                    $btnStyle = $isBrowserError ? "background: transparent; border: 1px solid var(--kb-accent); color: var(--kb-accent);" : "background: var(--kb-accent); color: white;";
                    $boxStyle = $isBrowserError ? "background: transparent; border: 1px solid var(--kb-border);" : "background: var(--kb-bg-secondary); border: 1px solid var(--kb-border);";
                ?>
                <div class="tool-recommendation-box" style="margin-bottom: 2rem; padding: 1.5rem; border-radius: var(--kb-radius); <?php echo $boxStyle; ?>">
                    <h3 style="margin-top: 0; font-size: 1.2rem; display: flex; align-items: center; gap: 0.5rem; color: var(--kb-text-primary);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--kb-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        <?php echo $ctaTitle; ?>
                    </h3>
                    <p style="margin-bottom: 1rem; color: var(--kb-text-secondary);"><?php echo $ctaDesc; ?></p>
                    <a href="<?php echo $_ENV['RELATED_SITE_URL']; ?>" target="_blank" rel="noopener noreferrer" class="button-primary" style="display: inline-block; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; font-weight: 500; <?php echo $btnStyle; ?>">Check Website Status &rarr;</a>
                </div>
                <?php endif; ?>

                <?php echo $affiliateManager->render($error); ?>

                <div class="related-grid">
                    <section>
                        <h2 class="section-title">Related Errors</h2>
                        <?php if (!empty($related_errors)): ?>
                            <ul class="related-list">
                                <?php foreach ($related_errors as $rel): ?>
                                    <li class="related-item">
                                        <a href="<?php echo $root; ?>/errors/<?php echo $rel['slug']; ?>" class="related-link">
                                            <span class="related-code"><?php echo htmlspecialchars($rel['code']); ?></span>
                                            <?php echo htmlspecialchars($rel['title']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="section-content">No related errors found.</p>
                        <?php endif; ?>
                    </section>
                </div>
            </article>
        </main>

        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    </div>
</div>
