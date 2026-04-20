<?php

declare(strict_types=1);

function render_header(string $title, string $activeTab): void
{
    $tabs = [
        'actors' => 'Actors',
        'movies' => 'Movies',
        'relations' => 'Relations (Bonus)',
        'export' => 'Export CSV',
    ];

    echo "<!doctype html>\n";
    echo "<html lang=\"en\">\n";
    echo "<head>\n";
    echo "  <meta charset=\"utf-8\">\n";
    echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
    echo "  <title>" . h($title) . "</title>\n";
    echo "  <style>\n";
    echo "    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:24px;line-height:1.35}\n";
    echo "    nav a{display:inline-block;margin-right:10px;padding:8px 10px;border:1px solid #ccc;border-radius:8px;text-decoration:none;color:#111}\n";
    echo "    nav a.active{background:#111;color:#fff;border-color:#111}\n";
    echo "    table{border-collapse:collapse;width:100%;margin-top:12px}\n";
    echo "    th,td{border:1px solid #ddd;padding:8px;text-align:left}\n";
    echo "    th{background:#f6f6f6}\n";
    echo "    form{margin-top:12px;padding:12px;border:1px solid #ddd;border-radius:10px}\n";
    echo "    .row{display:flex;gap:10px;flex-wrap:wrap}\n";
    echo "    label{display:block;font-size:12px;color:#333;margin-bottom:4px}\n";
    echo "    input,select{padding:8px;border:1px solid #ccc;border-radius:8px;min-width:200px}\n";
    echo "    button{padding:9px 12px;border:1px solid #111;background:#111;color:#fff;border-radius:10px;cursor:pointer}\n";
    echo "    .msg{margin-top:12px;padding:10px;border-radius:10px}\n";
    echo "    .ok{background:#e8fff0;border:1px solid #b6f2cc}\n";
    echo "    .err{background:#fff1f1;border:1px solid #f1b6b6}\n";
    echo "    small{color:#444}\n";
    echo "  </style>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "  <h1>" . h($title) . "</h1>\n";
    echo "  <nav>\n";
    foreach ($tabs as $key => $label) {
        $class = ($activeTab === $key) ? 'active' : '';
        echo "    <a class=\"{$class}\" href=\"index.php?tab={$key}\">" . h($label) . "</a>\n";
    }
    echo "  </nav>\n";
}

function render_footer(): void
{
    echo "</body>\n</html>\n";
}

