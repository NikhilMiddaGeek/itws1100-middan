<?php

declare(strict_types=1);

require __DIR__ . '/inc/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="actors.csv"');

$stmt = db()->prepare('SELECT id, first, last, birth_date FROM actors WHERE birth_date >= :cutoff ORDER BY birth_date, last, first');
$stmt->execute(['cutoff' => '1965-01-01']);
$rows = $stmt->fetchAll();

$out = fopen('php://output', 'w');
if ($out === false) {
    throw new RuntimeException('Failed to open output stream.');
}

fputcsv($out, ['id', 'first', 'last', 'birth_date']);
foreach ($rows as $r) {
    fputcsv($out, [$r['id'], $r['first'], $r['last'], $r['birth_date']]);
}
fclose($out);

