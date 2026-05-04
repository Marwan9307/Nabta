<?php $data['page_title'] = $data['page_title'] ?? 'Create Community Post'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>New Post</h2>
  <input class="form-control mb-2" placeholder="Title">
  <textarea class="form-control mb-2" rows="5" placeholder="Share your journey..."></textarea>
  <button class="btn btn-clay">Publish</button>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
