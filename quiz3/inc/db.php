<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $configPath = __DIR__ . '/../config.local.php';
    if (!file_exists($configPath)) {
        $samplePath = __DIR__ . '/../config.sample.php';
        throw new RuntimeException(
            "Missing config.local.php. Copy config.sample.php to config.local.php and set DB credentials.\nSample: {$samplePath}"
        );
    }

    /** @var array{dsn:string,user:string,pass:string,ip_salt?:string} $cfg */
    $cfg = require $configPath;

    $pdo = new PDO(
        $cfg['dsn'],
        $cfg['user'],
        $cfg['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}

function cfg(): array
{
    $configPath = __DIR__ . '/../config.local.php';
    if (!file_exists($configPath)) {
        return [];
    }
    /** @var array $cfg */
    $cfg = require $configPath;
    return $cfg;
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
}

function read_json_body(): array
{
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || trim($raw) === '') {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function clamp_str(string $value, int $maxLen): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }
    if (function_exists('mb_substr')) {
        return (string) mb_substr($value, 0, $maxLen, 'UTF-8');
    }
    return substr($value, 0, $maxLen);
}

