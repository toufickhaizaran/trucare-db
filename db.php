<?php
// db.php — central PDO + helpers

$DB_HOST = 'localhost';
$DB_NAME = 'trucare_medical_new'; // ← make sure this matches your actual DB name
$DB_USER = 'root';
$DB_PASS = '';

$DSN = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

try {
  $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo "<pre>DB connect failed: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>";
  exit;
}

// Helper available everywhere that includes db.php
if (!function_exists('h')) {
  function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
