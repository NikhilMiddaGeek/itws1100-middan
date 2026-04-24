# D3 — Code Walkthrough (Quiz 3 Option B)

1) Database schema
I set up one table called site_visits to hold everything the tracker logs.
Each row gets a unique ID that increments automatically, plus a field for the URL path of the page that was visited stored as text since paths are words and slashes, not numbers. I also capture the page title, where the visitor came from, and what browser they were using, though those last three are optional since they're not always available.
For privacy, I don't store raw IP addresses. Instead I run the IP through a SHA-256 hash , which gives me a consistent 64-character fingerprint I can use to spot repeat visitors without ever knowing who they actually are.
Finally, there's a timestamp for when the visit happened, and two indexes one on the timestamp for pulling recent visits quickly, and one on the page path for counting visits per page without scanning the whole table.

2) Logging a visit (the "write" side)
When someone loads one of my pages, a small script runs in the background and silently sends the visit info to a PHP endpoint on the server.
That endpoint checks that the request came in correctly, then reads three things the script sent over: the page path, the page title, and the referrer. It cleans up the values, trimming them to safe lengths and hashes the visitor's IP before anything touches the database.
Then it saves the row using a prepared statement. This is the security-critical part: instead of building the SQL by gluing the user's input into a string , it passes the values separately so the database always treats them as plain data. The endpoint responds with a simple "ok" so the page knows it worked.

3) Displaying the data 
The dashboard page pulls its data from two separate endpoints — one that returns summary totals (overall visit count plus a breakdown by page), and one that returns the most recent visits in reverse chronological order.
Both endpoints accept optional filters a date range or a specific page path — and only apply them if they're actually provided. The filtering is also done with prepared statements for the same reason as above: no raw user input ever gets concatenated into a query string.
The results come back as JSON, which the dashboard's JavaScript picks up and renders into the page.

4) The JavaScript side
There are two scripts doing different jobs.
The tracker runs quietly on every page. As soon as the page loads, it grabs the current URL, the page title, and the referrer, then fires that info off to the logging endpoint in the background, using a browser API specifically designed for this kind of "send and forget" logging so it doesn't slow the page down.
The dashboard script is the opposite , it's all about showing things. It fetches the summary and recent visits data, then writes the results into the page: filling in the total visit count and building out the data tables row by row. When someone adjusts the filters and clicks Apply, it refetches and re-renders just the data without reloading the whole page.