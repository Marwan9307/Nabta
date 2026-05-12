<?php $data['page_title'] = $data['page_title'] ?? 'Create Community Post'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>New Post</h2>
  <form method="post" action="/community/create" enctype="multipart/form-data">
    <input name="title" class="form-control mb-2" placeholder="Title" required>
    <textarea name="content" class="form-control mb-2" rows="5" placeholder="Share your journey..." required></textarea>
    <select name="post_type" class="form-select mb-3">
      <option value="general">General</option>
      <option value="style_request">Style Request</option>
      <option value="inspire_me">Inspire Me</option>
      <option value="upcycling_story">Upcycling Story</option>
    </select>
    <div class="mb-3">
        <label for="media" class="form-label">Upload Image/Media (Optional)</label>
        <input type="file" name="media" id="media" class="form-control" accept="image/*,video/*">
    </div>
    <button class="btn btn-clay" type="submit">Publish</button>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
