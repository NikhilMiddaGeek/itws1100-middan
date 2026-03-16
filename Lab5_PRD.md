# PRD — ITWS1100 Lab 05: JavaScript Form Validation

## Overview
Build a single-page “Lab 5” web form that demonstrates basic JavaScript DOM interaction and client-side form validation, plus two usability fixes in HTML/CSS (“crime #1” and “crime #4”).

The implementation must use **external** JavaScript (`lab5.js`) and **not** inline event handlers.

## Goals (What “done” means)
- Form blocks submission when any required field is blank/whitespace.
- On valid submission, the page shows a success message via `alert()`.
- Comments textarea clears only the default text on focus/click and restores it on blur only if left empty.
- A Nickname button shows an alert in the exact format: `"firstName lastName is nickname"`.
- Usability “crime #1”: labels are correctly associated with inputs.
- Usability “crime #4”: focused fields have background color `#fee`.
- HTML/CSS validate; JavaScript has no syntax errors; code style matches rubric (2–3 spaces, braces on all control statements).

## Users / Use Cases
- **Student (primary user)** enters contact info and comments.
- **Grader** opens the page, interacts with fields, and verifies behaviors and code style.

## In Scope
- Client-side validation for all fields in the form.
- DOM event handling using `addEventListener` (submit, focus/blur, click).
- Small HTML/CSS edits to address usability issues.
- Optional bonus: focus the first input on initial page load.

## Out of Scope
- Server-side processing, persistent storage, or “real” form submission to a backend.
- Any UI redesign beyond the required fixes.

## Functional Requirements

### FR1 — Student Name Display
- Add student name **directly below** the `<h1>` in `lab5.html`.

### FR2 — Form Submit Validation (No blanks)
- On submit, validate **all** form fields:
  - `firstName`, `lastName`, `title`, `org`, `pseudonym` (nickname), `comments`.
- Treat whitespace-only as blank (e.g., `"   "` is invalid).
- If invalid:
  - prevent submission (via `event.preventDefault()`),
  - show an `alert()` describing what is missing,
  - focus the invalid field,
  - do not show the success alert.

### FR3 — Success Alert
- If all validations pass on submit, show an `alert()` indicating successful save/submission.

### FR4 — Comment Field Behavior
- Default textarea text is: `Please enter your comments`.
- On focus/click into textarea:
  - If the current value equals the default text, clear it.
  - If the user already typed something else, do **not** erase it.
- On blur (leaving textarea):
  - If the textarea is empty/whitespace-only, restore the default text.

### FR5 — Nickname Button
- Add a button **below the Contact Information box** (below the `fieldset`) labeled something like “Nickname”.
- On click, show an alert in this exact format:
  - `"firstName lastName is nickname"`
  - Example: `"Samuel Clemens is Mark Twain"`
- Values come from the existing inputs: `#firstName`, `#lastName`, `#pseudonym`.

### FR6 — Usability Fix: “Crime #1” (Label Association)
- Each label must be associated with its input:
  - Use `for="inputId"` on `<label>` and ensure the input has matching `id`, **or**
  - Wrap the input inside the label.

### FR7 — Usability Fix: “Crime #4” (Focus Styling)
- In `lab5.css`, add focus styling so that **focused fields** (inputs and textarea) have:
  - `background-color: #fee;`

### FR8 (Bonus) — Focus First Field On Load
- On initial page load, focus the first form element (expected: first name input).

## Non-Functional Requirements / Quality
- **No inline JavaScript** in HTML (no `onsubmit=`, `onclick=`, etc.).
- JavaScript uses consistent **2–3 space indentation** (no tabs).
- Curly braces on all control statements (`if/else`, loops).
- JavaScript has **no syntax errors** in browser console.
- HTML and CSS should pass validation (W3C).

## Deliverables (Files & Purpose)

### Required lab files
- `lab5.html` — Markup for the page and form (adds student name; fixes labels; adds Nickname button; removes inline JS handlers).
- `lab5.css` — Styling, including `:focus` background color requirement (`#fee`).
- `lab5.js` — All JavaScript behavior:
  - attach event listeners,
  - validate on submit,
  - implement comment focus/blur behavior,
  - implement nickname button alert,
  - optional initial focus.

### Repo/course workflow files (expected in your website repo)
- `README.md` (repo root) — Mentions Lab 5 and includes a working link to the deployed site (and/or directly to the Lab 5 page).
- “Projects page” file (name varies by repo; e.g., `projects.html` or `index.html`) — Adds a link to `lab5.html`.

## Acceptance Criteria (Checklist)
- Page shows student name immediately under `<h1>`.
- All fields are blocked when blank/whitespace; user sees an alert and the field is focused.
- Successful submit shows a success `alert()` and does not show validation alerts.
- Comments textarea:
  - clears default text only,
  - restores default only if left empty,
  - never deletes non-default user input.
- Nickname button alert exactly matches: `"firstName lastName is nickname"`.
- Labels are properly associated with inputs (crime #1 fixed).
- Focused fields visibly change background to `#fee` (crime #4 fixed).
- No inline event attributes in `lab5.html`; JS is loaded from `lab5.js`.
- Code style: consistent 2–3 spaces; braces on all control statements.

## Implementation Notes (Planned Approach)
- In `lab5.js`, wait for DOM ready (`DOMContentLoaded`) then:
  - cache references to the form and fields,
  - attach `submit` listener for validation,
  - attach `focus`/`blur` listeners to the textarea,
  - attach `click` listener to the Nickname button,
  - optionally focus `#firstName`.
- Validation uses `value.trim()` to detect blank inputs.

