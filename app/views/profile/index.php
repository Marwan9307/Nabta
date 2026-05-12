<?php 
$data['page_title'] = $data['page_title'] ?? 'Profile'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<style>
    .profile-bg { background-color: #fcf9f4; min-height: 100vh; padding: 60px 0; font-family: 'Segoe UI', sans-serif; }
    .data-label { font-size: 0.7rem; color: #aaa; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; display: block; margin-bottom: 2px; }
    .data-value { font-size: 1.05rem; color: #333; margin-bottom: 22px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; }
    .plant-box { background: white; border-radius: 24px; padding: 40px; position: sticky; top: 120px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .edit-profile-btn { border: 1px solid #ccc; background: white; color: #666; padding: 8px 25px; border-radius: 10px; font-size: 0.9rem; transition: 0.3s; font-weight: 500; }
    .edit-profile-btn:hover { background: #5C7A5C; color: white; border-color: #5C7A5C; }
    .section-title { font-family: 'serif'; color: #2d3e2d; font-weight: 700; margin-bottom: 30px; border-left: 4px solid #5C7A5C; padding-left: 15px; }
</style>

<div class="profile-bg">
    <div class="container">
        <div class="row">
            
            <!-- الجانب الأيسر: المعلومات (8 أعمدة) -->
            <div class="col-md-8 pe-lg-5">
                
                <!-- Header: Photo + Name + Stats -->
                <div class="d-flex align-items-center justify-content-between mb-5">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-4">
                            <img src="<?= $data['avatar'] ?>" width="120" height="120" class="rounded-circle border-4 border-white shadow-sm">
                            <button class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 end-0 border shadow-sm" onclick="document.getElementById('avatarInput').click()">📷</button>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-1" style="color: #2d3e2d; font-size: 2.2rem;"><?= htmlspecialchars($data['name']) ?></h1>
                            <div class="d-flex align-items-center gap-4 mt-1">
                                <span class="text-muted small">⭐ Trust score: <strong class="text-dark"><?= $data['trust_score'] ?></strong></span>
                                <span class="text-muted small">🌿 <strong class="text-dark"><?= $data['eco_points'] ?></strong> Eco-Credits</span>
                            </div>
                        </div>
                    </div>
                    <?php if ($data['user_id'] != Session::userId()): ?>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportUserModal">
                            ⚠️ Report User
                        </button>
                    <?php endif; ?>
                </div>

                <h4 class="section-title">Account Information</h4>

                <!-- عرض البيانات -->
                <div id="infoDisplaySection">
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <span class="data-label">Email Address</span>
                            <p class="data-value"><?= htmlspecialchars($data['email']) ?></p>

                            <span class="data-label">Mobile Number</span>
                            <p class="data-value"><?= htmlspecialchars($data['mobile_no']) ?></p>

                            <span class="data-label">City / Location</span>
                            <p class="data-value"><?= htmlspecialchars($data['city']) ?></p>

                            <span class="data-label">Fabric Sensitivity</span>
                            <p class="data-value"><?= htmlspecialchars($data['fabric_sensitivity']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <span class="data-label">Gender</span>
                            <p class="data-value"><?= htmlspecialchars($data['gender']) ?></p>

                            <span class="data-label">Style Preference</span>
                            <p class="data-value"><?= htmlspecialchars($data['style_preference']) ?></p>

                            <span class="data-label">Sizes (Shirt/Pants/Shoe)</span>
                            <p class="data-value">
                                <?= htmlspecialchars($data['top_size']) ?> / 
                                <?= htmlspecialchars($data['bottom_size']) ?> / 
                                <?= htmlspecialchars($data['shoe_size']) ?>
                            </p>

                            <span class="data-label">Role</span>
                            <p class="data-value text-capitalize"><?= htmlspecialchars($data['role']) ?></p>
                        </div>
                        <div class="col-12">
                            <span class="data-label">Bio</span>
                            <p class="data-value"><?= nl2br(htmlspecialchars($data['bio'])) ?></p>
                        </div>
                    </div>
                    <button class="edit-profile-btn mt-2" onclick="toggleEdit()">Edit Information</button>
                </div>

                <!-- فورم التعديل (مخفية) -->
                <div id="editFormSection" class="d-none">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                        <form action="/profile/update" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">Full Name</label><input type="text" name="name" class="form-control" value="<?= $data['name'] ?>"></div>
                                <div class="col-md-6 mb-3"><label class="form-label small fw-bold">City</label><input type="text" name="city" class="form-control" value="<?= $data['city'] ?>"></div>
                                <div class="col-md-12 mb-3"><label class="form-label small fw-bold">Bio</label><textarea name="bio" class="form-control" rows="3"><?= $data['bio'] ?></textarea></div>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-dark px-4">Save Changes</button>
                            <button type="button" class="btn btn-link text-muted" onclick="toggleEdit()">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- الجانب الأيمن: النبتة (4 أعمدة) -->
            <div class="col-md-4 ps-md-5">
                <div class="plant-box text-center shadow-sm">
                    <div class="snake-plant-stage">
                        <svg id="snakePlantSvg" viewBox="0 0 120 120" width="220">
                            <path d="M60 105V35" stroke="#5C7A5C" stroke-width="5"/>
                            <ellipse cx="72" cy="45" rx="16" ry="24" fill="#A8B89A"/>
                            <ellipse cx="48" cy="60" rx="14" ry="22" fill="#D4A5A5"/>
                            <ellipse cx="66" cy="72" rx="12" ry="20" fill="#A8B89A"/>
                        </svg>
                    </div>
                    <h5 class="mt-4 fw-bold">Snake Plant Growth</h5>
                    <p class="text-muted small">Leaf count: <span id="leafCount" class="fw-bold text-success"><?= floor($data['eco_points'] / 100) + 1 ?></span></p>
                    <hr class="my-4" style="opacity: 0.1;">
                    <div class="text-start">
                        <p class="small fw-bold mb-2 text-success uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">ECOLOGICAL FACT</p>
                        <p class="small text-muted mb-0" style="line-height: 1.6;">Every action you take grow this plant. You are helping clean the air through circular fashion.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<form action="/profile/updatePhoto" method="POST" enctype="multipart/form-data" class="d-none">
    <input type="file" name="avatar" id="avatarInput" onchange="this.form.submit()">
</form>

<!-- Report Modal -->
<?php if ($data['user_id'] != Session::userId()): ?>
<div class="modal fade" id="reportUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Report User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/report/create" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="target_id" value="<?= htmlspecialchars($data['user_id']) ?>">
                    <p>Are you sure you want to report <strong><?= htmlspecialchars($data['name']) ?></strong> for inappropriate behavior or policy violation?</p>
                    <div class="mb-3">
                        <label for="reportType" class="form-label">Report Type</label>
                        <select name="report_type" id="reportType" class="form-select" required>
                            <option value="behavioral">Inappropriate Behavior</option>
                            <option value="communication">Spam or Harassment</option>
                            <option value="scam">Scam / Fraudulent Activity</option>
                            <option value="sustainability">Sustainability Misconduct (Greenwashing)</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reportReason" class="form-label">Reason for reporting</label>
                        <textarea name="reason" id="reportReason" class="form-control" rows="3" required placeholder="Provide specific details regarding your report..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    function toggleEdit() {
        document.getElementById('infoDisplaySection').classList.toggle('d-none');
        document.getElementById('editFormSection').classList.toggle('d-none');
    }
    if(window.updateSnakePlant) {
        window.updateSnakePlant(<?= $data['eco_points'] ?>);
    }
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>