# Lab 08 PRD (ITWS1100): JSON + AJAX Dynamic Projects Page

## 1) Overview
### Background
Your site has a “Projects” page originally built in Lab 3. For Lab 8, that projects menu/list must be generated dynamically from a JSON file using JavaScript + AJAX (via jQuery).

### Goal (what “done” means)
When the Projects page loads, it fetches a local JSON file, parses it, and renders your projects/labs list into the page (no hard-coded list items). jQuery must be loaded from a CDN, and the JavaScript must be in an external `.js` file. Add some jQuery / jQueryUI “jazz” for polish.

## 2) Scope
### In-scope
- A JSON file that contains the data needed to build the projects/labs menu.
- External JavaScript that loads the JSON via AJAX and builds the HTML.
- Projects page updated to include a container where the dynamic content is rendered.
- jQuery served from a CDN (not a local copy).
- A small “personal flair” enhancement using jQuery and/or jQueryUI.
- Documentation (README + helpful code comments).

### Out-of-scope (don’t waste time)
- Rebuilding the entire site design from scratch.
- Switching away from jQuery/AJAX to a framework (React/Vue/etc.).
- Adding backend code (this lab is client-side).

## 3) Stakeholders
- **You (student)**: want a clean, correct submission that’s easy to grade.
- **Instructor/TA**: wants to see JSON validity, AJAX usage, dynamic DOM creation, and documentation.

## 4) Requirements (for 100%)
### Functional requirements
1. **JSON-driven content**
   - Maintain a JSON file containing your menu/list entries (labs/projects).
   - JSON is well-formed and represents *your* site content (not placeholder names).
2. **Dynamic rendering**
   - The Projects page must build the menu/list from JSON at page load.
   - The rendered output includes, at minimum, each item’s label/title and link.
   - If you include images/thumbnails in JSON, they must render correctly (with valid paths).
3. **AJAX (jQuery)**
   - Use jQuery AJAX (e.g., `$.getJSON()` or `$.ajax({ dataType: "json" ... })`) to load the JSON file.
   - Handle the error case (show a message in the page if JSON fails to load).
4. **External JS**
   - All JavaScript for Lab 8 is in an external `.js` file (no inline `<script>` blocks containing logic).
5. **jQuery from CDN**
   - The Projects page references jQuery from a public CDN (e.g., code.jquery.com).
6. **“Jazz-up”**
   - Add at least one visible enhancement using jQuery/jQueryUI (examples below).

### Non-functional requirements
- Works when hosted (Azure / RCS / localhost). Do **not** rely on `file://` access.
- Reasonable HTML semantics and accessibility (links have meaningful text; images have `alt`).
- Clean, readable code (consistent indentation, descriptive names).

## 5) Data design (JSON)
You were provided a template (`lab8jsontemplate.json`) that uses keys like `a`, `b`, `c`, `d`. For full credit, use a structure that is clearly described and readable.

### Recommended JSON schema (clear + grader-friendly)
Create a JSON file (example name: `projects/lab8-menu.json`) like:
- Top-level object with an array, e.g. `{ "menuItems": [ ... ] }`
- Each item object:
  - `title` (string) – display text
  - `subtitle` (string, optional) – small description
  - `href` (string) – relative link to the lab/project page
  - `img` (string, optional) – thumbnail path
  - `tag` (string, optional) – e.g., “Lab”, “Project”, “Quiz”

### Acceptance criteria for JSON
- Valid JSON (no trailing commas; quotes around keys/strings).
- Paths are correct from the Projects page location.
- Represents your real labs/projects (Lab 1–current), not the template’s example values.

## 6) Page/UI design (Projects page)
### Target page
Use the Projects page that your site actually links to. In this repo, that is typically:
- `projects/index.html`

### Required HTML changes
- Add a container element where the dynamic list will be injected, for example:
  - `<ul id="projectsMenu"></ul>` or `<div id="projectsMenu"></div>`
- Add a “status” area for error/loading messages, for example:
  - `<div id="projectsStatus" role="status"></div>`
- Include:
  - jQuery CDN `<script>` tag (before your lab8 script)
  - Your external Lab 8 JS file `<script src="..."></script>`

## 7) Technical approach
### JavaScript flow (must happen on load)
1. Wait for DOM ready (`$(document).ready(...)`).
2. Update UI to “Loading…” in `#projectsStatus`.
3. Fetch JSON with AJAX.
4. Validate/assume expected keys exist; iterate items with `$.each()` or a loop.
5. Generate HTML strings or DOM nodes and inject into the container.
6. Clear loading status.
7. Apply your jQuery/jQueryUI enhancement.

### Error handling (required)
- If the request fails or JSON is malformed:
  - Show a friendly message in `#projectsStatus`
  - Do not leave the page blank with no explanation

### “Jazz-up” options (pick at least one)
- jQueryUI Accordion for grouping labs by unit/week.
- jQueryUI Tabs for “Labs / Projects / Quizzes”.
- A filter/search box (jQuery) that hides non-matching items as you type.
- Hover animation on cards/list items (fade/slide).
- Sort buttons (A→Z, newest→oldest) using jQuery.

## 8) Deliverables (what to submit in GitHub)
## 8.1) Files to create/modify (and what each does)
Use these exact paths (recommended for your repo), or equivalents that your site actually links to—graders will look for a working Projects page that dynamically renders from JSON.

