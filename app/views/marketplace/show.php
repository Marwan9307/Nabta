<?php $data['page_title'] = $data['page_title'] ?? 'Item Details'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row g-4">
  <div class="col-lg-6"><img src="<?= $data['item_image'] ?? 'https://placehold.co/700x900' ?>" class="img-fluid rounded-4"></div>
  <div class="col-lg-6">
    <h2><?= $data['item_title'] ?? 'Linen Upcycled Dress' ?></h2>
    <p>Seller: <a href="/profile/<?= $data['seller_id'] ?? 1 ?>"><?= $data['seller_name'] ?? 'Mona Adel' ?></a> · Trust <?= $data['trust_score'] ?? '4.8/5' ?></p>
    <p><?= $data['description'] ?? 'Soft breathable fabric with artisan stitching and eco-friendly dye.' ?></p>
    <h4>EGP <?= $data['price'] ?? 440 ?> <small class="text-muted fs-6">(Negotiation <?= $data['negotiation'] ?? '2%' ?>)</small></h4>
    <div class="d-flex gap-2 flex-wrap">
      <button class="btn btn-clay" data-bs-toggle="modal" data-bs-target="#buyNowModal">Buy Now</button>
      <button class="btn btn-sage-outline" data-bs-toggle="modal" data-bs-target="#swapModal">Request Swap</button>
      <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#arModal">AR Fitting Room</button>
    </div>
  </div>
</div>
<div class="modal fade" id="buyNowModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Counter Offer</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="range" min="440" max="450" value="445" class="form-range"><button class="btn btn-clay w-100">Submit Offer</button></div></div></div></div>
<div class="modal fade" id="swapModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Pick Item from Closet</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><select class="form-select"><option><?= $data['closet_item'] ?? 'Beige Jacket' ?></option></select></div></div></div></div>
<div class="modal fade" id="arModal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5>Try it on</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="card-eco p-5 text-center">Simulated AR overlay appears here.</div></div></div></div></div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
