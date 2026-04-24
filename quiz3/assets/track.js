(function () {
  // Don't track the API endpoints themselves.
  if (location.pathname.startsWith("/quiz3/api/")) return;

  const payload = {
    page_path: location.pathname,
    page_title: document.title || "",
    referrer: document.referrer || ""
  };

  const url = "/quiz3/api/log_visit.php";

  try {
    if (navigator.sendBeacon) {
      const blob = new Blob([JSON.stringify(payload)], { type: "application/json" });
      navigator.sendBeacon(url, blob);
      return;
    }
  } catch (_) {
    // fall through to fetch
  }

  fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
    keepalive: true
  }).catch(() => {});
})();

