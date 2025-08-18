<?php
include __DIR__ . '/partials/header.php';
// product.php — single product page (uses db.php + shared About + Footer)
require_once __DIR__ . '/db.php'; // defines $pdo and h()

// ---- Inputs ----
$table   = strtolower($_GET['table'] ?? 'geotek');
$allowed = ['geotek','rz'];
if (!in_array($table, $allowed, true)) $table = 'geotek';

// Prefer ?code=..., also accept ?id=...
$code = trim($_GET['code'] ?? '');
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ---- Fetch product (ONLY existing columns) ----
if ($code !== '') {
  $stmt = $pdo->prepare("
    SELECT id, section, name, code, description, size, packaging
    FROM `$table` WHERE code = :code LIMIT 1
  ");
  $stmt->execute([':code' => $code]);
} else {
  if ($id <= 0) { echo "<p>No product selected.</p>"; exit; }
  $stmt = $pdo->prepare("
    SELECT id, section, name, code, description, size, packaging
    FROM `$table` WHERE id = :id LIMIT 1
  ");
  $stmt->execute([':id' => $id]);
}
$product = $stmt->fetch();
if (!$product) { echo "<p>Product not found.</p>"; exit; }

// ---- Try to resolve an image by code (optional) ----
function findProductImage(string $code): string {
  $base = __DIR__ . '/assets/products/';
  $relBase = 'assets/products/';
  $candidates = [
    $code . '.png',
    $code . '.jpg',
    $code . '.jpeg',
    $code . '.webp',
    strtolower($code) . '.png',
    strtolower($code) . '.jpg',
    strtolower($code) . '.jpeg',
    strtolower($code) . '.webp',
  ];
  foreach ($candidates as $file) {
    if (is_file($base . $file)) return $relBase . $file;
  }
  return 'assets/placeholder.png'; // fallback
}
$imgSrc = findProductImage((string)($product['code'] ?? ''));
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=h($product['name'])?> — Product Details</title>
<link rel="stylesheet" href="styles.css?v=21">


<style>
/* Minimal page-specific styling to match the site look */
:root { --blue:#1e82d1; --ink:#111827; }
html, body { margin:0; background:var(--blue); color:#fff; font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }

/* Header / Navbar — solid and visible */
.header {
  position:fixed; top:0; left:0; right:0; z-index:1000;
  background:#ffffff; color:var(--ink);
  box-shadow:0 6px 24px rgba(0,0,0,0.08);
}
.nav { max-width:1100px; margin:0 auto; padding:16px 24px; display:flex; justify-content:space-between; align-items:center; }
.brand, .nav-link { color:inherit; text-decoration:none; }
.nav-right { display:flex; gap:16px; }

/* Layout */
.content { padding-top:90px; }
.section { max-width:1100px; margin:0 auto; padding:24px; }
.card { background:#fff; color:var(--ink); border-radius:12px; padding:24px; box-shadow:0 1px 6px rgba(0,0,0,0.06); }

/* Product layout */
.product-wrap { display:grid; gap:20px; grid-template-columns:1fr; }
@media (min-width: 900px) { .product-wrap { grid-template-columns: 1.1fr .9fr; } }

.media {
  background:#f1f5f9; border-radius:12px; min-height:280px;
  display:flex; align-items:center; justify-content:center; overflow:hidden;
}
.media img { width:100%; height:100%; object-fit:contain; }

.meta { display:flex; gap:8px; flex-wrap:wrap; margin:6px 0 10px; }
.badge { background:#fef9c3; color:#854d0e; padding:4px 10px; border-radius:999px; font-size:.8rem; }
.code  { background:#ecfeff; color:#155e75; padding:4px 10px; border-radius:999px; font-size:.8rem; }

.attrs { margin:14px 0; padding-left:18px; color:#374151; }
.attrs li { margin:4px 0; }

.actions { margin-top:16px; display:flex; gap:10px; flex-wrap:wrap; }
.btn { padding:10px 14px; border-radius:10px; border:1px solid var(--ink); background:#fff; color:var(--ink); text-decoration:none; display:inline-block; }
.btn.primary { background:var(--blue); color:#fff; border-color:var(--blue); }

/* Footer (uses your shared .site-footer if included) */
footer { background:transparent; }
</style>
</head>
<body>

<!-- Header -->
<header class="header" id="siteHeader">
  <div class="nav">
    <a href="index.php" class="brand">TruCare Medical</a>
    <nav class="nav-right">
      <a class="nav-link" href="/trucare-db/">Home</a>

      <a class="nav-link" href="browse.php?table=geotek">Geotek</a>
      <a class="nav-link" href="browse.php?table=rz">RZ</a>
      <a class="nav-link" href="#about">About</a>
    </nav>
  </div>
</header>
<main id="product" class="content">
  <section class="section">
    <div class="card">
      <div class="product-wrap">
        <div class="info">
          <h1><?=h($product['name'])?></h1>
          <span class="title-accent" aria-hidden="true"></span>

          <div class="meta">
            <?php if (!empty($product['section'])): ?>
              <span class="badge"><?=h($product['section'])?></span>
            <?php endif; ?>
            <?php if (!empty($product['code'])): ?>
              <span class="code">Code: <?=h($product['code'])?></span>
            <?php endif; ?>
          </div>

          <hr class="rule">

          <?php if (!empty($product['description'])): ?>
            <p class="desc"><?=nl2br(h($product['description']))?></p>
          <?php endif; ?>

          <?php if (!empty($product['size']) || !empty($product['packaging'])): ?>
            <ul class="attrs">
              <?php if (!empty($product['size'])): ?>
                <li><strong>Size:</strong> <?=h($product['size'])?></li>
              <?php endif; ?>
              <?php if (!empty($product['packaging'])): ?>
                <li><strong>Packaging:</strong> <?=h($product['packaging'])?></li>
              <?php endif; ?>
            </ul>
          <?php endif; ?>

          <div class="actions">
            <a class="btn" href="browse.php?table=<?=h($table)?>">← Back to <?=h(strtoupper($table))?></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Shared About Section (optional) -->
  <?php
    $__about = __DIR__ . '/partials/about-section.php';
    if (!is_file($__about)) { $__about = __DIR__ . '/about-section.php'; }
    if (is_file($__about)) { include $__about; }
  ?>
</main>



<?php
  // Shared footer (optional)
  $__footer = __DIR__ . '/partials/footer.php';
  if (!is_file($__footer)) { $__footer = __DIR__ . '/footer.php'; }
  if (is_file($__footer)) { include $__footer; }
?>

</body>
</html>
