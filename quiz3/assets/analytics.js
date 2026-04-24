async function fetchJson(url) {
  const res = await fetch(url, { headers: { "Accept": "application/json" } });
  const data = await res.json().catch(() => null);
  if (!res.ok || !data || data.ok !== true) {
    const message = (data && data.error) ? data.error : `Request failed (${res.status})`;
    throw new Error(message);
  }
  return data;
}

function toQuery(filters) {
  const params = new URLSearchParams();
  if (filters.start) params.set("start", filters.start);
  if (filters.end) params.set("end", filters.end);
  if (filters.page_path) params.set("page_path", filters.page_path);
  const s = params.toString();
  return s ? `?${s}` : "";
}

function setStatus(msg) {
  const el = document.getElementById("status");
  if (el) el.textContent = msg;
}

function escapeText(value) {
  const div = document.createElement("div");
  div.textContent = value == null ? "" : String(value);
  return div.innerHTML;
}

function renderByPage(rows) {
  const tbody = document.getElementById("byPageBody");
  if (!tbody) return;

  if (!rows || rows.length === 0) {
    tbody.innerHTML = `<tr><td colspan="2" class="muted">No results.</td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map(r => {
    const path = escapeText(r.page_path);
    const visits = escapeText(r.visits);
    return `<tr><td>${path}</td><td>${visits}</td></tr>`;
  }).join("");
}

function renderRecent(rows) {
  const tbody = document.getElementById("recentBody");
  if (!tbody) return;

  if (!rows || rows.length === 0) {
    tbody.innerHTML = `<tr><td colspan="4" class="muted">No visits yet.</td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map(v => {
    const at = escapeText(v.visited_at);
    const path = escapeText(v.page_path);
    const title = escapeText(v.page_title || "");
    const ref = escapeText(v.referrer || "");
    return `<tr><td>${at}</td><td>${path}</td><td>${title}</td><td>${ref}</td></tr>`;
  }).join("");
}

function setTotal(total) {
  const el = document.getElementById("totalVisits");
  if (el) el.textContent = String(total ?? 0);
}

function updatePageDropdown(byPage, current) {
  const sel = document.getElementById("pagePath");
  if (!sel) return;

  const options = [`<option value="">All pages</option>`].concat(
    (byPage || []).map(r => {
      const p = String(r.page_path);
      const selected = current === p ? " selected" : "";
      return `<option value="${escapeText(p)}"${selected}>${escapeText(p)}</option>`;
    })
  );
  sel.innerHTML = options.join("");
}

function readFiltersFromForm() {
  const start = document.getElementById("start").value;
  const end = document.getElementById("end").value;
  const page_path = document.getElementById("pagePath").value;
  return {
    start: start ? start.replace("T", " ") + ":00" : "",
    end: end ? end.replace("T", " ") + ":59" : "",
    page_path: page_path || ""
  };
}

async function loadDashboard(filters) {
  setStatus("Loading…");
  const q = toQuery(filters);

  const [summary, recent] = await Promise.all([
    fetchJson(`api/summary.php${q}`),
    fetchJson(`api/recent.php${q}`)
  ]);

  setTotal(summary.total_visits);
  updatePageDropdown(summary.by_page, filters.page_path || "");
  renderByPage(summary.by_page);
  renderRecent(recent.visits);

  setStatus(`Updated ${new Date().toLocaleString()}`);
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("filters");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    try {
      await loadDashboard(readFiltersFromForm());
    } catch (err) {
      setStatus(`Error: ${err.message}`);
    }
  });

  // Initial load: last 7 days.
  const now = new Date();
  const start = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
  const pad = (n) => String(n).padStart(2, "0");
  const toLocalInput = (d) =>
    `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;

  document.getElementById("start").value = toLocalInput(start);
  document.getElementById("end").value = toLocalInput(now);

  loadDashboard(readFiltersFromForm()).catch((err) => setStatus(`Error: ${err.message}`));
});

