<?php $data['page_title'] = $data['page_title'] ?? 'Preferences'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2 class="mb-3">Style & Fit Preferences</h2>
  <div class="row g-3">
    <div class="col-md-4"><label>Top Size</label><input class="form-control" placeholder="M"></div>
    <div class="col-md-4"><label>Bottom Size</label><input class="form-control" placeholder="32"></div>
    <div class="col-md-4"><label>Shoe Size</label><input class="form-control" type="number" step="0.5" placeholder="37.5"></div>
    <div class="col-12">
      <label class="d-block mb-2">Fabric Sensitivities</label>
      <span class="tag-pill">Wool</span> <span class="tag-pill">Synthetic</span> <span class="tag-pill">Latex</span> <span class="tag-pill">None</span>
    </div>
    <div class="col-12">
      <label class="d-block mb-2">Style Preferences</label>
      <span class="tag-pill">Vintage</span> <span class="tag-pill">Minimalist</span> <span class="tag-pill">Streetwear</span> <span class="tag-pill">Bohemian</span>
    </div>
    <div class="col-12">
      <label class="d-block mb-2">Favorite Palette</label>
      <div class="d-flex gap-3">
        <div class="color-swatch" style="background:linear-gradient(135deg,#5c7a5c,#c4956a)" title="Earthy"></div>
        <div class="color-swatch" style="background:linear-gradient(135deg,#8aa7b5,#a8b89a)" title="Cool"></div>
        <div class="color-swatch" style="background:linear-gradient(135deg,#ddd5c9,#b8aea1)" title="Neutral"></div>
        <div class="color-swatch" style="background:linear-gradient(135deg,#d4a5a5,#e8c4b0)" title="Bold"></div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
