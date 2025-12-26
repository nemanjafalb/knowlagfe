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

    <!-- Client-side Search for Static Deployment -->
    <div id="static-search-results" class="grid category-grid" style="display:none;"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('q');
        
        // Only run if we have a query and no server-side results (or we are in static mode)
        // In static build, $results is empty, so the PHP block above renders nothing or "No results" if we forced a query.
        // But in build_static.php we pass query='', so it renders the empty state.
        
        if (query) {
            // Update input value
            document.querySelector('input[name="q"]').value = query;
            
            fetch('<?php echo $root; ?>/search_index.json')
                .then(response => {
                    if (!response.ok) throw new Error("No search index");
                    return response.json();
                })
                .then(data => {
                    const results = data.filter(item => {
                        const q = query.toLowerCase();
                        return item.title.toLowerCase().includes(q) || 
                               item.code.toLowerCase().includes(q) || 
                               item.desc.toLowerCase().includes(q);
                    });
                    
                    const container = document.getElementById('static-search-results');
                    container.style.display = 'grid';
                    container.innerHTML = '';
                    
                    if (results.length > 0) {
                        const countP = document.createElement('p');
                        countP.className = 'mb-4 search-results-count';
                        countP.textContent = `Found ${results.length} results for "${query}"`;
                        container.parentNode.insertBefore(countP, container);

                        results.forEach(error => {
                            const card = document.createElement('a');
                            card.href = `<?php echo $root; ?>/errors/${error.slug}`;
                            card.className = 'article-card-pro';
                            
                            let html = '';
                            if (error.code !== error.title) {
                                html += `<span class="code">${error.code}</span>`;
                            }
                            html += `<h3>${error.title}</h3>`;
                            html += `<p>${error.desc}...</p>`;
                            html += `<div class="article-meta"><span>Read more &rarr;</span></div>`;
                            
                            card.innerHTML = html;
                            container.appendChild(card);
                        });
                    } else {
                        const p = document.createElement('p');
                        p.textContent = 'No results found.';
                        container.parentNode.insertBefore(p, container);
                    }
                })
                .catch(e => console.log('Static search not available or failed', e));
        }
    });
    </script>
</div>