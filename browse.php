<?php
require_once __DIR__ . '/db.php'; // gives you $pdo and h()
include __DIR__ . '/partials/header.php';
// browse.php — product listing (with About section at the end)



// ---- DB (PDO) ----
$DB_HOST = 'localhost';
$DB_NAME = 'trucare_medical_new';
$DB_USER = 'root';
$DB_PASS = '';
$DSN = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

try {
  $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo "<pre>DB connect failed: " . h($e->getMessage()) . "</pre>";
  exit;
}

// ---- Inputs ----
$table   = strtolower($_GET['table'] ?? 'geotek');
$allowed = ['geotek','rz'];
if (!in_array($table, $allowed, true)) $table = 'geotek';

$q       = trim($_GET['q'] ?? '');
$code    = trim($_GET['code'] ?? '');
$section = trim($_GET['section'] ?? '');
$per     = max(1, min(200, (int)($_GET['per'] ?? 24)));
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $per;

// ---- WHERE ----
$where = []; $args = [];
if ($q !== '')       { $where[] = "(name LIKE :q OR code LIKE :q OR description LIKE :q OR section LIKE :q)"; $args[':q'] = "%$q%"; }
if ($code !== '')    { $where[] = "code = :code";       $args[':code'] = $code; }
if ($section !== '') { $where[] = "section = :section"; $args[':section'] = $section; }
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// ---- Total ----
$stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM `$table` $whereSql");
$stmt->execute($args);
$total = (int)$stmt->fetch()['c'];
$pages = max(1, (int)ceil($total / $per));

