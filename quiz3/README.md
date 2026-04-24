# Quiz 3 — Option B: Site Analytics Dashboard

## Live URL (Azure)
- Dashboard: `https://YOUR-FQDN-HERE/iit/quiz3/dashboard.php`

## What this feature does
- Logs visits to pages on this site into MySQL (write path).
- Shows an analytics dashboard with totals, counts per page, and recent visits (read path).
- Uses JavaScript to:
  - log visits from static HTML pages
  - update the dashboard without full page reload (filters)

## File organization
- `quiz3/dashboard.php` — dashboard UI (protected by `quiz3/.htaccess`)
- `quiz3/index.php` — feature landing page
- `quiz3/api/log_visit.php` — insert visit rows (POST)
- `quiz3/api/summary.php` — summary counts (GET, JSON)
- `quiz3/api/recent.php` — recent visits (GET, JSON)
- `quiz3/inc/db.php` — PDO connection + small helpers
- `quiz3/assets/track.js` — visit logger (used by static site pages)
- `quiz3/assets/analytics.js` — dashboard interactivity
- `quiz3/assets/analytics.css` — styling
- `quiz3/sql/schema.sql` — database schema
- `quiz3/PRD.md` — product requirements document

## MySQL table (required CREATE TABLE)
Run this SQL:

```sql
CREATE TABLE site_visits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_path VARCHAR(255) NOT NULL,
  page_title VARCHAR(255) NULL,
  referrer VARCHAR(255) NULL,
  user_agent VARCHAR(255) NULL,
  ip_hash CHAR(64) NULL,
  visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_visited_at (visited_at),
  INDEX idx_page_path (page_path)
);
```

## Setup (local + Azure)
1. Create the table in your MySQL database (phpMyAdmin or CLI) using `quiz3/sql/schema.sql`.
2. Create `quiz3/config.local.php` (do **not** commit it). Start from `quiz3/config.sample.php` and set:
   - `dsn`
   - `user`
   - `pass`
   - `ip_salt` (random secret string)
3. Confirm Apache is configured to allow `.htaccess` overrides (needs `AllowOverride` enabled).
4. Put your `.htpasswd` file in `/etc/apache2/.htpasswd` on Azure (matches the `AuthUserFile` in `quiz3/.htaccess`).

## “Part of my personal website”
These pages include the tracker and a dashboard link:
- `labs/lab04/homepage_updated.html`
- `labs/lab03/lab03b/homepage.html`
- `labs/lab03/lab03b/lab_landing.html`
- `labs/lab03/lab03b/index.html`

Each page loads:
- The tracker script is included with a relative path to `quiz3/assets/track.js`.

## Technical requirements checklist
- MySQL table designed by me: `site_visits` (see CREATE TABLE above)
- PHP reads + writes:
  - Writes: `quiz3/api/log_visit.php`
  - Reads: `quiz3/api/summary.php`, `quiz3/api/recent.php`
- Prepared statements:
  - All queries using filter values use placeholders and `execute()` parameter arrays
- Client-side interactivity:
  - `quiz3/assets/track.js` logs visits
  - `quiz3/assets/analytics.js` renders tables + applies filters without reload
- Deployed to Azure:
  - Add your FQDN URL at the top of this README
- File organization:
  - Everything for this feature lives under `quiz3/`
