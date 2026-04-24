<?php

declare(strict_types=1);

?><!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Site Analytics Dashboard</title>
  <link rel="stylesheet" href="assets/analytics.css" />
</head>
<body>
  <div class="wrap">
    <header>
      <div>
        <h1 style="margin:0;font-size:20px;">Site Analytics</h1>
        <div class="muted">Quiz 3 — Option B (PHP + MySQL + JS)</div>
      </div>
      <div class="muted">
        <a href="/labs/lab04/homepage_updated.html">Home</a>
      </div>
    </header>

    <section class="card" style="margin-top:16px;">
      <div class="kpis">
        <div class="kpi">
          <div class="label">Total Visits (filtered)</div>
          <div id="totalVisits" class="value">—</div>
        </div>
      </div>

      <form id="filters" class="filters" autocomplete="off">
        <div>
          <label for="start">Start</label>
          <input id="start" type="datetime-local" />
        </div>
        <div>
          <label for="end">End</label>
          <input id="end" type="datetime-local" />
        </div>
        <div>
          <label for="pagePath">Page</label>
          <select id="pagePath">
            <option value="">All pages</option>
          </select>
        </div>
        <div>
          <button type="submit">Apply</button>
        </div>
      </form>

      <div id="status" class="status">Ready.</div>
    </section>

    <div class="grid">
      <section class="card">
        <h2>Visits by Page</h2>
        <table aria-label="Visits by page">
          <thead>
            <tr><th>Page</th><th>Visits</th></tr>
          </thead>
          <tbody id="byPageBody">
            <tr><td colspan="2" class="muted">Loading…</td></tr>
          </tbody>
        </table>
      </section>

      <section class="card">
        <h2>Recent Visits</h2>
        <table aria-label="Recent visits">
          <thead>
            <tr><th>When</th><th>Page</th><th>Title</th><th>Referrer</th></tr>
          </thead>
          <tbody id="recentBody">
            <tr><td colspan="4" class="muted">Loading…</td></tr>
          </tbody>
        </table>
      </section>
    </div>
  </div>

  <script src="assets/analytics.js"></script>
</body>
</html>