// ---- Data ----
$sql = "SELECT id, section, name, code, description, size, packaging
        FROM `$table` $whereSql
        ORDER BY id ASC
        LIMIT :per OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($args as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':per', $per, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=h(strtoupper($table))?> — Browse</title>

<link rel="stylesheet" href="styles.css" />

<style>
/* --- Keep homepage blue across this page --- */
html, body {
  background: #1e82d1 !important;
  margin: 0;
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  color: #fff;
}

/* --- Navbar: same behavior as homepage --- */
.header {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: transparent !important;  /* transparent at top */
  color: #fff !important;
  transition: background-color .3s ease, box-shadow .3s ease;
}
.header.scrolled {
  background: #1e82d1 !important;     /* blue after scroll */
  color: #fff !important;
  box-shadow: 0 6px 24px rgba(0,0,0,.08) !important;
}

/* --- Don’t paint over the blue --- */
.content, .section, footer { background: transparent !important; }

/* --- Layout below fixed header --- */
.content { padding-top: 90px; }
.section { max-width: 1100px; margin: 0 auto; padding: 24px; }

/* --- Filters --- */
.filters {
  display: grid; gap: 8px;
  grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
  background: #fff; padding: 16px; border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  color: #111827;
}
.filters input, .filters select, .filters button {
  width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px;
}

/* --- Products grid --- */
.grid { display: grid; gap: 16px; margin-top: 16px; }
.grid-3 { grid-template-columns: 1fr; }
@media (min-width: 900px) { .grid-3 { grid-template-columns: repeat(3, 1fr); } }

.card {
  background: #fff; border-radius: 8px; padding: 16px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  color: #111827; /* ensure dark text inside the white card */
}
.card-title {
  font-size: 1.1rem; font-weight: 700; margin: 0 0 6px;
  color: #111827 !important;  /* product NAME visible */
}
.card p { margin: 4px 0; font-size: 0.92rem; color: #444; }

/* --- Pagination --- */
.pagination { margin-top: 20px; display: flex; gap: 8px; flex-wrap: wrap; }
.pagination a, .pagination span {
  padding: 6px 12px; border: 1px solid #ccc; border-radius: 6px; color: #111827; font-size: 0.9rem; background: #fff;
}
.pagination .current { background: #111827; color: #fff; border-color: #111827; }

/* --- Footer readable on blue --- */
footer { padding: 16px 24px; text-align: center; font-size: 0.9rem; color: #fff; }

/* --- Make links readable on transparent header --- */
.nav-link, .brand { color: inherit !important; text-decoration: none; }
.nav-link:hover { opacity: .9; }

/* --- About section (white card) --- */
#about .card { background:#fff; color:#111827; }
#about .card .card-title { color:#111827; font-weight:800; }
#about .muted { color:#475569; font-size:0.9rem; }
</style>
</head>
<body>

<!-- Navbar (same markup; CSS/JS makes it behave like home) -->
<header class="header" id="siteHeader">
  <nav class="nav" style="display:flex;align-items:center;justify-content:space-between;padding:16px 24px;">
    <a href="index.php" class="brand" style="font-weight:700;font-size:1.125rem;">TruCare Medical</a>
    <div class="nav-right" style="display:flex;gap:16px;align-items:center;">
      <a class="nav-link" href="browse.php?table=geotek">Geotek</a>
      <a class="nav-link" href="browse.php?table=rz">RZ</a>
      <a class="nav-link" href="#about">About</a>
    </div>
  </nav>
</header>

<main class="content">
  <section class="section">
    <h1 style="margin:0 0 10px;"><?=h(strtoupper($table))?> Products</h1>

    <form class="filters" method="get">
      <input type="hidden" name="table" value="<?=h($table)?>" />
      <label>Search<br><input name="q" value="<?=h($q)?>" placeholder="name, code, desc, section" /></label>
      <label>Exact code<br><input name="code" value="<?=h($code)?>" placeholder="e.g. GMN-JB-11-100" /></label>
      <label>Section<br><input name="section" value="<?=h($section)?>" placeholder="e.g. ENT" /></label>
      <label>Per page<br>
        <select name="per">
          <?php foreach([24,48,96,200] as $opt): ?>
            <option value="<?=$opt?>" <?=$per===$opt?'selected':''?>><?=$opt?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <div><br><button type="submit" class="btn" style="padding:8px 14px;border-radius:999px;border:1px solid #111827;background:#fff;cursor:pointer;">Apply</button></div>
    </form>

    <p style="margin-top:8px">Total: <b><?=$total?></b> • Page <?=$page?> / <?=$pages?></p>

    <div class="grid grid-3">
      <?php if (!$rows): ?>
        <p>No results.</p>
      <?php else: foreach ($rows as $r): ?>
        <article class="card">
          <div>
            <h3 class="card-title"><?=h($r['name'])?></h3>
            <?php if (!empty($r['section'])): ?><p><?=h($r['section'])?></p><?php endif; ?>
            <?php if (!empty($r['code'])): ?><p><b>Code:</b> <?=h($r['code'])?></p><?php endif; ?>
            <?php if (!empty($r['description'])): ?><p><?=h($r['description'])?></p><?php endif; ?>
          </div>
          <?php if (!empty($r['code'])): ?>
            <div style="margin-top:10px">
              <a class="btn" href="product.php?table=<?=h($table)?>&code=<?=urlencode($r['code'])?>"
                 style="padding:8px 14px;border-radius:999px;border:1px solid #111827;background:#fff;display:inline-block;text-decoration:none;color:#111827;">
                View details
              </a>
            </div>
          <?php endif; ?>
        </article>
      <?php endforeach; endif; ?>
    </div>

    <?php if ($pages > 1):
      $base = 'browse.php?table=' . urlencode($table)
            . '&q=' . urlencode($q)
            . '&code=' . urlencode($code)
            . '&section=' . urlencode($section)
            . '&per=' . $per
            . '&page=';
    ?>
      <div class="pagination">
        <?php if ($page > 1): ?><a href="<?=$base . ($page-1)?>">← Prev</a><?php endif; ?>
        <span class="current"><?=$page?></span>
        <?php if ($page < $pages): ?><a href="<?=$base . ($page+1)?>">Next →</a><?php endif; ?>
      </div>
    <?php endif; ?>
  </section>

</main>

<footer>
  © <?=date('Y')?> TruCare Medical
</footer>

<script>
// Make navbar act like homepage: transparent at top -> blue after 20px
(function () {
  const header = document.getElementById('siteHeader');
  function onScroll(){
    if (window.scrollY > 20) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
<?php include __DIR__ . '/partials/about-section.php'; ?>
<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
