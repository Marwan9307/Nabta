<?php $data['page_title'] = $data['page_title'] ?? 'My Closet'; require_once __DIR__ . '/../layout/header.php'; ?>

<?php if ($error = Session::flash('error')): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

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
          <div class="d-flex gap-1 flex-wrap">
            <a href="/item/edit/<?= $it['item_id'] ?>" class="btn btn-sm btn-sage-outline">Edit</a>
            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#assessModal<?= $it['item_id'] ?>">Assess</button>
            <form method="post" action="/item/closet/remove"><input type="hidden" name="item_id" value="<?= $it['item_id'] ?>"><button class="btn btn-sm btn-outline-danger" type="submit">Remove</button></form>
          </div>
        </div>
      </div>

      <!-- Assess Modal for each item -->
      <div class="modal fade" id="assessModal<?= $it['item_id'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5>Assess Item: <?= htmlspecialchars($it['title'] ?? '') ?></h5>
              <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="/item/assess/<?= $it['item_id'] ?>">
                <div class="mb-3">
                  <label class="form-label">Tear Check (Are there any tears or holes?)</label>
                  <select name="tear_check" class="form-select" required>
                    <option value="">Select option</option>
                    <option value="No">No (Passed)</option>
                    <option value="Yes">Yes (Failed)</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Cleanliness Check (Is it clean and stain-free?)</label>
                  <select name="cleanliness_check" class="form-select" required>
                    <option value="">Select option</option>
                    <option value="Yes">Yes (Passed)</option>
                    <option value="No">No (Failed)</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Usage Frequency</label>
                  <select name="usage_frequency" class="form-select" required>
                    <option value="">Select usage</option>
                    <option value="Little Usage">Little Usage</option>
                    <option value="Medium Usage">Medium Usage</option>
                    <option value="Too Much Usage">Too Much Usage</option>
                  </select>
                </div>
                <button class="btn btn-clay w-100" type="submit">Submit Assessment</button>
              </form>
            </div>
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
    <input name="price" type="number" class="form-control mb-2" placeholder="Price (EGP)" required>
    <input name="weight" type="number" step="0.1" class="form-control mb-2" placeholder="Weight (kg)">
    <select name="listing_type" class="form-select mb-2"><option value="available">For Sale & Swap</option><option value="sale_only">Sale Only</option><option value="swap_only">Swap Only</option></select>
    <div class="input-group mb-2">
      <input name="negotiation" type="number" min="0" max="100" class="form-control" placeholder="Negotiation percentage (e.g., 10)">
      <span class="input-group-text">%</span>
    </div>
    <input type="file" name="item_photo" class="form-control mb-2">
    <label class="form-check-label mb-2"><input type="checkbox" name="is_upcycled" class="form-check-input"> Upcycled item</label>
    
    <hr class="my-3">
    <h6>Condition Assessment</h6>
    <div class="mb-2">
      <select name="tear_check" class="form-select" required>
        <option value="">Tear Check (Are there any tears or holes?)</option>
        <option value="No">No (Passed)</option>
        <option value="Yes">Yes (Failed)</option>
      </select>
    </div>
    <div class="mb-2">
      <select name="cleanliness_check" class="form-select" required>
        <option value="">Cleanliness Check (Is it clean and stain-free?)</option>
        <option value="Yes">Yes (Passed)</option>
        <option value="No">No (Failed)</option>
      </select>
    </div>
    <div class="mb-3">
      <select name="usage_frequency" class="form-select" required>
        <option value="">Usage Frequency</option>
        <option value="Little Usage">Little Usage</option>
        <option value="Medium Usage">Medium Usage</option>
        <option value="Too Much Usage">Too Much Usage</option>
      </select>
    </div>

    <button class="btn btn-clay w-100" type="submit">Add to Closet & Assess</button>
  </form>
</div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
