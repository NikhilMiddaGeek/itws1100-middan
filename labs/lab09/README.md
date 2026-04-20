# Lab 09: PHP & MySQL

## Description
Build a dynamic web application with PHP and a MySQL/MariaDB database. The app must list and manage **movies** and **actors/characters**, use **prepared statements**, and include a **many-to-many relationship** between movies and actors.

## What to turn in (repo artifacts)
- `labs/lab09/PRD.md` (requirements + checklist)
- `labs/lab09/actors.csv` (actors born on/after `1965-01-01`)
- `labs/lab09/iit.sql` (full schema export from phpMyAdmin)

## Quick start (local/Azure)
1) In phpMyAdmin, select database `iit` and import `labs/lab09/iit-lab9start.sql`
2) Copy `labs/lab09/config.sample.php` to `labs/lab09/config.local.php` and set DB credentials
3) Browse to `http://<your-host>/iit/labs/lab09/index.php`

## Rubric-driven checklist

### Database setup (schema)
- `actors` table exists with an auto-increment `id` primary key plus name fields + birth info (date or year), matching the starter PHP field names.
- `movies` table exists from the starter SQL and matches the PHP field names.
- `movie_actors` (junction) table exists and links `movies` <-> `actors` with FK columns like `movie_id` and `actor_id`.

### Data requirements
- Inserted 5+ actors, with at least 3 born before `1965-01-01` (or `birth_year` < 1965 if using years).

### PHP implementation
- Database connection uses `mysqli` or `PDO` with error handling.
- `movies.php` lists movies via a `SELECT` query and loops to display results.
- Add/remove operations work (Create/Read/Delete; Update optional).
- SQL uses prepared statements (no string-interpolated SQL).
- HTML output is escaped (e.g., `htmlspecialchars()`).
- DB credentials are not hardcoded in random files (use a config/include).

### CSV export requirement
- Query filters actors born on/after `1965-01-01` (or `birth_year >= 1965`).
- Export saved as `labs/lab09/actors.csv` (CSV for MS Excel from phpMyAdmin is acceptable).

### Bonus (extra credit)
- A page/tab that uses a JOIN to show movies with their actors, for example:
  - `SELECT m.title, a.first, a.last FROM movies m JOIN movie_actors ma ... JOIN actors a ...`

## Notes
- If the handout text and rubric disagree on the cutoff year/date, follow the rubric (`1965`) for grading.
