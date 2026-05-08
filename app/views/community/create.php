<?php $data['page_title'] = $data['page_title'] ?? 'Create Community Post'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>New Post</h2>
  <form method="post" action="/community/create">
    <input name="title" class="form-control mb-2" placeholder="Title" required>
    <textarea name="content" class="form-control mb-2" rows="5" placeholder="Share your journey..." required></textarea>
    <select name="post_type" class="form-select mb-2">
      <option value="general">General</option>
      <option value="style_request">Style Request</option>
      <option value="inspire_me">Inspire Me</option>
      <option value="upcycling_story">Upcycling Story</option>
    </select>
    <button class="btn btn-clay" type="submit">Publish</button>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
