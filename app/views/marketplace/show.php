<?php $data['page_title'] = $data['page_title'] ?? 'Item Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row g-4">
  <div class="col-lg-6"><img src="<?= $data['item_image'] ?? 'https://placehold.co/700x900' ?>" class="img-fluid rounded-4"></div>
  <div class="col-lg-6">
    <h2><?= $data['item_title'] ?? 'Linen Upcycled Dress' ?></h2>
    <p>Seller: <a href="/profile/<?= $data['seller_id'] ?? 1 ?>"><?= $data['seller_name'] ?? 'Mona Adel' ?></a> · Trust <?= $data['trust_score'] ?? '4.8/5' ?></p>
    <p><?= $data['description'] ?? '' ?></p>
    <h4>EGP <?= $data['price'] ?? 440 ?> <small class="text-muted fs-6">(Negotiation <?= $data['negotiation'] ?? '2%' ?>)</small></h4>
    <div class="d-flex gap-2 flex-wrap">
      <button class="btn btn-clay" data-bs-toggle="modal" data-bs-target="#buyNowModal">Buy Now</button>
      <button class="btn btn-sage-outline" data-bs-toggle="modal" data-bs-target="#swapModal">Request Swap</button>
    </div>
  </div>
</div>
<div class="modal fade" id="buyNowModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Buy / Counter Offer</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
  <form method="post" action="/order/buy">
    <input type="hidden" name="item_id" value="<?= $data['item_id'] ?? 0 ?>">
    <label>Your Offer (EGP)</label>
    <input type="number" name="offer_price" class="form-control mb-2" value="<?= $data['price'] ?? 440 ?>" min="1">
    <select name="payment_method" class="form-select mb-2"><option value="card">Credit Card</option><option value="wallet">Wallet</option></select>
    <button class="btn btn-clay w-100" type="submit">Confirm Purchase</button>
  </form>
</div></div></div></div>
<div class="modal fade" id="swapModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Pick Item from Closet</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
  <form method="post" action="/swap/request">
    <input type="hidden" name="requested_item_id" value="<?= $data['item_id'] ?? 0 ?>">
    <label>Select your item to offer</label>
    <select name="offered_item_id" class="form-select mb-2"><option><?= $data['closet_item'] ?? 'No items' ?></option></select>
    <button class="btn btn-sage-outline w-100" type="submit">Send Swap Request</button>
  </form>
</div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
