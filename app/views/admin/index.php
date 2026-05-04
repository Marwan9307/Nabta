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
<div class="card-eco p-4">
  <h4>Upcycler Applications</h4>
  <table class="table">
    <thead><tr><th>User</th><th>Status</th><th>Actions</th><th>Reason for rejection</th></tr></thead>
    <tbody>
      <tr><td>Aya</td><td>Pending</td><td><button class="btn btn-sm btn-success">Approve</button> <button class="btn btn-sm btn-outline-danger">Reject</button></td><td><textarea class="form-control form-control-sm"></textarea></td></tr>
      <tr><td>Reem</td><td>Pending</td><td><button class="btn btn-sm btn-success">Approve</button> <button class="btn btn-sm btn-outline-danger">Reject</button></td><td><textarea class="form-control form-control-sm"></textarea></td></tr>
    </tbody>
  </table>
</div>
<script>
new Chart(document.getElementById('styleTrends'), {type:'line', data:{labels:['Vintage','Minimalist','Streetwear'], datasets:[{label:'Style Trends', data:[32,24,39], borderColor:'#5c7a5c'}]}});
new Chart(document.getElementById('sustainabilityAudit'), {type:'bar', data:{labels:['Water','CO2','Waste'], datasets:[{label:'Sustainability Audit', data:[68,54,49], backgroundColor:['#a8b89a','#c4956a','#d4a5a5']}]}});
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
