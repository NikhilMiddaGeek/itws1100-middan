# Quiz 3 — AI Prompt Log (Option B: Site Analytics Dashboard)



---

## Prompt 1 — PRD + requirements mapping
**Prompt :**
You are my ITWS full‑stack TA. Create a PRD for **Quiz 3 Option B: Site Analytics Dashboard** to deploy on Azure (Ubuntu + Apache + PHP + MySQL). The PRD must explicitly map each rubric item:
1) MySQL table I design (include `CREATE TABLE`), 2) PHP reads + writes DB, 3) prepared statements for any user-controlled values, 4) client-side interactivity with JS (not just a static form), 5) deployed at my FQDN URL (include in README), 6) clean IA under `quiz3/`.
Deliver a sequential plan (phases) and a file map under `quiz3/`. Assume many site pages are static `.html`, so logging must still work.
Save as `quiz3/PRD.md`.

**AI returned:**
A PRD with a phased plan, a `site_visits` schema, endpoint list, JS tracker strategy for static pages, deployment checklist, and validation steps.

**Kept / changed / removed (why):**
- **Kept:** Endpoint split (log vs summary vs recent) and the `site_visits` schema because it cleanly satisfies “read + write”.
- **Changed later:** URL/path assumptions after confirming the deployed site is hosted under `/iit/` on the VM.
- **Removed:** Treating phpMyAdmin as required; MySQL CLI works fine and is simpler on this VM.

---

## Prompt 2 — Implement feature under `quiz3/` (end-to-end)
**Prompt :**
Implement the PRD under `quiz3/` with production-ready structure:
- `sql/schema.sql` creating `site_visits`
- `inc/db.php` PDO helper reading `config.local.php` (not committed)
- `api/log_visit.php` (POST) inserts visits (prepared statement)
- `api/summary.php` and `api/recent.php` (GET JSON) for dashboard (prepared statements for filters)
- `dashboard.php` UI + `assets/analytics.js` to fetch/render/filter without full reload
- `assets/track.js` to log visits from static pages via `sendBeacon` (fallback `fetch`)
Integrate: add an Analytics link and include the tracker script on the “latest homepage” + labs/projects navigation pages.

**AI returned (summary):**
Created the `quiz3/` folder with PHP APIs, dashboard UI, tracker JS, CSS, schema SQL, and site integration links/scripts.

**Kept / changed / removed (why):**
- **Kept:** JS tracker approach (works for static pages, minimal site changes).
- **Changed later:** Links/scripts to match `/iit/...` hosting on Azure.
- **Removed:** Any approach that puts DB credentials in the git repo.

---

## Prompt 3 — SSH into Azure VM (beginner workflow)
**Prompt :**
Teach me from zero how to SSH from Windows PowerShell into my Azure Ubuntu VM. Provide:
- exact `ssh username@fqdn` command (avoid defaulting to my Windows username)
- Exaclty what Mysql lines to run in my VM to see the table chagne in my analytics
Summary: 

Step-by-step SSH instructions and troubleshooting tips for login failures and Azure portal password info and provides the MySQL langauge for the table. 

**Kept / changed / removed (why):**
- **Kept:** Always specifying the SSH username explicitly.
- **Changed:** Gave the code for the 


## Prompt 4 — Deploy without merging `quiz3` → `main`
**Prompt :**
I’m not allowed to merge my `quiz3` branch into `main`. On the VM, deploy by keeping `/var/www/html/iit` checked out on branch `quiz3`:
- verify it’s a git repo and has `origin/quiz3`
- `git fetch`, check out the remote branch locally, `git pull`
- do not commit anything on the VM 

**AI returned :**
VM deployment steps using `git fetch`, `git checkout -b quiz3 origin/quiz3`, and `git pull` to keep the server on the quiz branch.

**Kept / changed / removed (why):**
- **Kept:** Branch-based deployment to comply with “no merge”.
- **Changed:** Verified `.git` existed and confirmed the VM was on `main` before switching.
- **Removed:** Committing on the VM 
---

## Prompt 5 — Fix Apache auth blocking API (401)
**Prompt :**
My dashboard JS `fetch()` calls to `/iit/quiz3/api/*.php` are failing with `401 Unauthorized`. Diagnose `.htaccess` scope and inheritance. Provide an exact fix so:
- `dashboard.php` remains protected 
- the API endpoints and the JS tracker can access `/quiz3/api/*` without being blocked
- parent `/var/www/html/iit/.htaccess` is overridden only for `quiz3/api/`
Include a `curl` verification sequence.

**AI returned :**
Adjusted `quiz3/.htaccess` to protect only the dashboard, and added `quiz3/api/.htaccess` 

**Kept / changed / removed (why):**
- **Kept:** Dashboard protection.
- **Changed:** Added an override `.htaccess` in `quiz3/api/` because `/iit/.htaccess` enforced auth globally.
- **Removed:** Blanket auth over the whole `quiz3/` folder (breaks JS API calls).

---

## Prompt 6 — Fix DB errors (500 “DB error”)
**Prompt:**
My API endpoints return `500` with JSON `{"ok":false,"error":"DB error"}`. Provide a minimal debug/fix plan:
1) confirm routing/auth is OK, 2) confirm PHP has `pdo_mysql`, 3) verify MySQL has the `iit` DB and `site_visits` table, 4) verify DB user permissions, 5) check Apache error logs for the real exception.
Then fix: create missing DB/user, and correct `quiz3/config.local.php` formatting/credentials.

**AI returned :**
Created DB/user (`iit`, `iituser`), granted privileges, fixed a malformed `config.local.php`, and restored API responses to 200.

**Kept / changed / removed (why):**
- **Kept:** `config.local.php`  pattern consistent with Lab 9.
- **Changed:** Switched away from `phpmyadmin` user after `ERROR 1698 (28000)` access issues.
- **Removed:** Assumption that the `iit` database already existed.

---

## Prompt 7 — Fix tracker posting to wrong URL (404 under `/iit`)
**Prompt:**
DevTools shows `POST http://<fqdn>/quiz3/api/log_visit.php 404` but my deployed site is served under `/iit/`. Update `assets/track.js` to correct base path:
- prefer deriving from the tracker `<script src="...">` path
- fallback if hosted under `/iit/`


**AI returned :**
Updated tracker base-path logic so it posts to `/iit/quiz3/api/log_visit.php` and eliminated the 404 logging errors.

**Kept / changed / removed (why):**
- **Kept:** `sendBeacon` first, `fetch` fallback.
- **Changed:** Base-path computation to support subpath hosting.
- **Removed:** Root-only hardcoded URLs.

---

## Prompt 8 — Dashboard shows 0 while DB/API show 20 (filters/autofill)
**Prompt (engineered):**
My DB count and unfiltered API show `total_visits=20`, but the dashboard shows 0. Provide a debugging workflow:
- inspect the Network request URL for `api/summary.php` and confirm whether `start/end` filters are being sent
- compare the filtered response vs unfiltered response
- identify browser autofill as a cause of unintended `start/end` values
- provide a UX mitigation 

**AI returned :**
It identified the dashboard was calling `summary.php` with `start/end` query params  and fixed it by clearing Start/End so the request is unfiltered.

**Kept / changed / removed (why):**
- **Kept:** Filter support 
- **Changed:** Dashboard usage: clear Start/End to see all-time totals.
- **Removed:** Assuming dashboard totals must equal DB totals without checking active filters.