### A) Data (JSON)
- **Create:** `labs/lab08/lab8-menu.json`
  - **Purpose:** Single source of truth for your labs/projects list.
  - **Required content:** Valid JSON matching the expected structure:
    ```json
    {
      "projects": [
        { "title": "Lab 2: HTML Resume", "description": "My HTML resume", "link": "..." }
      ]
    }
    ```
  - **Rules:**
    - Must be personalized (your real lab/project titles, not template/example placeholders).
    - Each item must include at minimum: `title`, `description`, `link`.
    - Links must be correct relative to `projects/index.html` (e.g., `../labs/lab06/lab06_landing.html`).

### B) Logic (external JS)
- **Create:** `labs/lab08/lab8.js`
  - **Purpose:** Fetches `labs/lab08/lab8-menu.json`, parses it, and builds the DOM menu/list dynamically.
  - **Must include (rubric/QA):**
    - An AJAX request using **one** of: `fetch()` or `$.ajax()` / `$.getJSON()` (jQuery).
    - JSON parsing/validation appropriate to your method (e.g., `response.json()` for `fetch()`, or `JSON.parse()` only if using raw `XMLHttpRequest`).
    - DOM creation using `document.createElement()` or jQuery DOM methods (`.append()`, `.html()`).
    - Error handling (`.catch(...)` or jQuery `error:` callback) that updates the page with a friendly message.
    - Explanatory comments where your choices aren’t obvious (schema, pathing, effects/widgets).

### C) UI entry point (Projects page)
- **Modify:** `projects/index.html`
  - **Purpose:** Loads jQuery (CDN), loads your external `projects/lab8.js`, and provides containers for dynamic output.
  - **Must include:**
    - A container for the generated menu/list, e.g. `<div id="projectsMenu"></div>` or `<ul id="projectsMenu"></ul>`.
    - A status/error region, e.g. `<div id="projectsStatus" role="status"></div>`.
    - jQuery loaded from a **CDN** (NOT `projects/.../jquery-*.min.js` from the zip).
    - A `<script src="../labs/lab08/lab8.js"></script>` (or correct relative path) after jQuery is loaded.
  - **Hardcode rule:** No hardcoded project list in the HTML except an optional *fallback* message (e.g., “Loading…/If you see this, JS failed.”).

### D) Enhancement (jQuery/jQueryUI)
- **Optional create/modify (choose one approach):**
  - **Option 1 (recommended):** Keep effects/widgets inside `labs/lab08/lab8.js`
    - Examples: `.fadeIn()`, `.slideDown()`, `.animate()`, filtering, sorting, hover effects.
  - **Option 2 (jQueryUI widgets):** Add jQueryUI via CDN in `projects/index.html`
    - **Purpose:** Enables widgets like accordion/tabs/dialog for “jazz-up” points.
    - **Note:** If you use jQueryUI, include both the jQueryUI CSS and JS (both via CDN).

### E) Documentation
- **Modify:** `README.md` (repo root)
  - **Purpose:** Explains what you implemented and provides required links.
  - **Must include:**
    - Short description of Lab 8 implementation (JSON schema + where files live).
    - Your GitHub repo link.
    - Your Azure deployed site link (and optionally the Projects page link).

### F) (Workflow artifacts on GitHub)
- **Create on GitHub:** Issue describing Lab 8 work
  - **Purpose:** Satisfies workflow points.
- **Create branch:** `lab8`
  - **Purpose:** Contains your Lab 8 commits and PR.
- **PR:** Merge `lab8` → `main` and close the issue in the PR description
  - **Purpose:** Satisfies PR/merge + issue closure rubric items.

### Code deliverables
- Projects page updated (example: `projects/index.html`)
- JSON data file (example: `projects/lab8-menu.json`)
- External JS file that loads JSON + renders content (example: `projects/lab8.js`)
- Any CSS updates needed (optional, but keep it tidy)

### Documentation deliverables (rubric points)
- A README file describing:
  - What you changed for Lab 8
  - Where the JSON file is and how it’s structured
  - Where the JS file is and what it does
  - Links to GitHub repo + deployed site (Azure)
- Code comments only where they clarify decisions (don’t comment every line).

## 9) Grading rubric mapping (10 points total)
From `lab8rubric.txt`:
- **Basic Functionality (5)**
  - JSON well-formed and described
  - JSON + JS specifically written for *your* website (not copied placeholders)
- **Documentation (2)**
  - Comments where necessary
  - README included
- **Creativity (3)**
  - Personal flair: jQueryUI, imagery, extra menu detail, etc.

## 10) GitHub workflow requirements (process points)
From the instructions:
1. Create a GitHub Issue describing what you will do for Lab 8.
2. Create a branch named `lab8`.
3. Implement + commit changes.
4. Open/complete PR or merge `lab8` into `main` on GitHub.
5. Close the Issue.
6. Pull changes to your hosted site and verify it works.

## 11) QA checklist (use this before submission)
- Projects page loads with no console errors.
- jQuery is loaded from a CDN (view-source verifies).
- Your Lab 8 JS is external and is the only place the logic lives.
- JSON validates and contains your real labs/projects.
- Menu/list is generated dynamically (removing JSON entries changes output without editing HTML).
- Works on your hosted site (not just locally).
- README exists and includes GitHub + Azure links.
