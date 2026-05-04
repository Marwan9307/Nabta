<?php $data['page_title'] = $data['page_title'] ?? 'My Closet'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between mb-3">
  <h2>Virtual Closet</h2>
  <button class="btn btn-clay" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
</div>
<ul class="nav nav-tabs mb-3">
  <li class="nav-item"><button class="nav-link active">My Items</button></li>
  <li class="nav-item"><button class="nav-link">My Listings</button></li>
  <li class="nav-item"><button class="nav-link">Single item / Multiple bulk</button></li>
</ul>
<div class="row g-3">
  <?php foreach (($data['closet_items'] ?? [1,2,3,4]) as $it): ?>
    <div class="col-md-3"><div class="card-eco p-2"><img src="https://placehold.co/300x280" class="img-fluid rounded"></div></div>
  <?php endforeach; ?>
</div>
<div class="modal fade" id="addItemModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Add Item</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input class="form-control mb-2" placeholder="Name"><input type="file" class="form-control"></div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
