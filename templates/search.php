<div class="kb-container search-page-container">
    <h1 class="search-title">Search Results</h1>
    
    <div class="search-box-wrapper mb-8">
        <form action="<?php echo $root; ?>/search" method="GET">
            <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search for an error code..." class="search-input-field">
        </form>
    </div>

    <?php if ($query): ?>
        <p class="mb-4 search-results-count">Found <?php echo count($results); ?> results for "<?php echo htmlspecialchars($query); ?>"</p>
    <?php endif; ?>

    <?php if (empty($results)): ?>
        <?php if ($query): ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php else: ?>
        <div class="grid category-grid">
            <?php foreach ($results as $error): ?>
                <a href="<?php echo $root; ?>/errors/<?php echo $error['slug']; ?>" class="article-card-pro">
                    <?php if ($error['code'] !== $error['title']): ?>
                        <span class="code"><?php echo htmlspecialchars($error['code']); ?></span>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($error['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr(strip_tags($error['content']['what_it_is'] ?? ''), 0, 100)) . '...'; ?></p>
                    <div class="article-meta">
                        <span><?php echo htmlspecialchars($error['type']); ?> Error</span>
                        <span>Read more &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>