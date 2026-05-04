<?php $data['page_title'] = $data['page_title'] ?? 'Community'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row g-3">
  <div class="col-lg-8">
    <div class="card-eco p-4">
      <h2>Community Feed</h2>
      <p><?= $data['feed_intro'] ?? 'Share before/after stories and sustainable tips.' ?></p>
      <a href="/community/create" class="btn btn-clay">Create Post</a>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card-eco p-4">
      <h5>Mentor Network</h5>
      <p class="small">Request support from top upcyclers.</p>
      <button class="btn btn-sage-outline" data-bs-toggle="modal" data-bs-target="#mentorRequestModal">Request a Mentor</button>
    </div>
  </div>
</div>
<div class="modal fade" id="mentorRequestModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Eligible Mentors</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><ul class="mb-0"><?php foreach (($data['eligible_mentors'] ?? [['name'=>'Sara','points'=>920],['name'=>'Nour','points'=>860]]) as $m): ?><li><?= $m['name'] ?> - <?= $m['points'] ?> points</li><?php endforeach; ?></ul></div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
