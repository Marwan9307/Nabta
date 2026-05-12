<?php $data['page_title'] = $data['page_title'] ?? 'Item Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row g-4">
  <div class="col-lg-6"><img src="<?= htmlspecialchars($data['item_image'] ?? 'https://placehold.co/700x900') ?>" class="img-fluid rounded-4"></div>
  <div class="col-lg-6">
    <h2><?= htmlspecialchars($data['item_title'] ?? 'Item') ?></h2>
    <p>Seller: <a href="/profile/<?= $data['seller_id'] ?? 1 ?>"><?= htmlspecialchars($data['seller_name'] ?? 'Unknown') ?></a> · Trust <?= htmlspecialchars($data['trust_score'] ?? '0/5') ?></p>
    <p><?= nl2br(htmlspecialchars($data['description'] ?? '')) ?></p>
    
    <div class="card-eco p-3 mb-3">
      <h4>Price: EGP <?= number_format($data['price'] ?? 0, 2) ?></h4>
      <?php if ($data['negotiation_percent'] > 0): ?>
        <p class="mb-2"><strong>Negotiation Available:</strong> <span class="badge bg-success"><?= htmlspecialchars($data['negotiation_percent']) ?>% off</span></p>
        <p class="small text-muted">Minimum acceptable offer: EGP <?= number_format($data['min_price'] ?? 0, 2) ?></p>
      <?php else: ?>
        <p class="mb-0"><strong>Negotiation:</strong> <span class="badge bg-secondary">Not Allowed</span></p>
      <?php endif; ?>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <?php if (Session::isLoggedIn()): ?>
        <button class="btn btn-clay" data-bs-toggle="modal" data-bs-target="#buyNowModal">Buy Now</button>
        <button class="btn btn-sage-outline" data-bs-toggle="modal" data-bs-target="#swapModal">Request Swap</button>
      <?php else: ?>
        <a href="/auth/login" class="btn btn-clay">Buy Now</a>
        <a href="/auth/login" class="btn btn-sage-outline">Request Swap</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="modal fade" id="buyNowModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buy / Counter Offer</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/order/buy" id="buyForm">
          <input type="hidden" name="item_id" value="<?= htmlspecialchars($data['item_id'] ?? 0) ?>">
          
          <div class="mb-3">
            <label class="form-label"><strong>Your Offer (EGP)</strong></label>
            <input type="number" name="offer_price" class="form-control" value="<?= htmlspecialchars($data['price'] ?? 0) ?>" min="1" step="0.01" required>
            <?php if ($data['negotiation_percent'] > 0): ?>
              <small class="d-block mt-1 text-muted">Minimum: EGP <?= number_format($data['min_price'] ?? 0, 2) ?> | Maximum: EGP <?= number_format($data['price'] ?? 0, 2) ?></small>
            <?php else: ?>
              <small class="d-block mt-1 text-danger">⚠️ This item is not negotiable. You must pay the full price.</small>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label"><strong>Payment Method</strong></label>
            <select name="payment_method" class="form-select" required>
              <option value="card">💳 Credit Card</option>
              <option value="wallet">👝 Digital Wallet</option>
            </select>
          </div>

          <button class="btn btn-clay w-100" type="submit">✓ Confirm Purchase</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="swapModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick Item from Closet</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/swap/request">
          <input type="hidden" name="requested_item_id" value="<?= htmlspecialchars($data['item_id'] ?? 0) ?>">
          <label class="form-label">Select your item to offer</label>
          <select name="offered_item_id" class="form-select mb-2" required>
            <option><?= htmlspecialchars($data['closet_item'] ?? 'No items in closet') ?></option>
          </select>
          <button class="btn btn-sage-outline w-100" type="submit">Send Swap Request</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
