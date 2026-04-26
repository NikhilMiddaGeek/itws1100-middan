<?php
// GitHub Webhook Auto-Deploy (reference implementation for Lab 10)
//
// Intended server location: /var/www/html/deploy.php
// Repo location on server: /var/www/html/iit
//
// This script:
// - Verifies X-Hub-Signature-256 (HMAC-SHA256)
// - Only deploys pushes to main
// - Runs `git pull origin main` as www-data
// - Logs all attempts to /var/log/deploy.log

declare(strict_types=1);

// Prefer an environment variable if you set one up in Apache, but allow the lab-style
// inline secret for simplicity.
$secret = getenv('DEPLOY_SECRET');
if ($secret === false || trim($secret) === '') {
    $secret = 'YOUR_SECRET_HERE';
}
if ($secret === 'YOUR_SECRET_HERE') {
    http_response_code(500);
    echo 'Server misconfigured: set DEPLOY_SECRET or edit $secret';
    exit;
}

$repoPath = '/var/www/html/iit';
$logFile = '/var/log/deploy.log';

function log_line(string $logFile, string $message): void {
    @file_put_contents($logFile, date('Y-m-d H:i:s') . " " . $message . "\n", FILE_APPEND);
}

$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input') ?: '';
$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    log_line($logFile, 'REJECTED: invalid signature');
    echo 'Invalid signature';
    exit;
}

$data = json_decode($payload, true);
$branch = is_array($data) ? ($data['ref'] ?? '') : '';

if ($branch !== 'refs/heads/main') {
    http_response_code(200);
    log_line($logFile, "SKIPPED: push to {$branch}");
    echo 'Not main branch';
    exit;
}

$output = [];
$code = 0;
exec("cd " . escapeshellarg($repoPath) . " && sudo -u www-data git pull origin main 2>&1", $output, $code);

$result = trim(implode("\n", $output));
$status = $code === 0 ? 'SUCCESS' : 'FAILED';
log_line($logFile, "{$status}: {$result}");

http_response_code($code === 0 ? 200 : 500);
echo $status;
