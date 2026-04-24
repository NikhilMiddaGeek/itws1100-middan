# Quiz 3 — Section 3: Site Analytics Dashboard (Option B)

## Student Info (required)
- GitHub ID: `NikhilMiddaGeek`
- Repo name: `itws-1100`
- Azure homepage link: `http://middanrpi1.eastus.cloudapp.azure.com/iit/labs/lab04/homepage_updated.html`
- Discord handle: `nm117`

## Live Links (Azure)
- Analytics dashboard: `http://middanrpi1.eastus.cloudapp.azure.com/iit/quiz3/dashboard.php`
-  **IMPORTANT**: CHANGE END DATE TO CLEAR TO SEE ACTVITY

## What this feature does
- Logs visits to pages on this site into MySQL 
- Shows an analytics dashboard with totals, counts per page, and recent visits 
- Uses JavaScript to:
  - log visits from static HTML pages
  - update the dashboard without full page reload 

## Section 3 summary (what I built)
For Section 3, I built a full-stack analytics feature that records page views into a MySQL table (`site_visits`) using a PHP write endpoint (`quiz3/api/log_visit.php`) and displays results on a protected dashboard (`quiz3/dashboard.php`). Static pages log visits a small client-side tracker (`quiz3/assets/track.js`), and the dashboard uses JavaScript (`quiz3/assets/analytics.js`) to fetch summary and recent visit JSON from PHP read endpoints and render it. 

