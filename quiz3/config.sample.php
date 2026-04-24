<?php

declare(strict_types=1);

// Copy this file to config.local.php and fill in your real credentials.
// Do not commit config.local.php.

return [
    // Example DSN (MySQL):
    // 'mysql:host=localhost;dbname=iit;charset=utf8mb4'
    'dsn' => 'mysql:host=localhost;dbname=iit;charset=utf8mb4',
    'user' => 'YOUR_DB_USER',
    'pass' => 'YOUR_DB_PASSWORD',

    // Optional: used to hash visitor IPs for privacy.
    // Set to a long random string in config.local.php.
    'ip_salt' => 'CHANGE_ME_TO_A_RANDOM_SECRET',
];

