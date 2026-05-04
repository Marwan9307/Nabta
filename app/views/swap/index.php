<?php $data['page_title'] = $data['page_title'] ?? 'Swaps'; require_once __DIR__ . '/../layout/header.php'; ?>
<h2 class="mb-3">Swap Requests</h2>
<div class="row g-3">
  <?php foreach (($data['swaps'] ?? [1,2,3]) as $s): ?>
    <div class="col-md-4"><div class="card-eco p-3"><h6>Swap #<?= is_array($s) ? ($s['id'] ?? 1) : $s ?></h6><p class="small mb-2">Pending exchange details</p><a href="/swap/show/<?= is_array($s) ? ($s['id'] ?? 1) : $s ?>" class="btn btn-sage-outline btn-sm">View</a></div></div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
