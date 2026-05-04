<?php $data['page_title'] = $data['page_title'] ?? 'Orders'; require_once __DIR__ . '/../layout/header.php'; ?>
<h2 class="mb-3">Orders</h2>
<div class="list-group">
  <?php foreach (($data['orders'] ?? [101,102]) as $o): ?>
    <a href="/order/show/<?= is_array($o) ? ($o['id'] ?? 1) : $o ?>" class="list-group-item list-group-item-action">Order #<?= is_array($o) ? ($o['id'] ?? 1) : $o ?></a>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
