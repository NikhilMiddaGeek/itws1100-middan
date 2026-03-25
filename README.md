# itws1100-middan

## Links (update these before submitting)

- Homepage: `https://<your-azure-site>/homepage.html`
- Lab 06 landing page: `https://<your-azure-site>/labs/lab06/lab06_landing.html`
- Lab 06 page: `https://<your-azure-site>/labs/lab06/lab6.html`
- GitHub repo: `https://github.com/<your-username>/itws1100-middan`

## Lab 06 (jQuery) - Problem 5 explanation

If you attach a click handler directly to the existing `<li>` elements, newly added `<li>` elements will not have that handler.
The fix is event delegation: attach one handler to the parent element (the `<ul id="labList">`) using `on('click', 'li', ...)`,
so clicks on both existing and newly added list items are handled.
