<?php $data['page_title'] = $data['page_title'] ?? 'Marketplace'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex flex-wrap gap-2 mb-3">
  <select class="form-select w-auto"><option>All Genders</option><option>Men</option><option>Women</option><option>Kids</option></select>
  <span class="tag-pill">Upcycled</span>
  <span class="tag-pill">Swap Available</span>
</div>
<div class="row g-3">
  <?php foreach (($data['items'] ?? [1,2,3,4,5,6]) as $item): ?>
    <div class="col-md-4 col-lg-3">
      <div class="card-eco p-3 h-100 spring">
        <img src="<?= is_array($item) ? ($item['image'] ?? 'https://placehold.co/400x300') : 'https://placehold.co/400x300' ?>" class="img-fluid rounded mb-2" alt="">
        <h6><?= is_array($item) ? ($item['title'] ?? 'Upcycled Piece') : 'Upcycled Piece' ?></h6>
        <div class="small text-muted">EGP <?= is_array($item) ? ($item['price'] ?? 450) : 450 ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
