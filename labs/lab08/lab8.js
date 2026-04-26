

(function () {
  "use strict";

  function setStatusText(message, isError) {
    var el = document.getElementById("projectsStatus");
    if (!el) return;
    el.textContent = message || "";
    if (isError) el.classList.add("lab8-status-error");
    else el.classList.remove("lab8-status-error");
  }

  // Quick on-page signal that this script actually loaded.
  setStatusText("Lab 8 script loaded. Waiting for jQuery…", false);

  // If jQuery fails to load (e.g., CDN blocked/offline), avoid a silent blank state.
  if (typeof window.jQuery === "undefined") {
    setStatusText(
      "jQuery failed to load. Check your internet connection or CDN access.",
      true
    );
    return;
  }

  var $ = window.jQuery;

  function setStatus(message, isError) {
    var $status = $("#projectsStatus");
    if (!$status.length) return;
    $status
      .toggleClass("lab8-status-error", !!isError)
      .text(message || "");
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
    var $menuRoot = $("#projectsMenu");
    if (!$menuRoot.length) return;

    // Filter UI (simple jQuery enhancement)
    var $filterWrap = $("<div>", { class: "lab8-filter" });
    var $filterLabel = $("<label>", { for: "projectsFilter", text: "Filter:" });
    var $filterInput = $("<input>", {
      id: "projectsFilter",
      type: "text",
      placeholder: "Type to filter labs/projects…",
      autocomplete: "off",
    });

    $filterWrap.append($filterLabel, $filterInput);

    // Accordion container (jQueryUI widget enhancement)
    var $accordion = $("<div>", { id: "projectsAccordion", class: "lab8-accordion" });

    projects.forEach(function (item) {
      var lockIcon = item.secure ? " \uD83D\uDD12" : "";

      var safeTitle = item.title + lockIcon;
      var safeDesc = item.description;
      var safeLink = item.link;

      $accordion.append($("<h3>").text(safeTitle));

      var $panel = $("<div>");
      $panel.append($("<p>").text(safeDesc));
      $panel.append(
        $("<p>").append(
          $("<a>", {
            href: resolveHref(safeLink),
            class: "lab8-open-link",
            title: item.secure ? "Password required" : "",
          }).text("Open")
        )
      );

      $accordion.append($panel);
    });

    $menuRoot.empty().append($filterWrap, $accordion);

    // Initialize jQueryUI accordion (if present) + a small effect for visibility.
    $accordion.hide();
    if ($.fn && typeof $.fn.accordion === "function") {
      $accordion.accordion({
        collapsible: true,
        heightStyle: "content",
        active: false,
      });
    }
    $accordion.fadeIn(250);

    // Live filter: hide non-matching headers + panels.
    $filterInput.on("input", function () {
      var query = ($(this).val() || "").toLowerCase().trim();
      var anyVisible = false;

      var $headers = $accordion.find("h3");
      $headers.each(function () {
        var $h = $(this);
        var $p = $h.next("div");
        var text = ($h.text() + " " + $p.text()).toLowerCase();
        var match = query === "" || text.indexOf(query) !== -1;
        $h.toggle(match);
        $p.toggle(match);
        anyVisible = anyVisible || match;
      });

      setStatus(anyVisible ? "" : "No matching results.", false);
    });
  }

  // Resolves a link stored in JSON so it works both locally and on the deployed site.
  // JSON links are stored as paths like: "labs/lab05/lab05_landing.html"
  // If the deployed site lives under "/iit/", we prefix that automatically.
  function resolveHref(rawHref) {
    if (!rawHref || typeof rawHref !== "string") return "#";
    if (/^(https?:)?\/\//i.test(rawHref) || rawHref.startsWith("#")) return rawHref;

    var clean = rawHref.replace(/^\/+/, "");
    var prefix = window.location.pathname.indexOf("/iit/") !== -1 ? "/iit/" : "/";
    return prefix + clean;
  }

  $(function () {

    var $menuRoot = $("#projectsMenu");
    if (!$menuRoot.length) return;

    var jsonUrl = $menuRoot.data("json");
    if (!jsonUrl) {
      setStatus("Missing data-json attribute for JSON location.", true);
      return;
    }

    setStatus("Loading projects…", false);

    $.ajax({
      type: "GET",
      url: jsonUrl,
      dataType: "json",
      cache: false,
      success: function (payload) {
        var error = validatePayload(payload);
        if (error) {
          setStatus("JSON validation error: " + error, true);
          return;
        }

        buildUI(payload.projects);
        setStatus("", false);
      },
      error: function (xhr) {
        var msg = "Could not load JSON menu.";
        if (xhr && xhr.status) msg += " HTTP " + xhr.status + ".";
        setStatus(msg + " Make sure you are running from a web server (not file://).", true);
      },
    });
  });
})();
