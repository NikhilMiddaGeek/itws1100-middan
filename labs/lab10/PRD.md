# Lab 10 PRD - Production Deployment, SSL & Auto-Deploy

**Course:** ITWS 1100 - Intro to IT & Web Science  
**Assignment due date (per instructions):** April 23, 2026 @ 11:59 PM  
**Document purpose:** Define what "done" means for Lab 10 and how it will be validated.

## 1) Summary
Lab 10 is a capstone deployment + operations lab. The student will take their existing course website and:
- Deploy it as a production site reachable at `https://yourFQDN` (no `/iit` in the public URL)
- Add selective access control to specific lab folders using Apache `.htaccess`
- Encrypt the entire site with HTTPS (Let's Encrypt / Certbot)
- Configure continuous deployment so pushes to GitHub `main` automatically update the server

## 2) Goals / Outcomes
- Site is accessible at the root domain and redirects to the course homepage.
- Sensitive labs are password protected, and the UI clearly indicates which labs are protected.
- HTTPS is correctly configured (valid cert + HTTP->HTTPS redirect).
- Auto-deploy is secure (HMAC verification) and reliable (logs + only deploys on `main` pushes).

## 3) Non-Goals (Out of Scope)
- Rebuilding previous labs' content or redesigning the whole site.
- Adding a full authentication system or database-backed user accounts (Apache basic auth is sufficient).
- Multi-environment deployments (staging/production) or complex CI pipelines.

## 4) Users & Use Cases
**Primary user:** Student (site owner/operator)  
**Secondary user:** Instructor/TA (grader/visitor)

Use cases:
- Visitor loads `https://yourFQDN` and is redirected to the homepage without seeing Apache's default page.
- Visitor opens the projects page and can navigate to all labs.
- Visitor clicks a secured lab and is prompted for a password; unsecured labs open normally.
- Student pushes a change to GitHub `main` and sees the server update without manual SSH `git pull`.

## 5) Functional Requirements

### FR-1 Root-domain redirect (production landing page)
- `http://yourFQDN` redirects to the site homepage.
- Redirect is implemented via PHP `header()` in a redirect script (copied to the Apache web root).

### FR-2 Lab 10 redirect artifact in repo
- Repo contains `lab10/index.php` that performs a 302 redirect to `/iit/` using `header("Location: /iit/");` and `exit;`.

### FR-3 Projects page shows all labs from JSON
- Projects page is generated from the JSON dataset established in Lab 8.
- JSON includes entries for Lab 9 and Lab 10.
- Lab 7's entry links to the group project destination per prior course requirements.

### FR-4 Selective folder protection via `.htaccess`
- Root `/iit/` is **not** password protected.
- At minimum, these folders are password protected on the server:
  - `/iit/lab01/`
  - `/iit/lab09/`
- Additional labs may be protected if marked as secure in the JSON dataset.

### FR-5 "secure" flag in JSON + lock indicator in UI
- Every JSON entry includes a boolean `secure` field (`true` or `false`).
- Projects page UI shows a lock indicator (e.g., a lock icon) next to secured labs.
- Secured lab links include a tooltip/title indicating "Password required".

### FR-6 HTTPS (SSL) configuration
- Port `443/tcp` is open to the VM.
- The site has a valid Let's Encrypt certificate for `yourFQDN`.
- `http://yourFQDN` redirects to `https://yourFQDN`.
- Auto-renewal is verified using a Certbot dry run and the `certbot.timer` systemd timer.

### FR-7 Auto-deploy webhook
- A webhook handler exists at `https://yourFQDN/deploy.php`.
- The handler verifies `X-Hub-Signature-256` using HMAC-SHA256 and a shared secret.
- The handler deploys **only** on pushes to `main` by checking the JSON payload `ref` equals `refs/heads/main`.
- On deploy, it runs `git pull origin main` as user `www-data` in `/var/www/html/iit`.
- Every webhook attempt is logged with a timestamp to `/var/log/deploy.log` (success or failure).
- Responses:
  - `403` for invalid signatures
  - `200` for successful deploy
  - `500` for failed deploy attempt

### FR-8 Repository hygiene + Lab 10 README
- Repo does **not** include in-class folders, quiz folders, or `.zip` files as part of the production website.
- `lab10/README.md` exists and includes:
  - The site FQDN
  - The GitHub repository URL

### FR-9 Git workflow requirement
- Work is done via a `lab10` branch and merged into `main`.

## 6) Non-Functional Requirements
- **Security:** Webhook must verify signature before running any deploy action; sudoers permissions follow least privilege (only allow the needed `git pull` for `www-data`).
- **Reliability:** Auto-deploy changes appear on the server within ~10 seconds of pushing to GitHub.
- **Observability:** `/var/log/deploy.log` is used to debug deploy failures and should include both success and failure logs.
- **Privacy:** Password prompts for protected labs must be protected by HTTPS so credentials are not sent in plaintext.

## 7) Acceptance Criteria (Grading-Aligned)

### Redirect & Navigation
- Visiting `http://yourFQDN` (no `/iit`) redirects to the homepage. (10 pts)
- Homepage loads; projects page lists all labs from JSON; Lab 7 link points to the group project. (10 pts)

### Security & UI
- JSON includes `secure` for every lab and lock icons appear on secured entries. (10 pts)
- `lab01` and `lab09` prompt for a password; unsecured labs do not. (15 pts)

### HTTPS
- Browser shows a valid padlock at `https://yourFQDN`; `http://yourFQDN` redirects to `https://`. (20 pts)

### Auto-Deploy
- GitHub webhook is configured and shows successful deliveries.
- Pushing to `main` updates the server automatically without manual `git pull`.
- HMAC signature verification is implemented. (20 pts)

### Repo Process & Cleanup
- No inclass/quiz/zip artifacts remain in the production repo; `lab10/README.md` exists. (10 pts)
- A `lab10` branch was used and merged into `main`. (5 pts)

## 8) Dependencies / Assumptions
- Azure VM is available and running Ubuntu with Apache configured for the student's site.
- Student has a working FQDN pointing to the VM.
- GitHub repo exists and is accessible for webhook configuration.
- `www-data` owns the deployed web directory and has permission to pull from the repo.

## 9) Risks / Common Failure Modes
- Misplaced `.htaccess` causes `500 Internal Server Error` (needs Apache error log debugging).
- Certbot cannot find `ServerName` (requires Apache config fix before cert issuance).
- Webhook signature mismatch or missing header yields `403`.
- Sudoers misconfiguration prevents `www-data` from running `git pull` non-interactively.
- VM stopped for too long may cause certificate renewal to fail.
