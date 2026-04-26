<?php

declare(strict_types=1);

require __DIR__ . '/inc/db.php';
require __DIR__ . '/inc/layout.php';

$tab = (string) ($_GET['tab'] ?? 'actors');
$allowed = ['actors', 'movies', 'relations', 'export'];
if (!in_array($tab, $allowed, true)) {
    $tab = 'actors';
}

$flashOk = null;
$flashErr = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'add_actor') {
            $first = trim((string) ($_POST['first'] ?? ''));
            $last = trim((string) ($_POST['last'] ?? ''));
            $birthDate = trim((string) ($_POST['birth_date'] ?? ''));

            if ($first === '' || $last === '' || $birthDate === '') {
                throw new RuntimeException('Actor first/last/birth_date are required.');
            }

            $stmt = db()->prepare('INSERT INTO actors (first, last, birth_date) VALUES (:first, :last, :birth_date)');
            $stmt->execute([
                'first' => $first,
                'last' => $last,
                'birth_date' => $birthDate,
            ]);
            $flashOk = 'Actor added.';
            $tab = 'actors';
        }

        if ($action === 'delete_actor') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = db()->prepare('DELETE FROM actors WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $flashOk = 'Actor removed.';
            $tab = 'actors';
        }

        if ($action === 'add_movie') {
            $title = trim((string) ($_POST['title'] ?? ''));
            $year = (int) ($_POST['year'] ?? 0);

            if ($title === '' || $year <= 0) {
                throw new RuntimeException('Movie title and year are required.');
            }

            $stmt = db()->prepare('INSERT INTO movies (title, `year`) VALUES (:title, :year)');
            $stmt->execute([
                'title' => $title,
                'year' => $year,
            ]);
            $flashOk = 'Movie added.';
            $tab = 'movies';
        }

        if ($action === 'delete_movie') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = db()->prepare('DELETE FROM movies WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $flashOk = 'Movie removed.';
            $tab = 'movies';
        }

        if ($action === 'add_relation') {
            $movieId = (int) ($_POST['movie_id'] ?? 0);
            $actorId = (int) ($_POST['actor_id'] ?? 0);

            if ($movieId <= 0 || $actorId <= 0) {
                throw new RuntimeException('Pick both a movie and an actor.');
            }

            $stmt = db()->prepare('INSERT IGNORE INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)');
            $stmt->execute([
                'movie_id' => $movieId,
                'actor_id' => $actorId,
            ]);
            $flashOk = 'Relation added.';
            $tab = 'relations';
        }

        if ($action === 'delete_relation') {
            $movieId = (int) ($_POST['movie_id'] ?? 0);
            $actorId = (int) ($_POST['actor_id'] ?? 0);

            $stmt = db()->prepare('DELETE FROM movie_actors WHERE movie_id = :movie_id AND actor_id = :actor_id');
            $stmt->execute([
                'movie_id' => $movieId,
                'actor_id' => $actorId,
            ]);
            $flashOk = 'Relation removed.';
            $tab = 'relations';
        }
    }
} catch (Throwable $e) {
    $flashErr = $e->getMessage();
}

render_header('Lab 09: PHP & MySQL', $tab);

if ($flashOk) {
    echo '<div class="msg ok">' . h($flashOk) . "</div>\n";
}
if ($flashErr) {
    echo '<div class="msg err">' . h($flashErr) . "</div>\n";
}

