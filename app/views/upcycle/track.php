<?php $data['page_title'] = $data['page_title'] ?? 'Track Upcycle'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Project Tracker</h2>
  <div class="progress mb-3"><div class="progress-bar" style="width: <?= $data['progress'] ?? 55 ?>%"></div></div>
  <p>Current phase: <?= $data['phase'] ?? 'Pattern redesign' ?></p>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
