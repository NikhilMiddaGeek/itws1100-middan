# Lab 04: RSS & Atom Feeds

## Overview
This lab implements two XML feed formats for the personal website:
- RSS 2.0 feed (`rss.xml`)
- Atom 1.0 feed (`atom.xml`)

The feeds publish lab updates and link to actual completed lab landing pages.

## Files
- `rss.xml` - RSS 2.0 feed with channel metadata and lab items
- `atom.xml` - Atom 1.0 feed with feed metadata and lab entries
- `rss.css` - XML stylesheet used by both feeds for browser-readable presentation

## Feed Links
- RSS feed: `/iit/labs/lab04/rss.xml`
- Atom feed: `/iit/labs/lab04/atom.xml`

## Related Site Links
- Homepage: `/iit/labs/lab03/lab03b/homepage.html`
- Lab 4 landing page: `/iit/labs/lab03/lab03b/lab04_landing.html`
- Labs landing page: `/iit/labs/lab03/lab03b/lab_landing.html`

## Validation & Requirements Notes
- Both XML files include required root/feed structure
- RSS includes required channel elements (`title`, `link`, `description`)
- Atom includes required feed elements (`title`, `id`, `updated`, `author`)
- Both feeds include at least 4 lab entries with real site links
- XML stylesheet processing instruction is included:
  - `<?xml-stylesheet type="text/css" href="rss.css"?>`

## Workflow
Work completed in the `lab04` branch with staged commits for:
1. RSS feed creation
2. Atom feed creation
3. XML stylesheet and integration updates
