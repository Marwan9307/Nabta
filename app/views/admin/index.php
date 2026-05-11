<?php $data['page_title'] = $data['page_title'] ?? 'Admin'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4 mb-3">
  <h5 class="mb-3">Biometric Retina Scan Simulation</h5>
  <div id="retinaSim" class="biometric-screen mb-2">
    <div class="scan-line"></div>
  </div>
  <small class="text-muted">Security check runs for 2 seconds before entry.</small>
</div>
<div class="card-eco p-4 mb-3">
  <h2>Admin Dashboard</h2>
  <div class="row g-3">
    <div class="col-lg-6"><canvas id="styleTrends"></canvas></div>
    <div class="col-lg-6"><canvas id="sustainabilityAudit"></canvas></div>
  </div>
</div>
<div class="card-eco p-4 mb-4">
  <h4>Upcycler Applications</h4>
  <table class="table">
    <thead><tr><th>User</th><th>Status</th><th>Actions</th><th>Reason for rejection</th></tr></thead>
    <tbody>
      <?php if (!empty($data['pending_upcyclers'])): ?>
        <?php foreach ($data['pending_upcyclers'] as $upcycler): ?>
        <tr>
          <td><?= htmlspecialchars($upcycler['username']) ?></td>
          <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($upcycler['status']) ?></span></td>
          <td>
            <div class="d-flex gap-2">
              <form method="post" action="/admin/approve-upcycler">
                <input type="hidden" name="user_id" value="<?= $upcycler['user_id'] ?>">
                <button class="btn btn-sm btn-success">Approve</button>
              </form>
              <form method="post" action="/admin/reject-upcycler" class="d-flex gap-2">
                <input type="hidden" name="user_id" value="<?= $upcycler['user_id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Reject</button>
              </form>
            </div>
          </td>
          <td>
            <input type="text" name="reason" form="rejectForm_<?= $upcycler['user_id'] ?>" class="form-control form-control-sm" placeholder="Optional reason...">
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-center text-muted">No pending upcycler applications at this time.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="card-eco p-4">
  <h4>Manage Users & Roles</h4>
  <table class="table">
    <thead><tr><th>Username</th><th>Email</th><th>Current Role</th><th>Actions</th></tr></thead>
    <tbody>
      <?php if (!empty($data['all_users'])): ?>
        <?php foreach ($data['all_users'] as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
          <td>
            <?php if ($user['role'] !== 'admin'): ?>
            <form method="post" action="/admin/make-admin" onsubmit="return confirm('Are you sure you want to promote <?= htmlspecialchars($user['username']) ?> to Admin?');">
              <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
              <button type="submit" class="btn btn-sm btn-primary">Make Admin</button>
            </form>
            <?php else: ?>
              <span class="text-muted small">Admin</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<script>
new Chart(document.getElementById('styleTrends'), {type:'line', data:{labels:['Vintage','Minimalist','Streetwear'], datasets:[{label:'Style Trends', data:[32,24,39], borderColor:'#5c7a5c'}]}});
new Chart(document.getElementById('sustainabilityAudit'), {type:'bar', data:{labels:['Water','CO2','Waste'], datasets:[{label:'Sustainability Audit', data:[68,54,49], backgroundColor:['#a8b89a','#c4956a','#d4a5a5']}]}});
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
