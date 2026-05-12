<?php $data['page_title'] = $data['page_title'] ?? 'Create Item'; require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card-eco p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h2 class="mb-1">Add New Item</h2>
      <p class="text-muted mb-0">Create a closet listing and, if you are an upcycler, include the full transformation record.</p>
    </div>
    <a href="/item/closet" class="btn btn-sage-outline">Back to Closet</a>
  </div>

  <form method="post" action="/item/create" enctype="multipart/form-data" id="addItemForm">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" placeholder="Name" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category" class="form-select" required>
          <option value="">Choose category</option>
          <option>Tops</option>
          <option>Bottoms</option>
          <option>Dresses</option>
          <option>Outerwear</option>
          <option>Accessories</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Material</label>
        <select name="material_type" class="form-select" required>
          <option value="">Choose material</option>
          <option>cotton</option>
          <option>polyester</option>
          <option>denim</option>
          <option>wool</option>
          <option>silk</option>
          <option>linen</option>
          <option>leather</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Price (EGP)</label>
        <input name="price" type="number" class="form-control" placeholder="0" min="1" step="0.01" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Weight (kg)</label>
        <input name="weight" type="number" step="0.1" min="0" class="form-control" placeholder="0.0">
      </div>
      <div class="col-md-6">
        <label class="form-label">Listing Type</label>
        <select name="listing_type" class="form-select" required>
          <option value="available">For Sale & Swap</option>
          <option value="sale_only">Sale Only</option>
          <option value="swap_only">Swap Only</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Size</label>
        <input name="size" class="form-control" placeholder="S / M / L / 40">
      </div>
      <div class="col-12">
        <label class="form-label">Main Item Photo</label>
        <input type="file" name="item_photo" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Is this item upcycled?</label>
        <?php if (($data['current_role'] ?? '') === 'upcycler'): ?>
          <select name="is_upcycled" id="is_upcycled" class="form-select" required>
            <option value="no">No</option>
            <option value="yes">Yes</option>
          </select>
          <small class="text-muted d-block mt-1">Only upcycler accounts can publish items as upcycled.</small>
        <?php else: ?>
          <input type="hidden" name="is_upcycled" value="no">
          <div class="alert alert-secondary mb-0">You can publish a regular item. Upcycled listings are available only for users with the upcycler role.</div>
        <?php endif; ?>
      </div>
    </div>

    <div id="upcycledRequirements" class="card border-success-subtle bg-success-subtle p-3 mt-4 d-none">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h5 class="mb-0">Upcycling Requirements</h5>
        <span class="badge bg-success">Required when Yes is selected</span>
      </div>
      <p class="text-muted small mb-3">Fill these fields to document the transformation. The item ID, CO2 saved, and water saved are created automatically when the item is saved.</p>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Photo Before</label>
          <input type="file" name="before_photo" class="form-control upcycle-field">
        </div>
        <div class="col-md-6">
          <label class="form-label">Photo After</label>
          <input type="file" name="after_photo" class="form-control upcycle-field">
        </div>
        <div class="col-12">
          <label class="form-label">Added Materials</label>
          <input name="added_materials" class="form-control upcycle-field" placeholder="Buttons, fabric, beads, thread...">
        </div>
        <div class="col-12">
          <label class="form-label">Upcycling Story</label>
          <textarea name="story" class="form-control upcycle-field" rows="4" placeholder="Tell the story behind the transformation..."></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label">Item ID</label>
          <input class="form-control" value="Assigned after submit" disabled>
        </div>
        <div class="col-md-4">
          <label class="form-label">CO2 Saved</label>
          <input class="form-control" value="Calculated automatically" disabled>
        </div>
        <div class="col-md-4">
          <label class="form-label">Water Saved</label>
          <input class="form-control" value="Calculated automatically" disabled>
        </div>
      </div>
    </div>

    <hr class="my-4">
    <h5>Condition Assessment</h5>
    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <label class="form-label">Tear Check</label>
        <select name="tear_check" class="form-select" required>
          <option value="">Are there any tears or holes?</option>
          <option value="No">No (Passed)</option>
          <option value="Yes">Yes (Failed)</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Cleanliness Check</label>
        <select name="cleanliness_check" class="form-select" required>
          <option value="">Is it clean and stain-free?</option>
          <option value="Yes">Yes (Passed)</option>
          <option value="No">No (Failed)</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Usage Frequency</label>
        <select name="usage_frequency" class="form-select" required>
          <option value="">Choose usage</option>
          <option value="Little Usage">Little Usage</option>
          <option value="Medium Usage">Medium Usage</option>
          <option value="Too Much Usage">Too Much Usage</option>
        </select>
      </div>
    </div>

    <div class="d-grid mt-4">
      <button class="btn btn-clay btn-lg" type="submit">Add Item</button>
    </div>
  </form>
</div>

<script>
(() => {
  const selector = document.getElementById('is_upcycled');
  const requirements = document.getElementById('upcycledRequirements');
  const upcycleFields = requirements ? requirements.querySelectorAll('.upcycle-field') : [];

  const sync = () => {
    const enabled = selector && selector.value === 'yes';
    if (requirements) {
      requirements.classList.toggle('d-none', !enabled);
    }
    upcycleFields.forEach((field) => {
      field.required = enabled;
    });
  };

  if (selector) {
    selector.addEventListener('change', sync);
    sync();
  }
})();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>