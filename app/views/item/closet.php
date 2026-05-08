<?php $data['page_title'] = $data['page_title'] ?? 'My Closet'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h2>Virtual Closet</h2>
  <button class="btn btn-clay" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
</div>
<div class="row g-3">
  <?php if (!empty($data['closet_items']) && is_array($data['closet_items'])): ?>
    <?php foreach ($data['closet_items'] as $it): ?>
      <?php if (!is_array($it)) continue; ?>
      <div class="col-md-3">
        <div class="card-eco p-2">
          <img src="<?= $it['item_photo'] ?: 'https://placehold.co/300x280' ?>" class="img-fluid rounded mb-2">
          <h6 class="small"><?= htmlspecialchars($it['title'] ?? '') ?></h6>
          <div class="d-flex gap-1">
            <a href="/item/edit/<?= $it['item_id'] ?>" class="btn btn-sm btn-sage-outline">Edit</a>
            <form method="post" action="/item/closet/remove"><input type="hidden" name="item_id" value="<?= $it['item_id'] ?>"><button class="btn btn-sm btn-outline-danger" type="submit">Remove</button></form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12"><p class="text-muted">Your closet is empty. Add items to get started.</p></div>
  <?php endif; ?>
</div>
<div class="modal fade" id="addItemModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Add Item</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
  <form method="post" action="/item/create" enctype="multipart/form-data">
    <input name="title" class="form-control mb-2" placeholder="Name" required>
    <select name="category" class="form-select mb-2"><option value="">Category</option><option>Tops</option><option>Bottoms</option><option>Dresses</option><option>Outerwear</option><option>Accessories</option></select>
    <select name="material_type" class="form-select mb-2"><option value="">Material</option><option>cotton</option><option>polyester</option><option>denim</option><option>wool</option><option>silk</option><option>linen</option><option>leather</option></select>
    <input name="price" type="number" class="form-control mb-2" placeholder="Price (EGP)">
    <input name="weight" type="number" step="0.1" class="form-control mb-2" placeholder="Weight (kg)">
    <select name="listing_type" class="form-select mb-2"><option value="available">For Sale & Swap</option><option value="sale_only">Sale Only</option><option value="swap_only">Swap Only</option></select>
    <input type="file" name="item_photo" class="form-control mb-2">
    <label class="form-check-label mb-2"><input type="checkbox" name="is_upcycled" class="form-check-input"> Upcycled item</label>
    <button class="btn btn-clay w-100" type="submit">Add to Closet</button>
  </form>
</div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
