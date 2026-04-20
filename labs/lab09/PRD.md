# Lab 9 PRD — Databases & SQL (Intro ITWS)

## 1) Overview
This lab extends a starter PHP + MariaDB (MySQL) app so it can **Add / Remove / Display** both **actors** and **movies** from an `iit` database. You will also create missing database tables and export required artifacts (`actors.csv`, `iit.sql`).

The starter code (from `ITWS1100-LAB9.zip`) provides:
- An SQL file that creates a **movies** table (and inserts sample movies).
- A PHP app with a tab/menu UI where the **actors tab exists but errors** because the `actors` table is missing.
- No completed `movies.php` implementation (you must build it to match the actors patterns).

## 2) Goals
- Create the missing `actors` table with correct field names and types.
- Implement CRUD (add/remove/list) for **actors** and **movies** in the PHP app.
- Create a relationship table linking movies <-> actors and insert some relationships.
- Export:
  - `actors.csv` containing actors born **on or after `1965-01-01`**.
  - `iit.sql` containing the **full database schema**.

## 3) Non-Goals / Out of Scope
- Building authentication/roles for the app.
- Full editing/updating of records (only add/remove/display is required).
- Anything not required by the lab handout/rubric (keep changes focused).

## 4) Target Environment / Constraints
- Must run on your Azure server (per lab notes).
- MariaDB/MySQL must already be secured using the Lab 1 tutorial.
- phpMyAdmin must be installed using the Lab 1 tutorial.
- Database name must be `iit` with collation `utf8mb4_general_ci`.

## 5) Deliverables (What must exist in your repo)
- `labs/lab09/` folder containing at minimum:
  - `actors.csv` (export from phpMyAdmin after running the required query).
  - `iit.sql` (export schema from phpMyAdmin "Export" tab).
  - This `PRD.md`.
- Your working PHP + SQL lab code (from the starter zip) committed to your repository on the correct branch and merged when finished (per your class workflow).

## 6) Data Model Requirements

### 6.1 `actors` table (YOU CREATE THIS)
Create a new table named `actors` to store actor/character information.

Required columns (4 total):
1. Primary key integer, AUTO_INCREMENT
2. `first` (first name)
3. `last` (last name)
4. date of birth (DOB)

Notes:
- The column names must match what the PHP expects (commonly `first`, `last`, and something like `dob`). Check the starter PHP.
- Store DOB using a date type (`DATE`) unless the starter expects a different format.

### 6.2 `movies` table (PROVIDED IN STARTER SQL)
- Use the provided create-table statements as the source of truth for field names.
- Your PHP code must match the actual column names in the imported table.

### 6.3 Relationship table: movies <-> actors (YOU CREATE THIS)
Create a junction table that associates actors with movies (many-to-many).

Requirements:
- Contains foreign keys (or at least indexed integer columns) referencing:
  - the movies primary key
  - the actors primary key
- Add sample relationships that connect some of your actors to some movies.

## 7) Functional Requirements

### 7.1 Actors panel (tab)
The actors panel must support:
- List actors from the `actors` table.
- Add actor (at least first, last, dob).
- Remove actor (by primary key).

Data requirements (rubric-aligned):
- Add at least 5 actors you choose (not the instructor's).
- Ensure at least 3 have DOBs prior to `1965-01-01` (DOBs may be fictional).

### 7.2 Movies panel (tab)
Create/complete `movies.php` (or equivalent) to support:
- List movies from the `movies` table.
- Add movie (fields based on the existing schema).
- Remove movie (by primary key).

Implementation notes:
- Follow the same pattern used by the actors page (DB connection, prepared statements, output table, form submits).
- Validate you are using the correct field names by checking the DB schema (phpMyAdmin) and/or the starter `.sql`.

### 7.3 Relationship support (movies <-> actors)
At minimum, create the relationship table and insert some rows associating your actors to movies.

Extra credit:
- Add a third panel/tab that lists movies with their corresponding actors (e.g., movie title + actor names).

## 8) Reporting / Export Requirements

### 8.1 `actors.csv` export (rubric-aligned)
Run a query for:
- All actors born on or after `1965-01-01`

Export steps:
- Run the query in phpMyAdmin (with `iit` database selected).
- Use the "Export" tab and export as "CSV for MS Excel".
- Save as `labs/lab09/actors.csv`.

### 8.2 `iit.sql` schema export
Export the SQL schema from phpMyAdmin:
- With `iit` database selected, go to "Export"
- Choose SQL export
- Save as `labs/lab09/iit.sql`

Verification note:
- Confirm `iit.sql` includes the full schema (not just the last table you viewed).

## 9) Setup / Workflow Requirements (as stated in the handout)
- Create a branch for the lab and keep it active until submission.
- Import the provided starter SQL into database `iit`.
- Commit/push often; pull to Azure and test repeatedly.

Handout note:
- The handout text references "Lab 10" in a few places. Treat those as references to the provided starter files in your zip/handout; use the actual filenames present in your downloaded materials as the source of truth.

## 10) Acceptance Criteria (Checklist)
- `actors` table exists with correct column names/types and an auto-increment PK.
- Actors tab loads without DB errors and supports add/remove/list.
- Movies tab loads and supports add/remove/list using the provided movies schema.
- Relationship (junction) table exists and contains sample associations.
- `labs/lab09/actors.csv` exists and contains only actors with DOB >= `1965-01-01`.
- `labs/lab09/iit.sql` exists and contains the full database schema.
- App runs on Azure server and functions via browser (phpMyAdmin and PHP pages).

## 11) Submission
- Commit and push changes to GitHub; pull to Azure and verify.
- Zip the entire `iit` directory and rename to: `lab9-YourName-yourRCSID.zip`
- Submit the zip to LMS by the due date/time shown in LMS.

