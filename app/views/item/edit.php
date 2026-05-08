<?php $data['page_title'] = $data['page_title'] ?? 'Edit Item'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Edit Listing</h2>
  <form method="post" action="/item/edit/<?= $data['item_id'] ?? 0 ?>">
    <div class="row g-3">
      <div class="col-md-4"><input name="title" class="form-control" placeholder="Title" value="<?= $data['title'] ?? '' ?>"></div>
      <div class="col-md-4"><input name="price" class="form-control" placeholder="Price" value="<?= $data['price'] ?? '' ?>"></div>
      <div class="col-md-4"><input name="category" class="form-control" placeholder="Category" value="<?= $data['category'] ?? '' ?>"></div>
      <div class="col-12">
        <label class="d-block mb-2">Condition issues</label>
        <label class="btn btn-light border condition-btn me-2"><input type="checkbox" name="stain" class="d-none"> Stain</label>
        <label class="btn btn-light border condition-btn me-2"><input type="checkbox" name="fading" class="d-none"> Fading</label>
        <label class="btn btn-light border condition-btn me-2"><input type="checkbox" name="hole" class="d-none"> Hole</label>
        <label class="btn btn-light border condition-btn"><input type="checkbox" name="missing_button" class="d-none"> Missing button</label>
      </div>
      <div class="col-12"><button class="btn btn-clay" type="submit">Save Changes</button></div>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
