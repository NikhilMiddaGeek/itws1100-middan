/* Lab 08: JSON + AJAX Dynamic Projects Menu (Lab 03b site)
   Core requirements:
   - External JS file
   - Loads JSON via AJAX (fetch)
   - Dynamically builds HTML (DOM creation)
   - Error handling for failed fetch/parse
   - jQuery loaded from CDN (in HTML); optional jQueryUI enhancement when available

   Important: This implementation does NOT depend on jQuery to run.
   That prevents the page from getting "stuck" if CDN scripts are blocked or slow.
*/

(function () {
  "use strict";

  function byId(id) {
    return document.getElementById(id);
  }

  function setStatus(message, isError) {
    var el = byId("projectsStatus");
    if (!el) return;
    el.textContent = message || "";
    el.classList.toggle("lab8-status-error", !!isError);
  }

  function resolveHref(rawHref) {
    if (!rawHref || typeof rawHref !== "string") return "#";
    if (/^(https?:)?\/\//i.test(rawHref) || rawHref.startsWith("#")) return rawHref;

    // For Live Server reliability, keep non-absolute links relative to the current page.
    // This prevents "Cannot GET /lab01_landing.html" when the server root is the repo root.
    if (!rawHref.startsWith("/")) return rawHref;

    // If deployed under "/iit/", prefix absolute paths accordingly.
    var clean = rawHref.replace(/^\/+/, "");
    var prefix = window.location.pathname.indexOf("/iit/") !== -1 ? "/iit/" : "/";
    return prefix + clean;
  }

  function validatePayload(payload) {
    if (!payload || !Array.isArray(payload.projects)) {
      return "JSON must be an object with a 'projects' array.";
    }

    for (var i = 0; i < payload.projects.length; i++) {
      var item = payload.projects[i];
      if (
        !item ||
        typeof item.title !== "string" ||
        typeof item.description !== "string" ||
        typeof item.link !== "string" ||
        typeof item.secure !== "boolean"
      ) {
        return "Each project must have 'title', 'description', 'link' (strings), and 'secure' (boolean).";
      }
    }

    return null;
  }

  function buildUI(projects) {
    var menuRoot = byId("projectsMenu");
    if (!menuRoot) return;

    menuRoot.innerHTML = "";

    // Filter UI
    var filterWrap = document.createElement("div");
    filterWrap.className = "lab8-filter";

    var filterLabel = document.createElement("label");
    filterLabel.setAttribute("for", "projectsFilter");
    filterLabel.textContent = "Filter:";

    var filterInput = document.createElement("input");
    filterInput.id = "projectsFilter";
    filterInput.type = "text";
    filterInput.placeholder = "Type to filter labs/projects...";
    filterInput.autocomplete = "off";

    filterWrap.appendChild(filterLabel);
    filterWrap.appendChild(filterInput);

    // Accordion-friendly markup: h3 + div pairs
    var accordion = document.createElement("div");
    accordion.id = "projectsAccordion";
    accordion.className = "lab8-accordion";

    projects.forEach(function (item) {
      var lockIcon = item.secure ? " \uD83D\uDD12" : "";

      var header = document.createElement("h3");
      header.className = "lab8-acc-header";
      header.tabIndex = 0;
      header.textContent = item.title + lockIcon;

      var panel = document.createElement("div");
      panel.className = "lab8-acc-panel";

      var desc = document.createElement("p");
      desc.textContent = item.description;

      var linkP = document.createElement("p");
      var link = document.createElement("a");
      link.className = "lab8-open-link";
      link.href = resolveHref(item.link);
      link.textContent = "Open";
      if (item.secure) link.title = "Password required";
      linkP.appendChild(link);

      panel.appendChild(desc);
      panel.appendChild(linkP);

      accordion.appendChild(header);
      accordion.appendChild(panel);
    });

    menuRoot.appendChild(filterWrap);
    menuRoot.appendChild(accordion);

    // Vanilla accordion behavior (works even without jQueryUI)
    function togglePanelFor(headerEl) {
      var panelEl = headerEl && headerEl.nextElementSibling;
      if (!panelEl) return;
      var isOpen = panelEl.classList.contains("lab8-panel-open");
      panelEl.classList.toggle("lab8-panel-open", !isOpen);
      headerEl.classList.toggle("lab8-header-open", !isOpen);
    }

    accordion.addEventListener("click", function (e) {
      var target = e.target;
      if (target && target.classList && target.classList.contains("lab8-acc-header")) {
        togglePanelFor(target);
      }
    });

    accordion.addEventListener("keydown", function (e) {
      if (e.key !== "Enter" && e.key !== " ") return;
      var target = e.target;
      if (target && target.classList && target.classList.contains("lab8-acc-header")) {
        e.preventDefault();
        togglePanelFor(target);
      }
    });

    // Open nothing by default (matches jQueryUI active:false)
    var panels = accordion.querySelectorAll(".lab8-acc-panel");
    panels.forEach(function (p) {
      p.classList.remove("lab8-panel-open");
    });

    // Live filter: hide non-matching headers + panels
    filterInput.addEventListener("input", function () {
      var query = (filterInput.value || "").toLowerCase().trim();
      var anyVisible = false;

      var headers = accordion.querySelectorAll("h3.lab8-acc-header");
      headers.forEach(function (h) {
        var p = h.nextElementSibling;
        var text = ((h.textContent || "") + " " + (p ? p.textContent : "")).toLowerCase();
        var match = query === "" || text.indexOf(query) !== -1;
        h.style.display = match ? "" : "none";
        if (p) p.style.display = match ? "" : "none";
        anyVisible = anyVisible || match;
      });

      setStatus(anyVisible ? "" : "No matching results.", false);
    });

    // Optional enhancement: if jQueryUI loads later, upgrade to accordion widget.
    maybeEnhanceWithJQueryUI();
  }

  function maybeEnhanceWithJQueryUI() {
    var start = Date.now();
    var maxWaitMs = 4000;

    function tryInit() {
      var $ = window.jQuery;
      if ($ && $.fn && typeof $.fn.accordion === "function") {
        try {
          window.jQuery("#projectsAccordion").accordion({
            collapsible: true,
            heightStyle: "content",
            active: false,
          });
          // Small jQuery effect for “jazz-up” points (only when available)
          window.jQuery("#projectsAccordion").hide().fadeIn(200);
        } catch (e) {
          // keep vanilla behavior if widget init fails
        }
        return;
      }

      if (Date.now() - start < maxWaitMs) {
        setTimeout(tryInit, 200);
      }
    }

    tryInit();
  }

  function init() {
    var menuRoot = byId("projectsMenu");
    if (!menuRoot) return;

    var jsonUrl = menuRoot.getAttribute("data-json");
    if (!jsonUrl) {
      setStatus("Missing data-json attribute for JSON location.", true);
      return;
    }

    setStatus("Loading projects...", false);

    fetch(jsonUrl, { cache: "no-store" })
      .then(function (res) {
        if (!res.ok) throw new Error("HTTP " + res.status);
        return res.json();
      })
      .then(function (payload) {
        var error = validatePayload(payload);
        if (error) {
          setStatus("JSON validation error: " + error, true);
          return;
        }
        buildUI(payload.projects);
        setStatus("", false);
      })
      .catch(function (err) {
        setStatus(
          "Could not load JSON menu. " +
            String(err && err.message ? err.message : err) +
            " (Run from Live Server, not file://)",
          true
        );
      });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
