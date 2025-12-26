<?php
$catMap = [];
if (isset($categories)) {
    foreach ($categories as $c) {
        $catMap[$c['slug']] = $c['name'];
    }
}
?>
<div class="kb-container">
    <nav class="breadcrumbs">
        <a href="<?php echo $root; ?>/">Home</a> <span>/</span> 
        <a href="<?php echo $root; ?>/platforms">Platforms</a> <span>/</span> 
        <?php echo htmlspecialchars($platform['name']); ?>
    </nav>

    <div class="page-layout-sidebar">
        <main>
            <div class="hero platform-hero">
                <h1 class="platform-title"><?php echo htmlspecialchars($platform['name']); ?></h1>
                <p class="lead platform-lead"><?php echo htmlspecialchars($platform['description']); ?></p>
            </div>

            <div class="seo-text-block" style="margin-bottom: 2.5rem; color: var(--kb-text-secondary); line-height: 1.7; font-size: 1.05rem;">
                <p>
                    <strong><?php echo htmlspecialchars($platform['name']); ?></strong> powers a significant portion of the web ecosystem, but like any complex technology, it has its own unique set of challenges. 
                    When you encounter an error on this platform, it is rarely a random event. It is usually a specific response to a misconfiguration, a resource limit, or a compatibility issue.
                    Our documentation for <?php echo htmlspecialchars($platform['name']); ?> is designed to cut through the noise. We don't just list the error codes; we explain the architecture behind them, helping you understand <em>why</em> the platform is rejecting your request or failing to load.
                </p>
            </div>

            <h2 class="platform-subtitle">Common Errors</h2>
            
            <?php if (empty($errors)): ?>
                <p>No errors found for this platform.</p>
            <?php else: ?>
                <div class="category-grid">
                    <?php foreach ($errors as $error): ?>
                        <a href="<?php echo $root; ?>/errors/<?php echo $error['slug']; ?>" class="article-card-pro">
                            <?php if ($error['code'] !== $error['title']): ?>
                                <span class="code"><?php echo htmlspecialchars($error['code']); ?></span>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($error['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr(strip_tags($error['content']['what_it_is'] ?? ''), 0, 100)) . '...'; ?></p>
                            <div class="article-meta">
                                <span><?php echo htmlspecialchars($catMap[$error['category']] ?? $error['category']); ?></span>
                                <span>Read more &rarr;</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="seo-outro-block" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--kb-border); color: var(--kb-text-secondary); line-height: 1.7;">
                <h2 style="margin-bottom: 1rem;">Best Practices for <?php echo htmlspecialchars($platform['name']); ?> Stability</h2>
                <p style="margin-bottom: 1rem;">
                    Maintaining a stable <strong><?php echo htmlspecialchars($platform['name']); ?></strong> environment requires proactive monitoring. 
                    Many of the errors listed above can be prevented by regular updates, proper caching configurations, and strict permission management.
                    If you are a developer, ensure you are checking the platform's specific error logs, which often contain more detail than the public-facing error message.
                </p>
                <p>
                    Whether you are dealing with API rate limits, server timeouts, or authentication failures, the solutions provided here are tested and verified. 
                    Bookmark this page to have a quick reference guide whenever <?php echo htmlspecialchars($platform['name']); ?> throws an unexpected exception.
                </p>
            </div>
        </main>

        <aside>
            <?php require __DIR__ . '/../partials/sidebar.php'; ?>
        </aside>
    </div>
</div>