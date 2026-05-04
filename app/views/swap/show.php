<?php $data['page_title'] = $data['page_title'] ?? 'Swap Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Swap Request #<?= $data['swap_id'] ?? 1 ?></h2>
  <p><?= $data['swap_note'] ?? 'Confirm pickup and condition before accepting.' ?></p>
  <div class="d-flex gap-2">
    <button class="btn btn-clay">Accept</button>
    <button class="btn btn-outline-secondary">Decline</button>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
