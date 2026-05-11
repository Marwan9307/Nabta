<?php $data['page_title'] = $data['page_title'] ?? 'Login'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card-eco p-4">
      <h2 class="mb-3">Welcome back</h2>
      <?php if (!empty($data['error'])): ?><div class="alert alert-danger"><?= $data['error'] ?></div><?php endif; ?>
      <form method="post" action="/auth/login">
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $data['email'] ?? '' ?>"></div>
        <div class="mb-3">
          <label>Password</label>
          <div class="input-group">
            <input type="password" name="password" id="loginPassword" class="form-control" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('loginPassword', this)">See</button>
          </div>
        </div>
        <button class="btn btn-clay w-100">Login</button>
      </form>
      <p class="mt-3 text-center"><a href="/auth/register">Don't have an account? Register</a></p>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>