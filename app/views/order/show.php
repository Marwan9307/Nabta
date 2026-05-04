<?php $data['page_title'] = $data['page_title'] ?? 'Order Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Order #<?= $data['order_id'] ?? 101 ?></h2>
  <p>Status: <?= $data['status'] ?? 'Shipped' ?></p>
  <button class="btn btn-clay" data-toggle-text data-on-text="Liked" data-off-text="Like">Like</button>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