try {
    if ($tab === 'actors') {
        echo "<h2>Actors</h2>\n";

        $actors = db()->query('SELECT id, first, last, birth_date FROM actors ORDER BY last, first')->fetchAll();

        echo "<table>\n<thead><tr><th>ID</th><th>First</th><th>Last</th><th>Birth Date</th><th>Remove</th></tr></thead>\n<tbody>\n";
        foreach ($actors as $a) {
            echo "<tr>";
            echo "<td>" . h((string) $a['id']) . "</td>";
            echo "<td>" . h((string) $a['first']) . "</td>";
            echo "<td>" . h((string) $a['last']) . "</td>";
            echo "<td>" . h((string) $a['birth_date']) . "</td>";
            echo "<td>\n";
            echo "  <form method=\"post\" style=\"margin:0;padding:0;border:0\">\n";
            echo "    <input type=\"hidden\" name=\"action\" value=\"delete_actor\">\n";
            echo "    <input type=\"hidden\" name=\"id\" value=\"" . h((string) $a['id']) . "\">\n";
            echo "    <button type=\"submit\">Delete</button>\n";
            echo "  </form>\n";
            echo "</td>";
            echo "</tr>\n";
        }
        echo "</tbody></table>\n";

        echo "<form method=\"post\">\n";
        echo "  <h3>Add Actor</h3>\n";
        echo "  <input type=\"hidden\" name=\"action\" value=\"add_actor\">\n";
        echo "  <div class=\"row\">\n";
        echo "    <div><label>First</label><input name=\"first\" required></div>\n";
        echo "    <div><label>Last</label><input name=\"last\" required></div>\n";
        echo "    <div><label>Birth Date</label><input name=\"birth_date\" type=\"date\" required></div>\n";
        echo "  </div>\n";
        echo "  <p><button type=\"submit\">Add</button></p>\n";
        echo "</form>\n";
    }

    if ($tab === 'movies') {
        echo "<h2>Movies</h2>\n";

        $movies = db()->query('SELECT id, title, `year` AS year FROM movies ORDER BY `year` DESC, title')->fetchAll();

        echo "<table>\n<thead><tr><th>ID</th><th>Title</th><th>Year</th><th>Remove</th></tr></thead>\n<tbody>\n";
        foreach ($movies as $m) {
            echo "<tr>";
            echo "<td>" . h((string) $m['id']) . "</td>";
            echo "<td>" . h((string) $m['title']) . "</td>";
            echo "<td>" . h((string) $m['year']) . "</td>";
            echo "<td>\n";
            echo "  <form method=\"post\" style=\"margin:0;padding:0;border:0\">\n";
            echo "    <input type=\"hidden\" name=\"action\" value=\"delete_movie\">\n";
            echo "    <input type=\"hidden\" name=\"id\" value=\"" . h((string) $m['id']) . "\">\n";
            echo "    <button type=\"submit\">Delete</button>\n";
            echo "  </form>\n";
            echo "</td>";
            echo "</tr>\n";
        }
        echo "</tbody></table>\n";

        echo "<form method=\"post\">\n";
        echo "  <h3>Add Movie</h3>\n";
        echo "  <input type=\"hidden\" name=\"action\" value=\"add_movie\">\n";
        echo "  <div class=\"row\">\n";
        echo "    <div><label>Title</label><input name=\"title\" required></div>\n";
        echo "    <div><label>Year</label><input name=\"year\" type=\"number\" min=\"1888\" max=\"2100\" required></div>\n";
        echo "  </div>\n";
        echo "  <p><button type=\"submit\">Add</button></p>\n";
        echo "</form>\n";
    }

    if ($tab === 'relations') {
        echo "<h2>Movie <-> Actor Relations (Bonus)</h2>\n";

        $movies = db()->query('SELECT id, title, `year` AS year FROM movies ORDER BY `year` DESC, title')->fetchAll();
        $actors = db()->query('SELECT id, first, last FROM actors ORDER BY last, first')->fetchAll();

        $rows = db()->query(
            'SELECT m.id AS movie_id, m.title, m.`year` AS year, a.id AS actor_id, a.first, a.last
             FROM movie_actors ma
             JOIN movies m ON m.id = ma.movie_id
             JOIN actors a ON a.id = ma.actor_id
             ORDER BY m.`year` DESC, m.title, a.last, a.first'
        )->fetchAll();

        echo "<table>\n<thead><tr><th>Movie</th><th>Year</th><th>Actor</th><th>Remove</th></tr></thead>\n<tbody>\n";
        foreach ($rows as $r) {
            echo "<tr>";
            echo "<td>" . h((string) $r['title']) . "</td>";
            echo "<td>" . h((string) $r['year']) . "</td>";
            echo "<td>" . h((string) $r['first'] . ' ' . (string) $r['last']) . "</td>";
            echo "<td>\n";
            echo "  <form method=\"post\" style=\"margin:0;padding:0;border:0\">\n";
            echo "    <input type=\"hidden\" name=\"action\" value=\"delete_relation\">\n";
            echo "    <input type=\"hidden\" name=\"movie_id\" value=\"" . h((string) $r['movie_id']) . "\">\n";
            echo "    <input type=\"hidden\" name=\"actor_id\" value=\"" . h((string) $r['actor_id']) . "\">\n";
            echo "    <button type=\"submit\">Delete</button>\n";
            echo "  </form>\n";
            echo "</td>";
            echo "</tr>\n";
        }
        echo "</tbody></table>\n";

        echo "<form method=\"post\">\n";
        echo "  <h3>Add Relation</h3>\n";
        echo "  <input type=\"hidden\" name=\"action\" value=\"add_relation\">\n";
        echo "  <div class=\"row\">\n";
        echo "    <div><label>Movie</label><select name=\"movie_id\" required>\n";
        echo "      <option value=\"\">Select…</option>\n";
        foreach ($movies as $m) {
            $label = (string) $m['title'] . ' (' . (string) $m['year'] . ')';
            echo "      <option value=\"" . h((string) $m['id']) . "\">" . h($label) . "</option>\n";
        }
        echo "    </select></div>\n";
        echo "    <div><label>Actor</label><select name=\"actor_id\" required>\n";
        echo "      <option value=\"\">Select…</option>\n";
        foreach ($actors as $a) {
            $label = (string) $a['first'] . ' ' . (string) $a['last'];
            echo "      <option value=\"" . h((string) $a['id']) . "\">" . h($label) . "</option>\n";
        }
        echo "    </select></div>\n";
        echo "  </div>\n";
        echo "  <p><button type=\"submit\">Add</button></p>\n";
        echo "</form>\n";
    }

    if ($tab === 'export') {
        echo "<h2>Export Actors CSV</h2>\n";
        echo "<p><a href=\"export_actors_csv.php\">Download actors.csv</a></p>\n";
    }
} catch (Throwable $e) {
    echo '<div class="msg err">' . h($e->getMessage()) . "</div>\n";
    echo "<p><small>If this is your first run, import `labs/lab09/iit-lab9start.sql` into the `iit` database and create `labs/lab09/config.local.php`.</small></p>\n";
}

render_footer();

