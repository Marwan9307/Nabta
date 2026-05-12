<?php $data['page_title'] = $data['page_title'] ?? 'Moderator'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Community Reports (Behavioral)</h2>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>Report ID</th><th>Target ID</th><th>Reason</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach (($data['reports'] ?? []) as $report): ?>
          <tr>
            <td>#R<?= htmlspecialchars($report['report_id'] ?? '') ?></td>
            <td>User ID: <?= htmlspecialchars($report['target_id'] ?? '') ?></td>
            <td><?= htmlspecialchars($report['reason'] ?? '') ?></td>
            <td>
              <span class="badge <?= ($report['report_status'] ?? '') === 'pending' ? 'bg-warning text-dark' : 'bg-success' ?>">
                <?= ucfirst(htmlspecialchars($report['report_status'] ?? 'pending')) ?>
              </span>
            </td>
            <td>
              <?php if (($report['report_status'] ?? 'pending') === 'pending'): ?>
                <form action="/moderator/resolve" method="POST" class="d-inline">
                  <input type="hidden" name="report_id" value="<?= htmlspecialchars($report['report_id'] ?? '') ?>">
                  <input type="hidden" name="status" value="resolved">
                  <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                </form>
              <?php else: ?>
                <span class="text-muted small">Resolved</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($data['reports'])): ?>
          <tr><td colspan="5" class="text-center">No reports found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
