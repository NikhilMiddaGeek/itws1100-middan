# D3 — Code Walkthrough (Quiz 3 Option B)

## 1) Database schema (3 pts)
I created one table: `site_visits` (see `quiz3/sql/schema.sql`).

- `id` (INT, auto-increment): unique row id for each visit.
- `page_path` (VARCHAR(255), NOT NULL): the URL path that was visited (example: `/iit/labs/lab04/homepage_updated.html`). Stored as text because paths aren’t numeric.
- `page_title` (VARCHAR(255), NULL): the page’s `<title>` (optional).
- `referrer` (VARCHAR(255), NULL): where the visitor came from (optional).
- `user_agent` (VARCHAR(255), NULL): browser info from the request header (optional).
- `ip_hash` (CHAR(64), NULL): SHA-256 hash of the visitor IP (privacy-friendly; fixed length = 64 hex chars).
- `visited_at` (DATETIME, default current timestamp): when the visit was logged.
- Indexes:
  - `idx_visited_at` speeds up “recent visits” queries.
  - `idx_page_path` speeds up “count by page” queries.

## 2) PHP “write” path (3 pts)
User action: a visitor loads a page that includes the tracker script (`quiz3/assets/track.js`). That script sends a POST request to `quiz3/api/log_visit.php`.

In `quiz3/api/log_visit.php`:
1. It requires `quiz3/inc/db.php` so it can connect to MySQL with PDO.
2. It checks the request is `POST` (rejects other methods).
3. It reads the submitted data (JSON body first, then form-style POST fields as a fallback):
   - `page_path`, `page_title`, `referrer`
4. It sanitizes/clamps strings to safe lengths and normalizes `page_path` to a clean path.
5. It computes `ip_hash` from the request IP plus the secret salt in `quiz3/config.local.php`.
6. It inserts the row with a prepared statement:
   - SQL uses placeholders (`:page_path`, `:page_title`, etc.)
   - `execute([...])` binds real values separately so user input can’t break the SQL.
7. It returns JSON `{ "ok": true }` (or an error JSON if something fails).

Why prepared statements matter: inputs like `page_path` come from the browser (user-controlled). Using placeholders prevents SQL injection because the database treats the inputs as data, not executable SQL.

## 3) PHP “read” path (3 pts)
The dashboard page (`quiz3/dashboard.php`) loads JavaScript that requests summary data from:
- `quiz3/api/summary.php` (totals + counts per page)
- `quiz3/api/recent.php` (newest visits first)

In `quiz3/api/summary.php` and `quiz3/api/recent.php`:
1. They accept optional filter values from query parameters:
   - `start`, `end`, `page_path`
2. They build a `WHERE` clause only for filters that are present, and bind those values with a prepared statement (no string-concatenated user input).
3. They run SELECT queries using PDO:
   - `summary.php` computes total visit count and a grouped “visits per page” list.
   - `recent.php` returns the most recent rows ordered by time.
4. They output JSON that the browser can render.

## 4) Client-side JavaScript (3 pts)
There are two main JS files:

- `quiz3/assets/track.js` (logging):
  - Runs on page load.
  - Collects `location.pathname`, `document.title`, and `document.referrer`.
  - Sends a POST to `quiz3/api/log_visit.php` using `navigator.sendBeacon()` (fallback `fetch()`).

- `quiz3/assets/analytics.js` (dashboard UI):
  - Calls `fetch()` to load JSON from `api/summary.php` and `api/recent.php`.
  - Updates the page by filling the “total visits” number and generating table rows from the returned JSON.
  - When the user clicks **Apply**, it re-fetches with any chosen filters and re-renders without a full page reload.

