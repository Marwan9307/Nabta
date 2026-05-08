<?php
$data['page_title'] = $data['page_title'] ?? 'NABTA | Home';
require_once __DIR__ . '/../layout/header.php';
?>
<script>document.body.dataset.cursorTrail='hero';</script>

<section class="py-5">
  <div class="row align-items-center g-4">
    <div class="col-lg-7">
      <h1 class="hero-title mb-3"><?= $data['hero_title'] ?? 'Wear your values. Refresh your closet.' ?></h1>
      <p class="lead"><?= $data['hero_subtitle'] ?? 'Sustainable fashion marketplace for swaps, upcycling, and conscious buying.' ?></p>
      <div class="d-flex gap-2">
        <a href="../marketplace" class="btn btn-clay btn-lg rounded-pill px-4">Shop Now</a>
        <button class="btn btn-sage-outline btn-lg rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#applyUpcyclerModal">Start Upcycling</button>
      </div>
    </div>
  </div>
</section>

<section class="py-4">
  <h2 class="mb-3">How NABTA Works</h2>
  <div class="row g-3">
    <div class="col-md-4"><div class="card-eco p-4 spring"><h5>⟳ Browse & Swap</h5><p class="mb-0">Discover items and exchange thoughtfully.</p></div></div>
    <div class="col-md-4"><div class="card-eco p-4 spring"><h5>✦ Upcycle</h5><p class="mb-0">Turn old pieces into beautiful stories.</p></div></div>
    <div class="col-md-4"><div class="card-eco p-4 spring"><h5>👥 Earn Credits</h5><p class="mb-0">Contribute and collect eco-rewards.</p></div></div>
  </div>
</section>

<section class="py-4 transform-strip p-4">
  <h2 class="mb-3">The Transformation</h2>
  <div class="row align-items-center g-3">
    <div class="col-md-5 text-center">
      <img class="transformation-img before" src="../../../assets/OldCl.png" alt="Old clothes">
      <div>🐛</div>
    </div>
    <div class="col-md-2 text-center fs-1">➜</div>
    <div class="col-md-5 text-center">
      <img class="transformation-img after" src="../../../assets/NewCl.png" alt="Upcycled pieces">
      <div>🦋</div>
    </div>
  </div>
  <p class="mt-3 eco-label fs-5">They were sad thinking their fate was a kitchen towel... but they had a second story to tell.</p>
</section>

<section class="py-4">
  <h2 class="mb-3">Impact Counter</h2>
  <div class="row g-3">
    <div class="col-md-6"><div class="metric-box"><small>Liters of Water Saved</small><div class="display-6" data-count-target data-count-base="<?= $data['water_base'] ?? 1200 ?>">0</div></div></div>
    <div class="col-md-6"><div class="metric-box"><small>KG of CO2 Reduced</small><div class="display-6" data-count-target data-count-base="<?= $data['co2_base'] ?? 560 ?>">0</div></div></div>
  </div>
</section>

<div class="modal fade" id="applyUpcyclerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-eco border-0">
      <div class="modal-header"><h5>Apply for Upcycler Status</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <form class="modal-body" method="post" action="/profile/apply-role" enctype="multipart/form-data">
        <div class="mb-3"><label class="form-label">Portfolio Upload</label><input class="form-control" type="file" name="portfolio"></div>
        <div class="mb-3"><label class="form-label">Why I want to join</label><textarea class="form-control" rows="3" name="motivation"></textarea></div>
        <button class="btn btn-clay w-100" type="submit">Submit</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
