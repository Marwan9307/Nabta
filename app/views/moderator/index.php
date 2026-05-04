<?php $data['page_title'] = $data['page_title'] ?? 'Moderator'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Community Reports (Behavioral)</h2>
  <table class="table">
    <thead><tr><th>Report ID</th><th>User</th><th>Type</th><th>Status</th></tr></thead>
    <tbody>
      <tr><td>#R12</td><td>User87</td><td>Harassment</td><td>Open</td></tr>
      <tr><td>#R13</td><td>User22</td><td>Spam</td><td>Resolved</td></tr>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
