<?php $data['page_title'] = $data['page_title'] ?? 'Marketplace'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex flex-wrap gap-2 mb-3">
  <form method="get" action="/marketplace" class="d-flex flex-wrap gap-2">
    <input type="text" name="q" class="form-control w-auto" placeholder="Search..." value="<?= $_GET['q'] ?? '' ?>">
    <select name="sort" class="form-select w-auto">
      <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest</option>
      <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price Low-High</option>
      <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price High-Low</option>
    </select>
    <button class="btn btn-sage-outline btn-sm" type="submit">Filter</button>
  </form>
</div>
<div class="row g-3">
  <?php if (!empty($data['items']) && is_array($data['items'])): ?>
    <?php foreach ($data['items'] as $item): ?>
      <?php if (!is_array($item)) continue; ?>
      <div class="col-md-4 col-lg-3">
        <a href="/marketplace/show/<?= $item['item_id'] ?>" class="text-decoration-none">
          <div class="card-eco p-3 h-100 spring">
            <img src="<?= $item['item_photo'] ?: 'https://placehold.co/400x300' ?>" class="img-fluid rounded mb-2" alt="">
            <h6><?= htmlspecialchars($item['title'] ?? 'Upcycled Piece') ?></h6>
            <div class="small text-muted">EGP <?= $item['item_price'] ?? 0 ?></div>
            <?php if ($item['is_upcycled']): ?><span class="badge bg-success">Upcycled</span><?php endif; ?>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12"><p class="text-muted">No items found. Try adjusting your filters.</p></div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
