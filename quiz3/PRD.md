# Quiz 3 — Option B: Site Analytics Dashboard (PHP + MySQL + JS)

## 1) Overview
Build a site analytics feature for my personal website that:
- Logs page visits into MySQL (write)
- Displays an analytics dashboard with summaries and recent visits (read)
- Uses client-side JavaScript to make the dashboard interactive
- Is deployed to Azure and accessible at my FQDN

This work lives in `quiz3/` and is linked from the “latest homepage” (`labs/lab04/homepage_updated.html`).

## 2) Goals (What “done” means)
- Track visits across my site (at least homepage + labs landing pages; optionally all pages).
- Show a dashboard page with:
  - Total visits
  - Visits per page (counts)
  - Recent visits list (newest first)
  - Optional filters (date range, page) without full page reload
- Meet **all** technical requirements:
  1. At least one MySQL table I designed (include `CREATE TABLE`)
  2. PHP reads **and** writes to the database
  3. Prepared statements for all queries involving user-controlled values
  4. Client-side interactivity via JavaScript (not just a static form)
  5. Deployed to Azure with working FQDN URL (documented)
  6. Proper file organization: `quiz3/` with clean IA

## 3) Non-goals (to stay in scope)
- No full “Google Analytics”-level tracking (no cookies-based cross-session identity, no heatmaps).
- No storing sensitive personal data beyond what’s needed (minimize IP usage; prefer hashing).
- No admin accounts system beyond simple protection (HTTP Basic auth is fine).

## 4) Users & User Stories
- **Visitor (anonymous)**: I visit a page → the visit is logged automatically.
- **Site owner (me)**: I open the analytics dashboard → I see which pages are being viewed and when.
- **Site owner (me)**: I filter for a date range or specific page → results update immediately without a full reload.

## 5) Information Architecture (IA) / URL Map
All new work is inside `quiz3/`:
- `quiz3/index.php` — landing page (short description + link to dashboard)
- `quiz3/dashboard.php` — analytics dashboard UI (protected)
- `quiz3/api/log_visit.php` — endpoint to record a visit (called from JS on site pages)
- `quiz3/api/summary.php` — returns summary counts (JSON)
- `quiz3/api/recent.php` — returns recent visits (JSON)
- `quiz3/inc/db.php` — shared DB connector helper (PDO)
- `quiz3/inc/auth.php` — optional auth gate for dashboard (or `.htaccess`)
- `quiz3/assets/analytics.js` — dashboard JS (fetch + render + filters)
- `quiz3/assets/analytics.css` — dashboard styling
- `quiz3/README.md` — deployment URL + setup steps (final deliverable support)

Homepage integration:
- Add a link on `labs/lab04/homepage_updated.html` (and/or `labs/lab03/lab03b/lab_landing.html`) pointing to `../../quiz3/dashboard.php` (path adjusted based on file location).

## 6) Data Model (MySQL)
### Table: `site_visits`
Purpose: store one row per logged page view.

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

Notes:
- `page_path` is the relative path like `/labs/lab04/homepage_updated.html` or `/quiz3/dashboard.php`.
- `ip_hash` is a SHA-256 hash of the IP (privacy-friendly) rather than plain IP.
- Indexes support “recent visits” and “counts per page” queries.

## 7) Logging Strategy (How visits get recorded)
Because many of my site pages are static `.html`, logging must work even when PHP is not generating the page.

Plan:
1. Add a small JavaScript snippet to chosen pages (homepage + labs landing + projects landing) that calls `quiz3/api/log_visit.php` on page load.
2. Use `navigator.sendBeacon()` when possible (best for analytics) and fall back to `fetch()`.
3. `log_visit.php` validates/sanitizes input, then inserts the visit row into MySQL using a prepared statement.

Data sent from browser → server:
- `page_path` (from `location.pathname`)
- `page_title` (from `document.title`)
- `referrer` (from `document.referrer`)
- `user_agent` can be derived server-side from request headers (no need to trust client)

Security & integrity considerations:
- Treat `page_path`, `page_title`, and `referrer` as user-controlled input.
- Limit max lengths and strip unexpected characters to reduce spam/abuse.

## 8) Server-Side (PHP) Requirements & Implementation Plan

