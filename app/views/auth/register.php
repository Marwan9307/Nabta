<?php $data['page_title'] = $data['page_title'] ?? 'Register'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card-eco p-4">
      <h2 class="mb-3">Create account</h2>
      <?php if (!empty($data['error'])): ?><div class="alert alert-danger"><?= $data['error'] ?></div><?php endif; ?>
      <form method="post" action="/auth/register" enctype="multipart/form-data">
        <ul class="nav nav-pills mb-3" id="registerTabs" role="tablist">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#step1" type="button">Step 1</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#step2" type="button">Step 2</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#step3" type="button">Step 3</button></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="step1">
            <div class="row g-3">
              <div class="col-md-6"><input class="form-control" name="username" placeholder="Username" value="<?= $data['username'] ?? '' ?>"></div>
              <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email" value="<?= $data['email'] ?? '' ?>"></div>
              <div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="Password"></div>
              <div class="col-md-6"><input name="phone" class="form-control" placeholder="Phone (01XXXXXXXXX)" pattern="^(010|011|012|015)\d{8}$"></div>
              <div class="col-md-6">
                <select name="city" class="form-select">
                  <option>Cairo</option><option>Giza</option><option>Alexandria</option><option>Aswan</option><option>Luxor</option><option>Port Said</option>
                </select>
              </div>
              <div class="col-md-6 d-flex align-items-center gap-2">
                <label class="tag-pill"><input type="radio" name="gender" value="female"> Female</label>
                <label class="tag-pill"><input type="radio" name="gender" value="male"> Male</label>
                <label class="tag-pill"><input type="radio" name="gender" value="other"> Prefer not say</label>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="step2">
            <div class="mb-3"><label>Profile Picture (Optional)</label><input type="file" name="profile_picture" class="form-control"></div>
            <textarea class="form-control" name="bio" rows="4" placeholder="Bio"><?= $data['bio'] ?? '' ?></textarea>
          </div>
          <div class="tab-pane fade" id="step3">
            <p class="text-muted mb-3">You're all set! Click below to create your account.</p>
            <div class="text-end"><button class="btn btn-clay" type="submit">Create Account</button></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
