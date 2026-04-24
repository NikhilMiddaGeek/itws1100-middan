<?php

declare(strict_types=1);

require __DIR__ . '/../inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
    exit;
}

$body = read_json_body();

$pagePath = (string) ($body['page_path'] ?? ($_POST['page_path'] ?? ''));
$pageTitle = (string) ($body['page_title'] ?? ($_POST['page_title'] ?? ''));
$referrer = (string) ($body['referrer'] ?? ($_POST['referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? '')));

$pagePath = clamp_str($pagePath, 255);
$pageTitle = clamp_str($pageTitle, 255);
$referrer = clamp_str($referrer, 255);

// Basic normalization: keep only the path portion if a full URL is provided.
if ($pagePath !== '') {
    $parsed = parse_url($pagePath);
    if (is_array($parsed) && isset($parsed['path'])) {
        $pagePath = (string) $parsed['path'];
    }
}

// Require a reasonable-looking path like "/something".
if ($pagePath === '' || $pagePath[0] !== '/' || strlen($pagePath) > 255) {
    json_response(['ok' => false, 'error' => 'Invalid page_path'], 400);
    exit;
}

$userAgent = clamp_str((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 255);

$ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
// If Azure/Apache provides X-Forwarded-For, take the first IP-like token (best effort).
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $xff = (string) $_SERVER['HTTP_X_FORWARDED_FOR'];
    $first = trim(explode(',', $xff)[0] ?? '');
    if ($first !== '') {
        $ip = $first;
    }
}

$salt = (string) (cfg()['ip_salt'] ?? '');
$ipHash = $ip !== '' ? hash('sha256', $salt . $ip) : null;

try {
    $stmt = db()->prepare(
        'INSERT INTO site_visits (page_path, page_title, referrer, user_agent, ip_hash)
         VALUES (:page_path, :page_title, :referrer, :user_agent, :ip_hash)'
    );
    $stmt->execute([
        'page_path' => $pagePath,
        'page_title' => ($pageTitle !== '' ? $pageTitle : null),
        'referrer' => ($referrer !== '' ? $referrer : null),
        'user_agent' => ($userAgent !== '' ? $userAgent : null),
        'ip_hash' => $ipHash,
    ]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => 'DB error'], 500);
}

