(function () {
  // Don't track the API endpoints themselves.
  if (location.pathname.includes("/quiz3/api/")) return;

  const payload = {
    page_path: location.pathname,
    page_title: document.title || "",
    referrer: document.referrer || ""
  };

  // Build the API URL based on where this script is served from.
  // Prefer deriving from the <script src="..."> tag because some sites are hosted under a subpath (like /iit).
  // Example script src: /iit/quiz3/assets/track.js  => base: /iit/quiz3
  let basePath = "/quiz3";

  // Fallback guess: if the site is hosted under /iit, default to /iit/quiz3.
  if (location.pathname.startsWith("/iit/")) {
    basePath = "/iit/quiz3";
  }

  const scriptEl =
    document.currentScript ||
    document.querySelector('script[src*="quiz3/assets/track.js"]') ||
    document.querySelector('script[src*="quiz3\\\\assets\\\\track.js"]');

  const scriptSrc = scriptEl && scriptEl.src ? scriptEl.src : "";
  try {
    if (scriptSrc) {
      const pathname = new URL(scriptSrc, location.origin).pathname;
      basePath = pathname.replace(/\/assets\/track\.js.*$/, "");
    }
  } catch (_) {}
  const url = `${basePath}/api/log_visit.php`;

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
