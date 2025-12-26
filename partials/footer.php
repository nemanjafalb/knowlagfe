    </main>
    <footer class="site-footer">
        <div class="kb-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-title">About ErrorBase</h3>
                    <p class="footer-text">
                        Built for developers and sysadmins. We provide accurate, technical explanations for internet error codes, focusing on root cause analysis and platform-specific solutions.
                    </p>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Categories</h3>
                    <ul class="footer-list">
                        <li class="footer-item"><a href="<?php echo $root; ?>/categories/http-errors" class="footer-link">HTTP Errors</a></li>
                        <li class="footer-item"><a href="<?php echo $root; ?>/categories/dns-errors" class="footer-link">DNS Errors</a></li>
                        <li class="footer-item"><a href="<?php echo $root; ?>/categories/ssl-tls-errors" class="footer-link">SSL / TLS Errors</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Platforms</h3>
                    <ul class="footer-list">
                        <li class="footer-item"><a href="<?php echo $root; ?>/platforms/nginx" class="footer-link">Nginx</a></li>
                        <li class="footer-item"><a href="<?php echo $root; ?>/platforms/apache" class="footer-link">Apache</a></li>
                        <li class="footer-item"><a href="<?php echo $root; ?>/platforms/cloudflare" class="footer-link">Cloudflare</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; <?php echo date('Y'); ?> ErrorBase. Built for developers.</p>
                <p class="footer-copyright">Last updated: <?php echo date('F Y'); ?></p>
            </div>
        </div>
    </footer>
</body>
</html>