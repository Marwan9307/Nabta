<?php $data['page_title'] = $data['page_title'] ?? 'Swaps'; require_once __DIR__ . '/../layout/header.php'; ?>
<h2 class="mb-3">Swap Requests</h2>
<div class="row g-3">
  <?php if (!empty($data['swaps']) && is_array($data['swaps'])): ?>
    <?php foreach ($data['swaps'] as $s): ?>
      <?php if (!is_array($s)) continue; ?>
      <div class="col-md-4">
        <div class="card-eco p-3">
          <h6>Swap #<?= $s['swap_id'] ?></h6>
          <p class="small mb-2">Status: <?= $s['swap_status'] ?></p>
          <a href="/swap/show/<?= $s['swap_id'] ?>" class="btn btn-sage-outline btn-sm">View</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12"><p class="text-muted">No swap requests.</p></div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
