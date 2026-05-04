<?php $data['page_title'] = $data['page_title'] ?? 'Create Item'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Add Item to Closet</h2>
  <div class="row g-3">
    <div class="col-md-6"><input class="form-control" placeholder="Name"></div>
    <div class="col-md-6"><input type="file" class="form-control"></div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
