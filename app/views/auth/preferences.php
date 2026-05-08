<?php $data['page_title'] = $data['page_title'] ?? 'Preferences'; require_once __DIR__ . '/../layout/header.php'; ?>
<form method="post" action="/auth/preferences">
<div class="card-eco p-4">
  <h2 class="mb-3">Style & Fit Preferences</h2>
  <div class="row g-3">
    <div class="col-md-4"><label>Top Size</label><input name="top_size" class="form-control" placeholder="M"></div>
    <div class="col-md-4"><label>Bottom Size</label><input name="bottom_size" class="form-control" placeholder="32"></div>
    <div class="col-md-4"><label>Shoe Size</label><input name="shoe_size" class="form-control" type="number" step="0.5" placeholder="37.5"></div>
    <div class="col-12">
      <label class="d-block mb-2">Fabric Sensitivities</label>
      <label class="tag-pill"><input type="checkbox" name="fabric_sensitivity" value="wool"> Wool</label>
      <label class="tag-pill"><input type="checkbox" name="fabric_sensitivity" value="synthetic"> Synthetic</label>
      <label class="tag-pill"><input type="checkbox" name="fabric_sensitivity" value="latex"> Latex</label>
      <label class="tag-pill"><input type="checkbox" name="fabric_sensitivity" value="none"> None</label>
    </div>
    <div class="col-12">
      <label class="d-block mb-2">Style Preferences</label>
      <label class="tag-pill"><input type="radio" name="style_preference" value="vintage"> Vintage</label>
      <label class="tag-pill"><input type="radio" name="style_preference" value="minimalist"> Minimalist</label>
      <label class="tag-pill"><input type="radio" name="style_preference" value="streetwear"> Streetwear</label>
      <label class="tag-pill"><input type="radio" name="style_preference" value="bohemian"> Bohemian</label>
    </div>
    <div class="col-12">
      <label class="d-block mb-2">Favorite Palette</label>
      <div class="d-flex gap-3">
        <label><input type="radio" name="color_palette" value="earthy" class="d-none"><div class="color-swatch" style="background:linear-gradient(135deg,#5c7a5c,#c4956a)" title="Earthy"></div></label>
        <label><input type="radio" name="color_palette" value="cool" class="d-none"><div class="color-swatch" style="background:linear-gradient(135deg,#8aa7b5,#a8b89a)" title="Cool"></div></label>
        <label><input type="radio" name="color_palette" value="neutral" class="d-none"><div class="color-swatch" style="background:linear-gradient(135deg,#ddd5c9,#b8aea1)" title="Neutral"></div></label>
        <label><input type="radio" name="color_palette" value="bold" class="d-none"><div class="color-swatch" style="background:linear-gradient(135deg,#d4a5a5,#e8c4b0)" title="Bold"></div></label>
      </div>
    </div>
    <div class="col-12"><button class="btn btn-clay" type="submit">Save Preferences</button></div>
  </div>
</div>
</form>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
