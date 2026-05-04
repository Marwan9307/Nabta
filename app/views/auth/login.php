<?php $data['page_title'] = $data['page_title'] ?? 'Login'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card-eco p-4">
      <h2 class="mb-3">Welcome back</h2>
      <form method="post" action="/auth/login">
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $data['email'] ?? '' ?>"></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control"></div>
        <button class="btn btn-clay w-100">Login</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
