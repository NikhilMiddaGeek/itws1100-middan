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

    /** @var array{dsn:string,user:string,pass:string} $cfg */
    $cfg = require $configPath;

    $pdo = new PDO(
        $cfg['dsn'],
        $cfg['user'],
        $cfg['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    return $pdo;
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

