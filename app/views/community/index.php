<?php 
$data['page_title'] = $data['page_title'] ?? 'Community'; 
$userRole = Session::userRole() ?? '';
if ($userRole === 'admin') {
    require_once __DIR__ . '/../admin/header.php';
} elseif ($userRole === 'moderator') {
    require_once __DIR__ . '/../moderator/header.php';
} else {
    require_once __DIR__ . '/../layout/header.php';
}
?>
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card-eco p-4 mb-3">
      <h2>Community Feed</h2>
      <p><?= $data['feed_intro'] ?? 'Share before/after stories and sustainable tips.' ?></p>
      <a href="/community/create" class="btn btn-clay">Create Post</a>
    </div>
    <?php foreach (($data['posts'] ?? []) as $post): ?>
      <div class="card-eco p-3 mb-2 spring">
        <div class="d-flex justify-content-between align-items-start">
          <h6><?= htmlspecialchars($post['title'] ?? '') ?></h6>
          <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#reportModal<?= $post['post_id'] ?>" title="Report Post">
            <small>⚠️ Report</small>
          </button>
        </div>
        <p class="small mb-1"><?= htmlspecialchars($post['content'] ?? '') ?></p>
        
        <?php if (!empty($post['media_url'])): ?>
          <img src="<?= htmlspecialchars($post['media_url']) ?>" alt="Post media" class="img-fluid rounded mb-2" style="max-height: 400px; object-fit: cover;">
        <?php endif; ?>

        <small class="text-muted">By <a href="/profile/<?= htmlspecialchars($post['author_id'] ?? 0) ?>" class="text-decoration-none fw-bold"><?= htmlspecialchars($post['author_name'] ?? 'Unknown') ?></a> · <?= $post['created_at'] ?? '' ?></small>
        
        <?php if (!empty($post['comments'])): ?>
          <div class="mt-2 bg-light p-2 rounded small">
            <strong>Comments:</strong>
            <?php foreach ($post['comments'] as $c): ?>
              <div class="border-bottom border-light pb-1 mb-1 mt-1">
                <strong><a href="/profile/<?= htmlspecialchars($c['author_id'] ?? 0) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($c['author_name'] ?? 'Unknown') ?></a>:</strong> <?= htmlspecialchars($c['content'] ?? '') ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <hr class="my-2">
        <form method="post" action="/community/comment" class="d-flex mt-2">
            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
            <input type="text" name="content" class="form-control form-control-sm me-2" placeholder="Add a comment..." required>
            <button type="submit" class="btn btn-sm btn-clay">Comment</button>
        </form>
      </div>

      <!-- Report Modal for each post -->
      <div class="modal fade" id="reportModal<?= $post['post_id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-danger">Report Post</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/report/create" method="POST">
              <div class="modal-body">
                <input type="hidden" name="target_id" value="<?= $post['post_id'] ?>">
                <input type="hidden" name="report_type" value="communication">
                <p>Are you sure you want to report this post by <strong><?= htmlspecialchars($post['author_name'] ?? 'Unknown') ?></strong>?</p>
                <div class="mb-3">
                  <label for="reason<?= $post['post_id'] ?>" class="form-label">Reason for reporting</label>
                  <textarea name="reason" id="reason<?= $post['post_id'] ?>" class="form-control" rows="3" required placeholder="Please provide specific details about why this post violates community guidelines..."></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Submit Report</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="col-lg-4">
    <div class="card-eco p-4">
      <h5>Mentor Network</h5>
      <p class="small">Request support from top upcyclers.</p>
      <button class="btn btn-sage-outline" data-bs-toggle="modal" data-bs-target="#mentorRequestModal">Request a Mentor</button>
    </div>
  </div>
</div>
<div class="modal fade" id="mentorRequestModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Eligible Mentors</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
  <ul class="mb-0">
    <?php foreach (($data['eligible_mentors'] ?? []) as $m): ?>
      <li><?= $m['username'] ?? $m['name'] ?? '' ?> - <?= $m['eco_points'] ?? $m['points'] ?? 0 ?> points
        <form method="post" action="/community/mentor" class="d-inline"><input type="hidden" name="mentor_id" value="<?= $m['user_id'] ?? 0 ?>"><button class="btn btn-sm btn-clay" type="submit">Request</button></form>
      </li>
    <?php endforeach; ?>
    <?php if (empty($data['eligible_mentors'])): ?><li>No mentors available yet.</li><?php endif; ?>
  </ul>
</div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
