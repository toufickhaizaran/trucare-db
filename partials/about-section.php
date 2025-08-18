<?php /* partials/about-section.php — self-contained About */ ?>
<section id="about" style="padding:64px 0;background:linear-gradient(0deg,#f7fbff 0%,#ffffff 55%);border-top:1px solid #eaeef3;">
  <style>
    /* Scoped to #about so it won't conflict with anything else */
    #about .wrap{max-width:1200px;margin:0 auto;padding:0 16px;display:grid;gap:36px;align-items:center;justify-items:center}
    @media (min-width:900px){
      #about .wrap{grid-template-columns:1.15fr .85fr}
      #about .wrap > .media{justify-self:end} /* image on right */
    }
    #about .text{max-width:720px;text-align:center}
    #about .title{margin:0 0 10px;font:800 clamp(1.7rem,1.3rem + 1.6vw,2.2rem)/1.2 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:#1e82d1;letter-spacing:.2px}
    #about .lead{margin:0 0 16px;color:#334155;font-size:1.06rem;line-height:1.7}
    #about .list{list-style:none;margin:0 0 16px;padding:0}
    #about .list li{margin:8px 0;display:flex;gap:10px;align-items:flex-start;justify-content:center;text-align:center;color:#0f172a}
    #about .icon{flex:0 0 18px;margin-top:2px;color:#1e82d1}
    #about .icon svg{display:block}
    #about .stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:18px}
    #about .stat{background:#fff;border:1px solid #e6eef6;border-radius:14px;padding:12px 10px;text-align:center;box-shadow:0 4px 18px rgba(15,23,42,.06)}
    #about .n{display:block;font-weight:800;color:#0f172a}
    #about .l{display:block;font-size:.85rem;color:#64748b;margin-top:2px}
    #about .sub{margin:22px 0 10px;font:800 1.1rem system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:#1e82d1;text-align:center}
    #about .leaders{display:grid;gap:10px;grid-template-columns:1fr}
    @media (min-width:600px){#about .leaders{grid-template-columns:1fr 1fr}}
    #about .card{background:#fff;border:1px solid #e6eef6;border-radius:12px;padding:10px 12px;display:flex;gap:10px;align-items:center;justify-content:center;text-align:center;box-shadow:0 4px 18px rgba(15,23,42,.06)}
    #about .av{width:36px;height:36px;border-radius:50%;background:#e8f2fb;color:#1e82d1;display:inline-flex;align-items:center;justify-content:center;font-weight:800}
    #about .name{font-weight:800;color:#0f172a;line-height:1.1}
    #about .role{font-size:.9rem;color:#64748b}
    #about .cta{margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center}
    #about .btn{display:inline-block;padding:9px 14px;border-radius:10px;font-weight:700;text-decoration:none;border:1px solid transparent;transition:transform .15s,box-shadow .15s,background .2s,color .2s,border-color .2s}
    #about .btn:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(30,130,209,.15)}
    #about .primary{background:#1e82d1;color:#fff;border-color:#1e82d1}
    #about .ghost{background:transparent;color:#1e82d1;border-color:#cfe6f8}
    #about .ghost:hover{background:#eaf4fd;border-color:#bcdcf5}
    #about .media{position:relative;display:flex;justify-content:center;align-items:center}
    #about .media::before{content:"";position:absolute;inset:8px;border-radius:12px;background:linear-gradient(135deg,rgba(30,130,209,.08),rgba(30,130,209,0));z-index:0}
    #about .img{position:relative;z-index:1;width:100%;max-width:300px;height:auto;border-radius:12px;border:1px solid #fff;box-shadow:0 10px 25px rgba(15,23,42,.12);transition:transform .3s,box-shadow .3s}
    #about .img:hover{transform:translateY(-5px) scale(1.02);box-shadow:0 18px 40px rgba(15,23,42,.22)}
    @media (max-width:480px){#about{padding:56px 0}#about .wrap{gap:24px}}
  </style>

  <div class="wrap">
    <!-- Text column -->
    <div class="text">
      <h2 class="title">About TruCare Medical</h2>
      <p class="lead">
        TruCare Medical supplies reliable hospital and clinical equipment across Lebanon.
        We partner with trusted manufacturers to deliver safe, modern, and cost-effective solutions
        for healthcare providers, from large hospitals to private clinics.
      </p>

      <ul class="list">
        <li>
          <span class="icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
              <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
            </svg>
          </span>
          Wide catalog: beds, surgical lights, monitors, lab &amp; IVF supplies
        </li>
        <li>
          <span class="icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
              <path d="M12 3l1.6 3.7L17 8.2l-3.4 1.5L12 13l-1.6-3.3L7 8.2l3.4-1.5L12 3zM5 16l.9 2.1L8 19l-2.1.9L5 22l-.9-2.1L2 19l2.1-.9L5 16zm14 0l1 2.3 2.3 1-2.3 1L19 23l-1-2.7-2.3-1 2.3-1L19 16z" stroke="currentColor" stroke-width="1"/>
            </svg>
          </span>
          Fast response, expert guidance, and after-sales support
        </li>
        <li>
          <span class="icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
              <path d="M20.59 13.41L12 22l-8.59-8.59A2 2 0 0 1 3 12V4a2 2 0 0 1 2-2h8a2 2 0 0 1 1.41.59l6.18 6.18a2 2 0 0 1 0 2.82z" stroke="currentColor" stroke-width="2"/>
              <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
            </svg>
          </span>
          Competitive pricing and consistent availability
        </li>
      </ul>

      <div class="stats">
        <div class="stat"><span class="n">3+ yrs</span><span class="l">Serving Lebanon</span></div>
        <div class="stat"><span class="n">500+</span><span class="l">Products Listed</span></div>
        <div class="stat"><span class="n">24/7</span><span class="l">Support</span></div>
      </div>

      <h3 class="sub"></h3>
      <div class="leaders">
        <div class="card">
          <div class="av">MK</div>
          <div>
            <div class="name">Mohamad Khaizaran</div>
            <div class="role">Managing Director</div>
          </div>
        </div>
        <div class="card">
          <div class="av">TD</div>
          <div>
            <div class="name">Tarek Dada</div>
            <div class="role">Chief Executive Officer</div>
          </div>
        </div>
      </div>
    </div> <!-- 👈 this was missing: close .text -->

    <!-- Image column -->
    <div class="media">
      <img class="img"
           src="assets/about-equipment.png"
           onerror="this.src='https://placehold.co/520x350/1e82d1/ffffff?text=About+TruCare+Medical'"
           alt="Hospital equipment by TruCare Medical">
    </div>
  </div>
</section>
