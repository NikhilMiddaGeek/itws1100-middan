# itws1100-middan

## Links (update these before submitting)

- Homepage: `https://<your-azure-site>/homepage.html`
- Projects page (Lab 08 dynamic menu): `https://<your-azure-site>/projects/index.html`
- Lab 06 landing page: `https://<your-azure-site>/labs/lab06/lab06_landing.html`
- Lab 06 page: `https://<your-azure-site>/labs/lab06/lab6.html`
- Lab 08 landing page: `https://<your-azure-site>/labs/lab08/lab08_landing.html`
- GitHub repo: `https://github.com/<your-username>/itws1100-middan`

## Lab 08 (JSON & AJAX)
The Projects page (`projects/index.html`) loads `labs/lab08/lab8-menu.json` using an AJAX request (jQuery) and builds the menu dynamically on page load.
To add a new lab/project, add a new object to the `projects` array in the JSON file—no HTML edits are needed.

## Lab 06 (jQuery) - Problem 5 explanation

If you attach a click handler directly to the existing `<li>` elements, newly added `<li>` elements will not have that handler.
The fix is event delegation: attach one handler to the parent element (the `<ul id="labList">`) using `on('click', 'li', ...)`,
so clicks on both existing and newly added list items are handled.
