<?php
// api.php
// Simple JSON API for tables: geotek, rz
// Supports: pagination (?page=1&per=50), free text search (?q=...), filter by section (?section=ENT), exact code (?code=GTK01)

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // remove or restrict in production

// ---- DB CONFIG (edit if needed) ----
$DB_HOST = 'localhost';
$DB_NAME = 'trucare_medical_new';
$DB_USER = 'root';
$DB_PASS = ''; // set if you use a password
$DSN = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

// ---- Connect (PDO) ----
try {
  $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connect failed', 'details' => $e->getMessage()]);
  exit;
}

// ---- Params ----
$table   = isset($_GET['table']) ? strtolower($_GET['table']) : 'geotek'; // geotek|rz
$allowed = ['geotek', 'rz'];
if (!in_array($table, $allowed, true)) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid table. Use geotek or rz']);
  exit;
}

$q       = isset($_GET['q']) ? trim($_GET['q']) : '';
$code    = isset($_GET['code']) ? trim($_GET['code']) : '';
$section = isset($_GET['section']) ? trim($_GET['section']) : '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$per     = max(1, min(200, (int)($_GET['per'] ?? 50)));
$offset  = ($page - 1) * $per;

// ---- Build WHERE ----
$where = [];
$args  = [];

if ($q !== '') {
  $where[] = "(name LIKE :q OR code LIKE :q OR description LIKE :q OR section LIKE :q)";
  $args[':q'] = "%$q%";
}
if ($code !== '') {
  $where[] = "code = :code";
  $args[':code'] = $code;
}
if ($section !== '') {
  $where[] = "section = :section";
  $args[':section'] = $section;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// ---- Count ----
$countSql = "SELECT COUNT(*) AS c FROM `$table` $whereSql";
$stmt = $pdo->prepare($countSql);
$stmt->execute($args);
$total = (int)$stmt->fetch()['c'];

// ---- Data ----
$dataSql = "SELECT id, section, name, code, description, size, packaging
            FROM `$table`
            $whereSql
            ORDER BY id ASC
            LIMIT :per OFFSET :offset";

$stmt = $pdo->prepare($dataSql);
// bind named params first
foreach ($args as $k => $v) $stmt->bindValue($k, $v);
// then numeric params
$stmt->bindValue(':per', $per, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

// ---- Output ----
echo json_encode([
  'table'  => $table,
  'page'   => $page,
  'per'    => $per,
  'total'  => $total,
  'pages'  => (int)ceil($total / $per),
  'items'  => $rows
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
