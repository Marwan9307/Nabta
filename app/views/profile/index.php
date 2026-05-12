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
</div>
<script>window.updateSnakePlant(<?= $data['eco_points'] ?? 480 ?>);</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
