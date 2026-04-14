# Lab 08: JSON & AJAX

## What this lab does
The Projects page loads a JSON file using an AJAX request and builds the Projects menu dynamically on page load.
The primary Projects page is `/projects/index.html`.

## Links:
main lab08 page: http://127.0.0.1:5500/labs/lab03/lab03b/index.html


## Files (all stored in `labs/lab08/`)
- `labs/lab08/lab8-menu.json`
  - JSON data source with a top-level `projects` array.
  - Each item includes `title`, `description`, and `link` (minimum required).
- `labs/lab08/lab8.js`
  - External JavaScript that fetches the JSON with `$.ajax(...)`, validates the structure, and creates DOM content dynamically.
  - Includes error handling that writes a friendly message into the page.
- `labs/lab08/lab8.css`
  - Small styling for status messages, filter UI, and “Open” links.
- `labs/lab08/lab08_landing.html`
  - Landing page describing Lab 08 and linking to the lab files.

## JSON structure (expected)
```json
{
  "projects": [
    {
      "title": "Lab 2: HTML Resume",
      "description": "My HTML resume",
      "link": "lab02/index.html"
    }
  ]
}
```