### 8.1 Database connection helper (`quiz3/inc/db.php`)
Main steps:
1. Create `quiz3/config.sample.php` with placeholder DSN/user/pass.
2. Create `quiz3/config.local.php` on Azure (not committed) with real credentials.
3. Implement a reusable PDO connection function with exception error mode.

Acceptance check:
- Any PHP file can call the helper and run a query.

### 8.2 Write path: `quiz3/api/log_visit.php`
Main steps:
1. Read inputs (`page_path`, `page_title`, `referrer`) from the request body (POST).
2. Sanitize and enforce length limits.
3. Compute `ip_hash` from the client IP (server side).
4. Insert row with a prepared statement (placeholders for all values):
   - Insert pattern: “insert into `site_visits` with placeholders → execute with a values map”.
5. Return JSON `{ ok: true }`.

Acceptance check:
- Refreshing a tracked page increases total visits in the database.

### 8.3 Read path: dashboard APIs (`quiz3/api/summary.php`, `quiz3/api/recent.php`)
Main steps:
1. Support optional filters from query parameters:
   - `start` (date/time)
   - `end` (date/time)
   - `page_path` (optional)
2. Use prepared statements for any query with these filter values.
3. Summary endpoint returns counts per page + total.
4. Recent endpoint returns last N visits (newest first), with safe output escaping.

Acceptance check:
- Dashboard loads summary and recent visits successfully.
- Filters change results without reloading the whole page.

### 8.4 Dashboard page (`quiz3/dashboard.php`)
Main steps:
1. Protect access (choose one):
   - **Preferred**: `.htaccess` Basic Auth for `quiz3/dashboard.php` (or whole `quiz3/`), with `.htpasswd` stored outside web root
   - Alternative: small PHP auth check (less ideal for this class)
2. Render base HTML shell (header, filter UI, empty containers for charts/tables).
3. Include `assets/analytics.js` to fetch and render data.

Acceptance check:
- Dashboard loads only for authenticated users (if protected).
- Without JS, page still loads (but shows minimal content / message).

## 9) Client-Side (JavaScript) Interactivity

### 9.1 Tracking snippet (for non-PHP pages)
Main steps:
1. Add a `<script>` tag on selected site pages that posts to `quiz3/api/log_visit.php`.
2. Prefer `sendBeacon` so it logs even when the user navigates away quickly.

Acceptance check:
- Visits to static pages get logged consistently.

### 9.2 Dashboard interactivity (`quiz3/assets/analytics.js`)
Main steps:
1. On dashboard load, request summary + recent visits via `fetch`.
2. Render:
   - A “total visits” number
   - A table of pages and counts
   - A recent visits table (timestamp, page, referrer)
3. Add filters:
   - Date range inputs + “Apply” button
   - Optional “page” dropdown populated from summary results
4. Update the tables dynamically when filters are applied (no full page reload).

Acceptance check:
- Filters update results immediately.

## 10) Deployment Plan (Azure)
Main steps:
1. Create the MySQL table(s) in Azure’s MySQL instance (or the course-provided DB).
2. Add `quiz3/config.local.php` on the server with DB credentials.
3. Ensure `quiz3/` is reachable at:
   - `https://<my-fqdn>/quiz3/dashboard.php`
4. Confirm `.htaccess` rules work (requires Apache to allow overrides via `AllowOverride`).

Acceptance check:
- Feature works at the live URL on Azure.
- Logging works on Azure (rows appear in DB).

## 11) Validation & Testing Checklist
- [ ] Visit homepage → new row appears in `site_visits`
- [ ] Visit labs landing page → new row appears
- [ ] Dashboard shows total visits and per-page counts
- [ ] “Recent visits” is newest-first
- [ ] Filters use prepared statements and work correctly
- [ ] Dashboard access is protected (if required/desired)
- [ ] README includes FQDN URL and setup notes

## 12) Deliverables (for grading)
- Working deployed feature meeting all 6 technical requirements
- `quiz3/PRD.md` (this document)
- `quiz3/README.md` with:
  - Live URL at my FQDN
  - Table `CREATE TABLE` statement(s)
  - Brief setup instructions (DB credentials file, how to test)

