<?php $data['page_title'] = $data['page_title'] ?? 'Swap Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Swap Request #<?= $data['swap_id'] ?? 1 ?></h2>
  <p><?= $data['swap_note'] ?? '' ?></p>
  <div class="d-flex gap-2">
    <form method="post" action="/swap/accept"><input type="hidden" name="swap_id" value="<?= $data['swap_id'] ?? 0 ?>"><button class="btn btn-clay" type="submit">Accept</button></form>
    <form method="post" action="/swap/reject"><input type="hidden" name="swap_id" value="<?= $data['swap_id'] ?? 0 ?>"><button class="btn btn-outline-secondary" type="submit">Decline</button></form>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
