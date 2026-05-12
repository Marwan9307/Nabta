<?php $data['page_title'] = $data['page_title'] ?? 'Order Details'; require_once __DIR__ . '/../layout/header.php'; 
$order = $data['order'] ?? [];
$items = $data['items'] ?? [];
$status = strtolower($data['status'] ?? 'pending');
?>
<div class="card-eco p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Order #<?= htmlspecialchars($data['order_id'] ?? 0) ?></h2>
    <span class="badge bg-<?= $status === 'delivered' ? 'success' : ($status === 'pending' ? 'warning' : 'info') ?>">
      <?= htmlspecialchars(ucfirst($status)) ?>
    </span>
  </div>
  
  <div class="row mb-3">
    <div class="col-md-6">
      <p><strong>Subtotal:</strong> EGP <?= number_format($order['items_subtotal'] ?? 0, 2) ?></p>
      <p><strong>Platform Fee (5%):</strong> EGP <?= number_format($order['platform_fee'] ?? 0, 2) ?></p>
      <p><strong>Bundle Discount:</strong> -EGP <?= number_format($order['bundle_discount'] ?? 0, 2) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Total Price:</strong> <span class="h5">EGP <?= number_format($order['total_price'] ?? 0, 2) ?></span></p>
      <p><strong>Order Date:</strong> <?= $order['created_at'] ?? 'N/A' ?></p>
    </div>
  </div>

  <?php if (!empty($items)): ?>
  <h5 class="mt-4 mb-3">Items in This Order</h5>
  <table class="table">
    <thead>
      <tr><th>Item</th><th>Price</th></tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['title'] ?? 'Unknown Item') ?></td>
        <td>EGP <?= number_format($item['price'] ?? 0, 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <?php if ($status !== 'delivered'): ?>
  <div class="alert alert-info mt-3">
    <strong>ℹ️ Next Step:</strong> Once you receive and verify the item(s), click the button below to confirm delivery. This will release payment to the seller.
  </div>
  <form method="post" action="/order/confirm" class="mt-3">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($data['order_id'] ?? 0) ?>">
    <button class="btn btn-clay" type="submit">✓ Confirm Delivery</button>
  </form>
  <?php else: ?>
  <div class="alert alert-success mt-3">
    <strong>✓ Completed!</strong> This order has been delivered and the transaction is complete.
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
