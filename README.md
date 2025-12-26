# ErrorBase - Programmatic SEO Knowledge Base

A high-performance, automated Knowledge Base for error codes, built with PHP and powered by Google Gemini AI.

## 🚀 Features

- **Automated Content Generation**: Uses Gemini AI to discover and write technical error documentation.
- **Smart Categorization**: Automatically creates and normalizes categories (e.g., `wordpress-errors`, `ios-errors`).
- **SEO Optimized**:
  - Dynamic Title Tags & Meta Descriptions.
  - Schema.org `TechArticle` markup.
  - Automatic Sitemap generation (`sitemap.xml`).
  - Canonical URLs.
- **Performance**: Flat-file JSON database (no MySQL required) for blazing fast reads.
- **Partner Integration**: "Is Your Website Down" tool embedded in sidebar.

## 📋 Requirements

- **PHP 7.4+** (PHP 8.0+ recommended)
- **cURL Extension** enabled
- **Write Permissions** for the `data/` folder
- **Google Gemini API Key**

## 🛠️ Production Setup (errorcode.help)

1.  **Upload Files**:
    Upload the entire project folder to your web server (e.g., `public_html`).

2.  **Set Permissions**:
    Ensure the `data/` directory and its contents are writable by the web server.
    ```bash
    chmod -R 755 data/
    chmod 644 data/*.json
    ```

3.  **Configure Environment**:
    Create or edit `.env` in the root directory:
    ```ini
    GEMINI_API_KEY=your_actual_api_key_here
    APP_URL=https://errorcode.help
    DAILY_LIMIT=50
    RELATED_SITE_URL=https://isyourwebsitedownrightnow.com/
    ```

## 🤖 Automation (Cron Job)

To keep the site alive and growing, you need a Cron Job. Since you are using Cloudflare, it is best to run the script **internally** on your server (CLI) to avoid timeout issues.

### cPanel / Linux Server (Recommended)

1.  Log in to cPanel.
2.  Go to **Cron Jobs**.
3.  Add a new Cron Job:
    *   **Common Settings**: Once per hour (or Twice per day).
    *   **Command**:
        ```bash
        /usr/local/bin/php /home/YOUR_USERNAME/public_html/scripts/generate_content.php >> /home/YOUR_USERNAME/public_html/scripts/generate.log 2>&1
        ```
    *(Note: Replace `/usr/local/bin/php` with your server's PHP path and `YOUR_USERNAME` with your actual hosting username).*

### Why CLI?
Running via CLI bypasses Cloudflare's timeout limits (100s) and PHP's `max_execution_time` for web requests. It is the most stable way to generate content.

## 🔍 SEO & Content Strategy

- **Trust Signals**: Categories and Platforms pages now include rich, expert-written intro and outro text to boost topical authority.
- **Interlinking**: The "Is It Down" tool is contextually linked based on error type (Server vs Browser).
- **Schema**: Full `TechArticle` and `BreadcrumbList` implementation with absolute URLs.
- **Sitemap**: Automatically regenerates at `https://errorcode.help/sitemap.xml` after every batch.

## 📂 Folder Structure
Command:
```bash
/usr/local/bin/php /home/username/public_html/scripts/generate_content.php >> /home/username/public_html/scripts/cron.log 2>&1
```
*(Note: Check your hosting provider for the exact path to PHP)*

### Windows (Task Scheduler)
1.  Create a `.bat` file (e.g., `run_gen.bat`):
    ```cmd
    C:\path\to\php.exe C:\path\to\htdocs\scripts\generate_content.php
    ```
2.  Open Task Scheduler -> Create Basic Task.
3.  Trigger: Daily -> Repeat task every 1 hour.
4.  Action: Start a program -> Select your `.bat` file.

## 📂 Project Structure

```
/
├── core/               # Core logic (Router, SEO, Sitemap)
├── data/               # JSON Databases (errors, categories, platforms)
├── partials/           # Header, Footer, Sidebar
├── scripts/            # Automation scripts
│   └── generate_content.php  # The AI Engine
├── templates/          # Frontend views
├── assets/             # CSS, JS, Images
├── index.php           # Entry point
└── .env                # Configuration
```

## 🔍 Monitoring & Troubleshooting

- **Check Generated Content**: Look at `data/errors.json` to see new entries.
- **Check Sitemap**: Visit `yourdomain.com/sitemap.xml` to verify new pages are indexed.
- **Logs**: If using the Cron command above, check `scripts/cron.log` for errors.
- **Common Issues**:
  - *Permissions*: If `errors.json` isn't updating, check folder permissions.
  - *API Limits*: If the log shows 429 errors, increase the interval between Cron runs.

## 🛡️ Production Checklist

- [ ] `.env` file is created and contains valid API Key.
- [ ] `APP_URL` in `.env` matches your live domain.
- [ ] `data/` folder is writable.
- [ ] Cron Job is active.
- [ ] "Is Your Website Down" tool is visible on error pages.

---
*Generated for ErrorBase Production Deployment*
