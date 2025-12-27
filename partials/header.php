<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($seo)): ?>
        <title><?php echo htmlspecialchars($seo['title']); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($seo['description']); ?>">
        <link rel="canonical" href="<?php echo htmlspecialchars($seo['canonical']); ?>">
    <?php else: ?>
        <title>Error Code Knowledge Base</title>
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo $root; ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        // Theme Logic: Immediate execution to prevent FOUC
        (function() {
            function getTheme() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    return savedTheme;
                }
                // Auto-detect based on time (8am - 8pm is Day/Light)
                const hour = new Date().getHours();
                const isDay = hour >= 8 && hour < 20;
                return isDay ? 'light' : 'dark';
            }

            const theme = getTheme();
            if (theme === 'light') {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body>
    <header>
        <div class="kb-container">
            <a href="<?php echo $root; ?>/" class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>ErrorCode</span>
            </a>
            <nav style="display: flex; align-items: center; gap: 1rem;">
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="<?php echo $root; ?>/">Overview</a></li>
                    <li><a href="<?php echo $root; ?>/categories">Categories</a></li>
                    <li><a href="<?php echo $root; ?>/platforms">Platforms</a></li>
                </ul>
                <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode">
                    <!-- Sun Icon -->
                    <svg id="icon-sun" class="hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <!-- Moon Icon -->
                    <svg id="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <button class="burger-menu" id="burger-menu" aria-label="Toggle Menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </nav>
        </div>
    </header>
    <script>
        // Toggle Logic
        const toggleBtn = document.getElementById('theme-toggle');
        const sunIcon = document.getElementById('icon-sun');
        const moonIcon = document.getElementById('icon-moon');
        const html = document.documentElement;
        const burgerMenu = document.getElementById('burger-menu');
        const navMenu = document.getElementById('nav-menu');

        function updateIcons() {
            const isDark = html.classList.contains('dark');
            if (isDark) {
                sunIcon.style.display = 'block';
                moonIcon.style.display = 'none';
            } else {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'block';
            }
        }

        // Initial Icon State
        updateIcons();

        toggleBtn.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateIcons();
        });

        // Mobile Menu Logic
        burgerMenu.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    </script>
    <main>
