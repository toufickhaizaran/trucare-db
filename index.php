<?php /* index.php — TruCare homepage with image mapping */ ?>
<?php
include __DIR__ . '/partials/header.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>TruCare Medical — Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Tailwind (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Site CSS -->
  <link rel="stylesheet" href="styles.css?v=4">
</head>
<body class="bg-white text-gray-900">

  <!-- Navbar (transparent at top, solid on scroll) -->
  <header class="fixed top-0 left-0 right-0 z-50 transition-all bg-transparent text-white" id="nav">
    <nav class="container-max flex items-center justify-between py-4">
      <a href="/trucare-db/" class="text-lg font-bold">TruCare Medical</a>
      <div class="flex items-center gap-4 text-sm">
        <a class="hover:opacity-80" href="/trucare-db/">Home</a>
        <a class="hover:opacity-80" href="browse.php?table=geotek">Geotek</a>
        <a class="hover:opacity-80" href="browse.php?table=rz">RZ</a>
        <a class="hover:opacity-80" href="#about">About</a>
      </div>
    </nav>
  </header>

  <!-- Hero (full-bleed, 109vh) — IMAGE ONLY -->
  <section class="relative w-full h-[140vh] overflow-hidden -mt-[72px]">
    <img src="assets/hero-medical.png" alt="TruCare Medical" class="absolute inset-0 w-full h-full object-cover" />
    <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-[#1e82d1] via-[#1e82d1]/70 to-transparent"></div>
  </section>

  <!-- Popular Equipment — BLUE section -->
  <section class="bg-[#1e82d1] text-white py-12">
    <div class="container-max text-center">
      <h2 class="text-3xl md:text-4xl font-bold">Popular Equipment</h2>
      <p class="mt-2 opacity-90">A quick look at our most requested items.</p>

      <!-- Loading skeletons -->
      <div id="popular-loading" class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 justify-center">
        <?php for ($i=0; $i<3; $i++): ?>
          <div class="max-w-sm w-full mx-auto rounded-2xl bg-white/95 p-3 shadow-smooth">
            <div class="aspect-[4/3] rounded-xl bg-gray-200 animate-pulse"></div>
            <div class="mt-3 h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
            <div class="mt-2 h-4 bg-gray-100 rounded w-1/2 animate-pulse"></div>
          </div>
        <?php endfor; ?>
      </div>

      <!-- Cards -->
      <div id="popular-grid" class="hidden mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 justify-center"></div>

      <!-- Empty state -->
      <div id="popular-empty" class="hidden mt-8 text-white/80">No items to display right now.</div>
    </div>
  </section>

  <!-- Bottom Section: Heading + Description + Buttons (centered) -->
  <section class="py-16 bg-[#1e82d1] text-center">
    <div class="container-max">
      <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow">
        Hospital &amp; Medical Equipment
      </h1>
      <p class="mt-3 text-white/90 max-w-2xl mx-auto">
        Browse high-quality equipment for hospitals and clinics across Lebanon.
      </p>
      <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="browse.php?table=geotek"
           class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-xl shadow hover:bg-gray-100 transition">
          Browse Geotek
        </a>
        <a href="browse.php?table=rz"
           class="px-6 py-3 bg-white/0 ring-1 ring-white text-white font-semibold rounded-xl hover:bg-white/10 transition">
          Browse RZ
        </a>
      </div>
    </div>
  </section>


  <script>
    // Navbar transparency on scroll
    const nav = document.getElementById('nav');
    const onScroll = () => {
      if (window.scrollY > 20) {
        nav.classList.remove('bg-transparent','text-white');
        nav.classList.add('bg-white','shadow','text-gray-900');
      } else {
        nav.classList.add('bg-transparent','text-white');
        nav.classList.remove('bg-white','shadow','text-gray-900');
      }
    };
    onScroll(); window.addEventListener('scroll', onScroll);

    // ---------- Config ----------
    const POPULAR_CODES = ['GTK01','GOA-S-17-30','GMN-JB-11-100'];
    const API_BASE = 'api.php'; // relative

    // Map "<table>:<code>" -> image path (put files in assets/products/)
    const PRODUCT_IMAGES = {
      'geotek:GMN-JB-11-100': 'assets/products/jamshidi-bone-marrow.png',
      'geotek:GOA-S-17-30':   'assets/products/oocyte-aspiration-single.png',
      'geotek:GTK01':         'assets/products/needle-guide.png'
    };

    // ---------- Helpers ----------
    const $ = sel => document.querySelector(sel);
    const popularGrid = $('#popular-grid');
    const popularLoading = $('#popular-loading');
    const popularEmpty   = $('#popular-empty');

    const escapeHtml = (s='') =>
      s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function cardHTML(p) {
      const key = `${p._table || ''}:${p.code || ''}`;
      const img = PRODUCT_IMAGES[key];
      return `
        <article class="max-w-sm w-full mx-auto transform transition hover:scale-105 hover:shadow-2xl rounded-2xl bg-white text-gray-800 p-3 shadow-smooth">
          <div class="aspect-[4/3] rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden">
            ${img
              ? `<img src="${img}" alt="${escapeHtml(p.name || '')}" class="w-full h-full object-contain" />`
              : `<div class="text-sm text-gray-400">No image</div>`}
          </div>
          <h3 class="mt-3 text-lg font-semibold">${escapeHtml(p.name || '')}</h3>
          <div class="mt-1 text-xs text-gray-500">${escapeHtml(p.section || '')}</div>
          ${p.code ? `<div class="mt-1 text-xs text-gray-500">Code: ${escapeHtml(p.code)}</div>` : ''}
          <a href="product.php?table=${encodeURIComponent(p._table || '')}&code=${encodeURIComponent(p.code || '')}"
             class="mt-3 w-full inline-block text-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
            View details
          </a>
        </article>
      `;
    }

    async function fetchAllProducts() {
      const urls = [
        `${API_BASE}?table=geotek&per=200`,
        `${API_BASE}?table=rz&per=200`
      ];
      const [g, r] = await Promise.all(urls.map(u => fetch(u).then(x => x.json())));
      const gItems = (g.items || []).map(o => ({...o, _table: 'geotek'}));
      const rItems = (r.items || []).map(o => ({...o, _table: 'rz'}));
      return [...gItems, ...rItems];
    }

    function pickPopular(list) {
      if (!Array.isArray(list) || list.length === 0) return [];
      const byCode = new Map(list.map(p => [p.code, p]));
      const picked = [];
      for (const code of POPULAR_CODES) {
        if (byCode.has(code)) picked.push(byCode.get(code));
        if (picked.length === 3) break;
      }
      if (picked.length < 3) {
        for (const p of list) {
          if (!picked.includes(p)) picked.push(p);
          if (picked.length === 3) break;
        }
      }
      return picked.slice(0, 3);
    }

    (async function init() {
      try {
        const all = await fetchAllProducts();
        const top3 = pickPopular(all);

        popularLoading.classList.add('hidden');
        if (top3.length === 0) {
          popularEmpty.classList.remove('hidden');
          return;
        }
        popularGrid.innerHTML = top3.map(cardHTML).join('');
        popularGrid.classList.remove('hidden');
      } catch (err) {
        popularLoading.classList.add('hidden');
        popularEmpty.textContent = 'Failed to load products.';
        popularEmpty.classList.remove('hidden');
        console.error(err);
      }
    })();
  </script>
 
  <?php
$__about = __DIR__ . '/partials/about-section.php';
if (is_file($__about)) { include $__about; }
?>
 <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
