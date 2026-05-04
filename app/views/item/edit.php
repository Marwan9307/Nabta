<?php $data['page_title'] = $data['page_title'] ?? 'Edit Item'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Edit Listing</h2>
  <div class="row g-3">
    <div class="col-md-4"><input class="form-control" placeholder="Title" value="<?= $data['title'] ?? '' ?>"></div>
    <div class="col-md-4"><input class="form-control" placeholder="Price" value="<?= $data['price'] ?? '' ?>"></div>
    <div class="col-md-4"><input class="form-control" placeholder="Category" value="<?= $data['category'] ?? '' ?>"></div>
    <div class="col-12">
      <label class="d-block mb-2">Condition issues</label>
      <button type="button" class="btn btn-light border condition-btn me-2">Stain</button>
      <button type="button" class="btn btn-light border condition-btn me-2">Fading</button>
      <button type="button" class="btn btn-light border condition-btn me-2">Hole</button>
      <button type="button" class="btn btn-light border condition-btn">Missing button</button>
    </div>
    <div class="col-12"><strong>Suggested Condition: <span id="conditionResult">Like New</span></strong></div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
