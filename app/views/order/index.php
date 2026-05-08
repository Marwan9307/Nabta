<?php $data['page_title'] = $data['page_title'] ?? 'Orders'; require_once __DIR__ . '/../layout/header.php'; ?>
<h2 class="mb-3">Orders</h2>
<div class="list-group">
  <?php if (!empty($data['orders']) && is_array($data['orders'])): ?>
    <?php foreach ($data['orders'] as $o): ?>
      <?php if (!is_array($o)) continue; ?>
      <a href="/order/show/<?= $o['order_id'] ?>" class="list-group-item list-group-item-action">
        Order #<?= $o['order_id'] ?> · <?= $o['order_status'] ?> · EGP <?= $o['total_price'] ?>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">No orders yet.</p>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
