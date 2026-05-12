<?php $data['page_title'] = $data['page_title'] ?? 'Profile'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4 text-center">
  <div class="position-relative d-inline-block mb-3">
    <img src="<?= $data['avatar'] ?? 'https://placehold.co/100x100' ?>" width="100" height="100" class="rounded-circle">
    <form action="/profile/updatePhoto" method="POST" enctype="multipart/form-data" class="d-inline">
      <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none" onchange="this.form.submit()">
      <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 border shadow-sm" style="width: 32px; height: 32px; right: -10px;" title="Edit Profile Photo" onclick="document.getElementById('avatarInput').click()">
        📷
      </button>
    </form>
  </div>
  <h3><?= $data['name'] ?? 'NABTA User' ?></h3>
  <p>Trust Score: <?= $data['trust_score'] ?? '★★★★☆' ?> · Eco Points: <span id="ecoPoints"><?= $data['eco_points'] ?? 480 ?></span></p>
  <div class="snake-plant-stage">
    <svg id="snakePlantSvg" viewBox="0 0 120 120" width="140" style="transition:transform .4s ease;">
      <path d="M60 105V35" stroke="#5C7A5C" stroke-width="5"/>
      <ellipse cx="72" cy="45" rx="16" ry="24" fill="#A8B89A"/>
      <ellipse cx="48" cy="60" rx="14" ry="22" fill="#D4A5A5"/>
      <ellipse cx="66" cy="72" rx="12" ry="20" fill="#A8B89A"/>
    </svg>
  </div>
  <p class="mb-1">Leaf count: <span id="leafCount">3</span></p>
  <div class="alert alert-success-subtle">Plant Fact: Just like this plant, you are cleaning the air.</div>

  <!-- Profile Data Display Section -->
  <div class="card mt-4 mb-3 text-start border-0 shadow-sm" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Profile Information</h5>
      <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('updateProfileFormContainer').classList.toggle('d-none')">
        Edit
      </button>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Email</strong>
          <span class="text-muted"><?= htmlspecialchars($data['email'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Mobile Number</strong>
          <span class="text-muted"><?= htmlspecialchars($data['mobile_no'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>City</strong>
          <span class="text-muted"><?= htmlspecialchars($data['city'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Street Name</strong>
          <span class="text-muted"><?= htmlspecialchars($data['street_name'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Building No.</strong>
          <span class="text-muted"><?= htmlspecialchars($data['building_no'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Gender</strong>
          <span class="text-muted"><?= htmlspecialchars($data['gender'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Style Preference</strong>
          <span class="text-muted"><?= htmlspecialchars($data['style_preference'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Color Palette</strong>
          <span class="text-muted"><?= htmlspecialchars($data['color_palette'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Shirt Size</strong>
          <span class="text-muted"><?= htmlspecialchars($data['top_size'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Pants Size</strong>
          <span class="text-muted"><?= htmlspecialchars($data['bottom_size'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Shoe Size</strong>
          <span class="text-muted"><?= htmlspecialchars($data['shoe_size'] ?? '') ?></span>
        </li>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
          <strong>Fabric Sensitivity</strong>
          <span class="text-muted"><?= htmlspecialchars($data['fabric_sensitivity'] ?? '') ?></span>
        </li>
      </ul>
      <div class="mt-3">
        <strong>Bio:</strong>
        <p class="text-muted mt-1 mb-0"><?= nl2br(htmlspecialchars($data['bio'] ?? '')) ?></p>
      </div>
    </div>
  </div>

  <!-- Inline Update Profile Form -->
  <div class="card-eco p-4 mt-3 d-none text-start" id="updateProfileFormContainer" style="max-width: 500px; margin: 0 auto;">
    <h5 class="mb-3">Update Profile Data</h5>
    <form action="/profile/update" method="POST">
      <div class="mb-3 border-bottom pb-2">
        <label class="form-label text-muted small text-uppercase fw-bold">Personal Info</label>
      </div>
      <div class="mb-3">
        <label for="bio" class="form-label">Bio</label>
        <textarea class="form-control" id="bio" name="bio" rows="2"><?= htmlspecialchars($data['bio'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" id="gender" name="gender">
          <option value="male" <?= (strtolower($data['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= (strtolower($data['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
        </select>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="city" class="form-label">City</label>
          <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($data['city'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="mobile_no" class="form-label">Mobile Number</label>
          <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="<?= htmlspecialchars($data['mobile_no'] ?? '') ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="street_name" class="form-label">Street Name</label>
          <input type="text" class="form-control" id="street_name" name="street_name" value="<?= htmlspecialchars($data['street_name'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="building_no" class="form-label">Building No</label>
          <input type="text" class="form-control" id="building_no" name="building_no" value="<?= htmlspecialchars($data['building_no'] ?? '') ?>">
        </div>
      </div>
      <div class="mb-3 border-bottom pb-2 mt-4">
        <label class="form-label text-muted small text-uppercase fw-bold">Style Preferences</label>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="style_preference" class="form-label">Style Preference</label>
          <input type="text" class="form-control" id="style_preference" name="style_preference" value="<?= htmlspecialchars($data['style_preference'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="color_palette" class="form-label">Color Palette</label>
          <input type="text" class="form-control" id="color_palette" name="color_palette" value="<?= htmlspecialchars($data['color_palette'] ?? '') ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="top_size" class="form-label">Top Size</label>
          <input type="text" class="form-control" id="top_size" name="top_size" value="<?= htmlspecialchars($data['top_size'] ?? '') ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label for="bottom_size" class="form-label">Bottom Size</label>
          <input type="text" class="form-control" id="bottom_size" name="bottom_size" value="<?= htmlspecialchars($data['bottom_size'] ?? '') ?>">
        </div>
        <div class="col-md-4 mb-3">
          <label for="shoe_size" class="form-label">Shoe Size</label>
          <input type="text" class="form-control" id="shoe_size" name="shoe_size" value="<?= htmlspecialchars($data['shoe_size'] ?? '') ?>">
        </div>
      </div>
      <div class="mb-3">
        <label for="fabric_sensitivity" class="form-label">Fabric Sensitivity</label>
        <input type="text" class="form-control" id="fabric_sensitivity" name="fabric_sensitivity" value="<?= htmlspecialchars($data['fabric_sensitivity'] ?? '') ?>">
      </div>
      <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('updateProfileFormContainer').classList.add('d-none')">Cancel</button>
        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
<script>window.updateSnakePlant(<?= $data['eco_points'] ?? 480 ?>);</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
