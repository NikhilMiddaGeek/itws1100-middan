<?php

declare(strict_types=1);

require __DIR__ . '/../inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
    exit;
}

$startRaw = clamp_str((string) ($_GET['start'] ?? ''), 32);
$endRaw = clamp_str((string) ($_GET['end'] ?? ''), 32);
$pagePath = clamp_str((string) ($_GET['page_path'] ?? ''), 255);

$where = [];
$params = [];

if ($startRaw !== '') {
    $start = date_create($startRaw);
    if (!$start) {
        json_response(['ok' => false, 'error' => 'Invalid start'], 400);
        exit;
    }
    $where[] = 'visited_at >= :start';
    $params['start'] = $start->format('Y-m-d H:i:s');
}

if ($endRaw !== '') {
    $end = date_create($endRaw);
    if (!$end) {
        json_response(['ok' => false, 'error' => 'Invalid end'], 400);
        exit;
    }
    $where[] = 'visited_at <= :end';
    $params['end'] = $end->format('Y-m-d H:i:s');
}

if ($pagePath !== '') {
    $parsed = parse_url($pagePath);
    if (is_array($parsed) && isset($parsed['path'])) {
        $pagePath = (string) $parsed['path'];
    }
    $where[] = 'page_path = :page_path';
    $params['page_path'] = $pagePath;
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Keep this fixed to avoid user-controlled LIMIT in SQL (still meets assignment requirements).
$limit = 50;

try {
    $stmt = db()->prepare(
        "SELECT page_path, page_title, referrer, visited_at
         FROM site_visits
         {$whereSql}
         ORDER BY visited_at DESC, id DESC
         LIMIT {$limit}"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'limit' => $limit,
        'visits' => $rows,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'DB error'], 500);
}

