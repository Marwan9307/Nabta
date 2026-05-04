<?php $data['page_title'] = $data['page_title'] ?? 'Admin Reports'; require_once __DIR__ . '/../layout/header.php'; ?>
<div class="card-eco p-4">
  <h2>Reports</h2>
  <p><?= $data['report_summary'] ?? 'Behavioral and sustainability reports overview.' ?></p>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
