<div class="kb-container category-page-container">
    <div class="page-layout-sidebar">
        <main>
            <h1 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h1>
            <p class="lead category-lead"><?php echo htmlspecialchars($category['intro'] ?? $category['description']); ?></p>

            <div class="seo-text-block" style="margin-bottom: 2.5rem; color: var(--kb-text-secondary); line-height: 1.7; font-size: 1.05rem;">
                <p>
                    Dealing with <strong><?php echo htmlspecialchars($category['name']); ?></strong> can be frustrating, but they are often just the system's way of telling you exactly what went wrong. 
                    <?php if (isset($category['type']) && $category['type'] === 'protocol'): ?>
                        These errors are defined by strict internet standards (RFCs) and usually indicate whether a request was successful, malformed, or rejected by the server. Understanding the specific code (like 4xx vs 5xx) is half the battle.
                    <?php elseif (isset($category['type']) && $category['type'] === 'network'): ?>
                        Network issues often stem from DNS resolution failures, connectivity drops, or firewall blocks. These errors mean the data isn't flowing correctly between your device and the destination server.
                    <?php elseif (isset($category['type']) && $category['type'] === 'security'): ?>
                        Security warnings are critical safeguards. They appear when encryption fails, certificates are invalid, or permissions are denied. Never ignore these, as they often protect your data from being intercepted.
                    <?php else: ?>
                        These errors are specific to the underlying technology or platform. They require a deep understanding of the specific software's configuration and logs.
                    <?php endif; ?>
                    In this section, we break down each error code into simple, actionable steps for both casual users and system administrators.
                </p>
            </div>

            <h2 class="category-subtitle">Errors in this Category</h2>
            <?php if (empty($errors)): ?>
                <p>No errors found in this category.</p>
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
                                <span><?php echo htmlspecialchars($error['type'] ?? 'General'); ?> Error</span>
                                <span>Read more &rarr;</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="seo-outro-block" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--kb-border); color: var(--kb-text-secondary); line-height: 1.7;">
                <h2 style="margin-bottom: 1rem;">Mastering <?php echo htmlspecialchars($category['name']); ?></h2>
                <p style="margin-bottom: 1rem;">
                    To effectively troubleshoot <strong><?php echo htmlspecialchars($category['name']); ?></strong>, it is essential to look beyond the error message itself. 
                    Most of these issues follow a predictable pattern. 
                    <?php if (isset($category['type']) && $category['type'] === 'client'): ?>
                        Since these are client-side errors, the fix is almost always on your end: clearing cache, updating the browser, or checking your internet connection.
                    <?php elseif (isset($category['type']) && $category['type'] === 'infrastructure'): ?>
                        These are server-side issues. As a user, you can rarely fix them directly. As a site owner, you need to check your server logs (error.log), configuration files (.htaccess, nginx.conf), and resource usage.
                    <?php else: ?>
                        Isolating the variable is key. Is it happening on all devices? Is it specific to one browser? Answering these questions will narrow down the root cause.
                    <?php endif; ?>
                </p>
                <p>
                    Our database is constantly updated with the latest solutions and workarounds. By understanding the technical nuance behind each code, you can prevent future occurrences and maintain a healthy, accessible digital environment.
                </p>
            </div>
        </main>
        <aside>
            <?php require __DIR__ . '/../partials/sidebar.php'; ?>
        </aside>
    </div>
</div>