<?php $data['page_title'] = $data['page_title'] ?? 'Apply Role'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Apply for Role</h2>
  <select class="form-select mb-2"><option>Upcycler</option><option>Mentor</option></select>
  <textarea class="form-control mb-2" rows="4" placeholder="Why should we approve your role?"></textarea>
  <button class="btn btn-clay">Submit</button>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
