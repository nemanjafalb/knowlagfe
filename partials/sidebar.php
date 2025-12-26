<aside>
    <div class="widget">
        <h3>Categories</h3>
        <ul class="widget-list">
            <?php if (isset($categories) && is_array($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                <li class="widget-item">
                    <a href="<?php echo $root; ?>/categories/<?php echo $cat['slug']; ?>" class="widget-link">
                        <span><?php echo htmlspecialchars($cat['name']); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="widget">
        <h3>Platforms</h3>
        <ul class="widget-list">
            <?php if (isset($platforms) && is_array($platforms)): ?>
                <?php foreach ($platforms as $plat): ?>
                <li class="widget-item">
                    <a href="<?php echo $root; ?>/platforms/<?php echo $plat['slug']; ?>" class="widget-link">
                        <span><?php echo htmlspecialchars($plat['name']); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <?php if (isset($related_errors) && !empty($related_errors)): ?>
    <div class="widget">
        <h3>Related Errors</h3>
        <ul class="widget-list">
            <?php foreach ($related_errors as $rel): ?>
                <li class="widget-item">
                    <a href="<?php echo $root; ?>/errors/<?php echo $rel['slug']; ?>" class="widget-link">
                        <span><?php echo htmlspecialchars($rel['title']); ?></span>
                        <span class="widget-badge"><?php echo htmlspecialchars($rel['code']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</aside>