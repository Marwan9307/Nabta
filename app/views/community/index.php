<?php $data['page_title'] = $data['page_title'] ?? 'Community'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card-eco p-4 mb-3">
      <h2>Community Feed</h2>
      <p><?= $data['feed_intro'] ?? 'Share before/after stories and sustainable tips.' ?></p>
      <a href="/community/create" class="btn btn-clay">Create Post</a>
    </div>
    <?php foreach (($data['posts'] ?? []) as $post): ?>
      <div class="card-eco p-3 mb-2 spring">
        <h6><?= htmlspecialchars($post['title'] ?? '') ?></h6>
        <p class="small mb-1"><?= htmlspecialchars($post['content'] ?? '') ?></p>
        <small class="text-muted">By <?= $post['author_name'] ?? 'Unknown' ?> · <?= $post['created_at'] ?? '' ?></small>
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
