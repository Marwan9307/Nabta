<?php $data['page_title'] = $data['page_title'] ?? 'Order Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Order #<?= $data['order_id'] ?? 0 ?></h2>
  <p>Status: <strong><?= $data['status'] ?? 'Pending' ?></strong></p>
  <?php if (($data['status'] ?? '') !== 'delivered'): ?>
    <form method="post" action="/order/confirm">
      <input type="hidden" name="order_id" value="<?= $data['order_id'] ?? 0 ?>">
      <button class="btn btn-clay" type="submit">Confirm Delivery</button>
    </form>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
