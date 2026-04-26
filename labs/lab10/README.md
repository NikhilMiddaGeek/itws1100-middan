# Lab 10 - Production Deployment, SSL & Auto-Deploy

## Links (fill these in)
- FQDN: `https://yourFQDN`
- GitHub repo: `https://github.com/<your-username>/<your-repo>`

## What this folder contains
- `index.php`: redirect script used to make `https://yourFQDN` go to `/iit/` (copied to Apache web root in production)
- `deploy.php`: GitHub webhook handler reference implementation (copy to `/var/www/html/deploy.php` on your VM)
- `deploy-test.txt`: a file you can commit/push to verify auto-deploy
- `PRD.md`: requirements + acceptance criteria for Lab 10
- `.htaccess.original`: the original site-wide Basic Auth config (moved here so the whole site is not locked)

## Quick checklist (rubric-aligned)

### Part 1 - Deploy
- Create a `lab10` branch, commit changes, merge to `main`
- Add this folder (`labs/lab10/`) and `labs/lab10/index.php`
- Update the Projects JSON so Lab 9 and Lab 10 appear on the Projects page
- On the VM: replace Apache default landing page so `http://yourFQDN` redirects to your homepage
- Remove non-production artifacts from the deployed repo (inclass/quiz/zip files)

### Part 2 - Security
- Root `/iit/` loads without a password
- At minimum: Lab 01 and Lab 09 require a password (folder-level `.htaccess`)
- In this repo: `labs/lab1/.htaccess` and `labs/lab09/.htaccess` enforce Basic Auth
- JSON includes a boolean `secure` field for every entry
- Projects page shows a lock indicator + "Password required" tooltip for secured labs

### Part 3 - SSL/HTTPS
- Port `443/tcp` open on Azure
- Valid Let's Encrypt certificate installed with Certbot
- `http://yourFQDN` redirects to `https://yourFQDN`
- `sudo certbot renew --dry-run` succeeds; `certbot.timer` is active

### Part 4 - Auto-Deploy
- Webhook URL: `https://yourFQDN/deploy.php`
- `deploy.php` verifies `X-Hub-Signature-256` (HMAC-SHA256) before doing anything
- Only deploy on pushes to `main` (`ref == refs/heads/main`)
- Runs `git pull origin main` as `www-data` in `/var/www/html/iit`
- Logs every attempt to `/var/log/deploy.log`
- Secret: set `DEPLOY_SECRET` in Apache env **or** edit `$secret` in `labs/lab10/deploy.php` after copying it to `/var/www/html/deploy.php`
