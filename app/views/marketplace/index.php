<?php 
$data['page_title'] = $data['page_title'] ?? 'Marketplace'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container py-4">
    <!-- فورم موحدة لجميع الفلاتر عشان تتبعت مع بعضها في الـ GET request -->
    <form method="get" action="/marketplace" id="filterForm">
        
        <!-- الجزء العلوي: السيرش والترتيب -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <span class="small text-muted fst-italic">Pre-loved · Curated</span>
                <h1 class="serif text-forest mb-0">Marketplace</h1>
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                <input type="text" name="q" class="form-control w-auto rounded-pill px-3" placeholder="Search items... (press S)" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                
                <select name="sort" class="form-select w-auto rounded-pill" onchange="document.getElementById('filterForm').submit()">
                    <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price Low-High</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price High-Low</option>
                </select>
                
                <button class="btn btn-clay text-white px-4 rounded-pill" type="submit">Filter</button>
            </div>
        </div>

        <div class="row">
            <!-- الشريط الجانبي للفلاتر (Sidebar Filters) -->
            <div class="col-md-3 mb-4">
                <div class="card border-0 p-4 rounded-4" style="background-color: var(--white-linen); box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                    <h5 class="serif text-forest mb-4">⚙️ Filters</h5>
                    
                    <!-- فلتر الـ Category -->
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold mb-2">Category</label>
                        <select name="category" class="form-select rounded-3" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All</option>
                            <option value="Tops" <?= ($_GET['category'] ?? '') === 'Tops' ? 'selected' : '' ?>>Tops</option>
                            <option value="Bottoms" <?= ($_GET['category'] ?? '') === 'Bottoms' ? 'selected' : '' ?>>Bottoms</option>
                            <option value="Dresses" <?= ($_GET['category'] ?? '') === 'Dresses' ? 'selected' : '' ?>>Dresses</option>
                            <option value="Outerwear" <?= ($_GET['category'] ?? '') === 'Outerwear' ? 'selected' : '' ?>>Outerwear</option>
                            <option value="Accessories" <?= ($_GET['category'] ?? '') === 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                            <option value="Shoes" <?= ($_GET['category'] ?? '') === 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                            <option value="Jackets" <?= ($_GET['category'] ?? '') === 'Jackets' ? 'selected' : '' ?>>Jackets</option>
                        </select>
                    </div>

                    <!-- فلتر الـ Condition -->
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold mb-2">Condition</label>
                        <select name="condition" class="form-select rounded-3" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All</option>
                            <option value="Like New" <?= ($_GET['condition'] ?? '') === 'Like New' ? 'selected' : '' ?>>Like New</option>
                            <option value="Good" <?= ($_GET['condition'] ?? '') === 'Good' ? 'selected' : '' ?>>Good</option>
                            <option value="Fair" <?= ($_GET['condition'] ?? '') === 'Fair' ? 'selected' : '' ?>>Fair</option>
                        </select>
                    </div>

                    <!-- فلتر الـ Material -->
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase fw-bold mb-2">Material</label>
                        <select name="material" class="form-select rounded-3" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All</option>
                            <option value="cotton" <?= ($_GET['material'] ?? '') === 'cotton' ? 'selected' : '' ?>>Cotton</option>
                            <option value="polyester" <?= ($_GET['material'] ?? '') === 'polyester' ? 'selected' : '' ?>>Polyester</option>
                            <option value="denim" <?= ($_GET['material'] ?? '') === 'denim' ? 'selected' : '' ?>>Denim</option>
                            <option value="wool" <?= ($_GET['material'] ?? '') === 'wool' ? 'selected' : '' ?>>Wool</option>
                            <option value="silk" <?= ($_GET['material'] ?? '') === 'silk' ? 'selected' : '' ?>>Silk</option>
                            <option value="linen" <?= ($_GET['material'] ?? '') === 'linen' ? 'selected' : '' ?>>Linen</option>
                            <option value="leather" <?= ($_GET['material'] ?? '') === 'leather' ? 'selected' : '' ?>>Leather</option>
                        </select>
                    </div>

                    <!-- ضيفيها جوه الـ sidebar card قبل الـ Checkboxes -->
                    <div class="mb-3">
                       <label class="small text-muted text-uppercase fw-bold mb-2">Gender</label>
                       <select name="gender" class="form-select rounded-3" onchange="this.form.submit()">
                           <option value="">All</option>
                           <option value="Men" <?= ($_GET['gender'] ?? '') === 'Men' ? 'selected' : '' ?>>Men</option>
                           <option value="Women" <?= ($_GET['gender'] ?? '') === 'Women' ? 'selected' : '' ?>>Women</option>
                           <option value="Kids" <?= ($_GET['gender'] ?? '') === 'Kids' ? 'selected' : '' ?>>Kids</option>
                           <option value="Unisex" <?= ($_GET['gender'] ?? '') === 'Unisex' ? 'selected' : '' ?>>Unisex</option>
                        </select>
                    </div>

                    <!-- التوجيهات البيئية (Checkboxes) -->
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="upcycled" value="1" id="upcycledCheck" <?= ($_GET['upcycled'] ?? '') === '1' ? 'checked' : '' ?> onchange="document.getElementById('filterForm').submit()">
                        <label class="form-check-label small" for="upcycledCheck">Upcycled only</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="swap_available" value="1" id="swapCheck" <?= ($_GET['swap_available'] ?? '') === '1' ? 'checked' : '' ?> onchange="document.getElementById('filterForm').submit()">
                        <label class="form-check-label small" for="swapCheck">Swap available</label>
                    </div>
                </div>
            </div>

            <!-- عرض المنتجات (Products Grid) -->
            <div class="col-md-9">
                <div class="row g-3">
                    <?php if (!empty($data['items']) && is_array($data['items'])): ?>
                        <?php foreach ($data['items'] as $item): ?>
                            <?php if (!is_array($item)) continue; ?>
                            <div class="col-md-6 col-lg-4">
                                <a href="/marketplace/show/<?= $item['item_id'] ?>" class="text-decoration-none">
                                    <div class="card-eco p-3 h-100 spring" style="background-color: var(--white-linen); border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.01);">
                                        <div class="ratio ratio-4x3 overflow-hidden rounded mb-3">
                                            <img src="<?= $item['item_photo'] ?: 'https://placehold.co/400x300' ?>" class="img-fluid object-fit-cover" alt="">
                                        </div>
                                        <div class="small text-uppercase tracking-wider text-muted mb-1" style="font-size: 10px;"><?= htmlspecialchars($item['condition_grade'] ?? 'Good') ?></div>
                                        <h6 class="text-forest serif mb-2"><?= htmlspecialchars($item['title'] ?? 'Upcycled Piece') ?></h6>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="fw-bold text-forest" style="font-size: 14px;">EGP <?= number_format($item['item_price'] ?? 0) ?></span>
                                            <?php if ($item['listing_type'] == 1 || $item['listing_type'] == 2): ?>
                                                <span class="small text-muted" style="font-size: 11px;">Swap</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($item['is_upcycled']): ?>
                                            <span class="badge bg-success mt-2" style="background-color: var(--color-sage) !important;">Upcycled</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <p class="text-muted">No items found. Try adjusting your filters 🌿</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>