# D4 — “Break It” Exercise

Important: I did **not** change my live endpoints. The vulnerable examples below are shown as a **copy** for demonstration only.

---

## Vulnerability 1 — SQL Injection (4 pts)

### A) The vulnerable version (string concatenation)
Example: a vulnerable rewrite of the **page filter** query in `quiz3/api/summary.php` (do **not** deploy this):

```php
// BAD: vulnerable to SQL injection
$pagePath = $_GET['page_path'] ?? '';
$sql = "SELECT page_path, COUNT(*) AS visits
        FROM site_visits
        WHERE page_path = '" . $pagePath . "'
        GROUP BY page_path
        ORDER BY visits DESC";
$rows = db()->query($sql)->fetchAll();
```

### B) A malicious input that exploits it
If an attacker controls `page_path`, they could use an input like:

```
' OR '1'='1' --
```

Example request:

```
iit/quiz3/api/summary.php?page_path=%27%20OR%20%271%27%3D%271%27%20--%20
```

### C) What would happen to the database / results
That input changes the meaning of the SQL from “only this one page” to “**every row matches**”, so the filter is bypassed and the endpoint returns counts for pages the attacker didn’t request legitimately.

In more dangerous endpoints (INSERT/UPDATE/DELETE built with concatenation), SQL injection can also allow attackers to **modify or delete data**.

### D) The original safe code (prepared statement) + why it’s safe
My safe pattern (from `quiz3/api/summary.php`) uses placeholders and binds the value separately:

```php
$where[] = 'page_path = :page_path';
$params['page_path'] = $pagePath;

$stmtPages = db()->prepare(
  "SELECT page_path, COUNT(*) AS visits
   FROM site_visits
   {$whereSql}
   GROUP BY page_path
   ORDER BY visits DESC, page_path ASC"
);
$stmtPages->execute($params);
$byPage = $stmtPages->fetchAll();
```

Why this prevents SQL injection:
- The SQL **structure** (keywords, operators) is fixed.
- User input is passed as a **value**, not as executable SQL.
- The database never interprets the attacker’s quotes/operators as part of the query logic.

---

## Vulnerability 2 — XSS (Cross-Site Scripting) (4 pts)

### A) The vulnerable version (raw HTML output)
The dashboard builds HTML in JavaScript from data returned by `quiz3/api/recent.php`. If I remove escaping and insert values directly into `innerHTML`, the page becomes vulnerable.

Example vulnerable change to `quiz3/assets/analytics.js` (do **not** deploy this):

```js
// BAD: vulnerable to XSS
tbody.innerHTML = rows.map(v => {
  return `<tr>
    <td>${v.visited_at}</td>
    <td>${v.page_path}</td>
    <td>${v.page_title}</td>
    <td>${v.referrer}</td>
  </tr>`;
}).join("");
```

### B) A malicious input that exploits it
An attacker can store a script payload in a field that later gets displayed on the dashboard. For example, they can send a crafted title to the logging endpoint:

```html
<script>alert('hacked')</script>
```

If that value gets stored in the database and then rendered as raw HTML on the dashboard, the script executes in the admin’s browser.

### C) What would happen in the browser / why it matters
When the dashboard loads “Recent Visits”, the injected `<script>` would run and pop an alert. In a real attack, it could:
- steal session/auth info (if cookies are accessible),
- change what the admin sees on the dashboard,
- send requests as the admin (CSRF-like behavior via JavaScript).

### D) The original safe code + why it prevents this attack
My safe code in `quiz3/assets/analytics.js` escapes values before inserting into HTML:

```js
function escapeText(value) {
  const div = document.createElement("div");
  div.textContent = value == null ? "" : String(value);
  return div.innerHTML;
}
```

And then uses it when rendering:

```js
const title = escapeText(v.page_title || "");
```

Why this prevents XSS:
- `textContent` forces the browser to treat attacker input as **text**, not HTML.
- So `<script>...</script>` is displayed literally (or safely encoded) instead of executing.
