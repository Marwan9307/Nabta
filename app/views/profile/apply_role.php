<?php $data['page_title'] = $data['page_title'] ?? 'Apply Role'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Apply for Role</h2>
  <form method="post" action="/profile/apply-role" enctype="multipart/form-data">
    <select name="role_type" class="form-select mb-2"><option>Upcycler</option><option>Mentor</option></select>
    <textarea name="motivation" class="form-control mb-2" rows="4" placeholder="Why should we approve your role?"></textarea>
    <label class="form-label">Portfolio Upload</label>
    <input type="file" name="portfolio" class="form-control mb-2">
    <button class="btn btn-clay" type="submit">Submit</button>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
