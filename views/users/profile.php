<?php
$empTypeTh    = getEmployeeTypeOptions();
$profileRoles = isset($user['roles']) ? array_map('trim', explode(',', $user['roles'])) : array($user['role']);
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">โปรไฟล์ของฉัน</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon"><i class="fas fa-user-circle"></i></div>
    <div>
      <div class="page-banner-title">โปรไฟล์ของฉัน</div>
      <div class="page-banner-sub"><?php echo e(getFullname($user)); ?> | @<?php echo e($user['username']); ?></div>
    </div>
  </div>

  <div class="row g-3">

    <!-- ซ้าย: ข้อมูลส่วนตัว -->
    <div class="col-12 col-lg-8">

      <div class="page-card mb-3">
        <div class="page-card-header"><i class="fas fa-user me-2 text-primary"></i>ข้อมูลส่วนตัว</div>
        <div class="page-card-body">
          <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background:linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                 style="width:56px;height:56px;font-size:1.5rem;background:#1565c0;">
              <?php echo mb_substr($user['firstname'], 0, 1, 'UTF-8'); ?>
            </div>
            <div>
              <div class="fw-bold"><?php echo e(getFullname($user)); ?></div>
              <div class="text-muted small mb-1">@<?php echo e($user['username']); ?></div>
              <div class="d-flex flex-wrap gap-1 mt-1">
                <?php foreach ($profileRoles as $pr): if (empty($pr)) continue; ?>
                <span class="badge <?php echo getRoleBadgeClass($pr); ?>">
                  <i class="fas fa-shield-alt me-1"></i><?php echo getRoleLabel($pr); ?>
                </span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="row g-2" style="font-size:0.88rem;">
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-user fa-fw me-1"></i>ชื่อ-นามสกุล</div>
                <div class="fw-semibold"><?php echo e($user['prefix'].' '.$user['firstname'].' '.$user['lastname']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-briefcase fa-fw me-1"></i>ตำแหน่ง</div>
                <div class="fw-semibold"><?php echo e($user['position']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-id-badge fa-fw me-1"></i>ประเภทพนักงาน</div>
                <div class="fw-semibold"><?php echo isset($empTypeTh[$user['employee_type']]) ? $empTypeTh[$user['employee_type']] : e($user['employee_type']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-landmark fa-fw me-1"></i>สำนักงาน</div>
                <div class="fw-semibold"><?php echo e($user['office_name']); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-phone fa-fw me-1"></i>เบอร์โทรศัพท์</div>
                <div class="fw-semibold"><?php echo $user['phone'] ? e($user['phone']) : '<span class="text-muted">-</span>'; ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-envelope fa-fw me-1"></i>อีเมล</div>
                <div class="fw-semibold"><?php echo $user['email'] ? e($user['email']) : '<span class="text-muted">-</span>'; ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-toggle-on fa-fw me-1"></i>สถานะบัญชี</div>
                <div><?php echo $user['is_active'] ? '<span class="badge bg-success">ใช้งานอยู่</span>' : '<span class="badge bg-secondary">ระงับ</span>'; ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="p-2 rounded border bg-light">
                <div class="text-muted small mb-1"><i class="fas fa-calendar-alt fa-fw me-1"></i>วันที่สร้างบัญชี</div>
                <div class="fw-semibold"><?php echo thaiDate($user['created_at']); ?></div>
              </div>
            </div>
          </div>
          <div class="alert alert-light border mt-3 mb-0 py-2 small">
            <i class="fas fa-info-circle text-primary me-1"></i>
            หากต้องการแก้ไขข้อมูลส่วนตัว กรุณาติดต่อผู้ดูแลระบบ
          </div>
        </div>
      </div>

      <!-- เปลี่ยนรหัสผ่าน -->
      <div class="page-card">
        <div class="page-card-header"><i class="fas fa-lock me-2 text-warning"></i>เปลี่ยนรหัสผ่าน</div>
        <div class="page-card-body">
          <form method="POST" action="?page=users&action=update_profile" id="passwordForm">
            <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
            <input type="hidden" name="position" value="<?php echo e($user['position']); ?>">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">รหัสผ่านปัจจุบัน <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                  <input type="password" name="current_password" id="curPw" class="form-control" required autocomplete="current-password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePw('curPw','eyeCur')"><i class="fas fa-eye" id="eyeCur"></i></button>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">รหัสผ่านใหม่ <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                  <input type="password" name="new_password" id="newPw" class="form-control"
                         required minlength="6" autocomplete="new-password"
                         oninput="checkStrength(this.value)" placeholder="อย่างน้อย 6 ตัว">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePw('newPw','eyeNew')"><i class="fas fa-eye" id="eyeNew"></i></button>
                </div>
                <div id="strengthBar" style="display:none;" class="mt-1">
                  <div class="progress" style="height:4px;"><div class="progress-bar" id="strengthBarInner" style="width:0%;transition:width .3s;"></div></div>
                  <small id="strengthText" class="text-muted"></small>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                  <input type="password" name="confirm_password" id="confPw" class="form-control"
                         required autocomplete="new-password" oninput="checkMatch()">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePw('confPw','eyeConf')"><i class="fas fa-eye" id="eyeConf"></i></button>
                </div>
                <small id="matchMsg" class="mt-1 d-block"></small>
              </div>
            </div>
            <div class="d-flex gap-2 mt-3 pt-3 border-top">
              <button type="submit" class="btn btn-warning btn-sm fw-semibold">
                <i class="fas fa-save me-1"></i>บันทึกรหัสผ่านใหม่
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>

    <!-- ขวา: ข้อมูลสรุป -->
    <div class="col-12 col-lg-4">
      <div class="page-card">
        <div class="page-card-header"><i class="fas fa-id-card me-2 text-info"></i>สรุปบัญชีของฉัน</div>
        <div class="page-card-body p-0">
          <table class="table table-sm table-borderless mb-0" style="font-size:0.82rem;">
            <tbody>
              <tr><td class="text-muted ps-3">ชื่อผู้ใช้</td><td><code><?php echo e($user['username']); ?></code></td></tr>
              <tr><td class="text-muted ps-3">บทบาท</td>
                <td>
                  <?php foreach ($profileRoles as $pr): if (empty($pr)) continue; ?>
                  <span class="badge <?php echo getRoleBadgeClass($pr); ?>" style="font-size:0.7rem;margin:1px;"><?php echo getRoleLabel($pr); ?></span>
                  <?php endforeach; ?>
                </td>
              </tr>
              <tr><td class="text-muted ps-3">สำนักงาน</td><td style="font-size:0.8rem;"><?php echo e($user['office_name']); ?></td></tr>
              <tr><td class="text-muted ps-3">ตำแหน่ง</td><td><?php echo e($user['position']); ?></td></tr>
              <tr><td class="text-muted ps-3">โทร</td><td><?php echo e($user['phone']); ?></td></tr>
              <tr><td class="text-muted ps-3 pb-3">สร้างเมื่อ</td><td class="pb-3"><?php echo thaiDate($user['created_at']); ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="alert alert-warning mt-3 small">
        <i class="fas fa-shield-alt me-1"></i>
        <strong>ความปลอดภัย:</strong> ใช้รหัสผ่านที่ประกอบด้วยตัวอักษร ตัวเลข และอักขระพิเศษ อย่างน้อย 6 ตัว
      </div>
    </div>

  </div>
</main>

<?php
$extraJs = '<script>
function togglePw(id, iconId) {
  var el = document.getElementById(id), ic = document.getElementById(iconId);
  el.type = (el.type === "password") ? "text" : "password";
  ic.className = (el.type === "text") ? "fas fa-eye-slash" : "fas fa-eye";
}
function checkStrength(v) {
  var bar = document.getElementById("strengthBar"), bi = document.getElementById("strengthBarInner"), tx = document.getElementById("strengthText");
  if (!v) { bar.style.display = "none"; return; }
  bar.style.display = "block";
  var s = 0;
  if (v.length >= 6) s++;
  if (v.length >= 10) s++;
  if (/[A-Z]/.test(v)) s++;
  if (/[0-9]/.test(v)) s++;
  if (/[^A-Za-z0-9]/.test(v)) s++;
  var c = ["#dc3545","#fd7e14","#ffc107","#20c997","#198754"];
  var l = ["อ่อนมาก","อ่อน","ปานกลาง","แข็งแรง","แข็งแรงมาก"];
  var p = [20,40,60,80,100]; var i = s > 0 ? s - 1 : 0;
  bi.style.width = p[i] + "%"; bi.style.backgroundColor = c[i];
  tx.textContent = "ความแข็งแรง: " + l[i]; tx.style.color = c[i];
}
function checkMatch() {
  var nw = document.getElementById("newPw").value, cf = document.getElementById("confPw").value, m = document.getElementById("matchMsg");
  if (!cf) { m.textContent = ""; return; }
  m.innerHTML = (nw === cf)
    ? "<span class=\"text-success\"><i class=\"fas fa-check me-1\"></i>ตรงกัน</span>"
    : "<span class=\"text-danger\"><i class=\"fas fa-times me-1\"></i>ไม่ตรงกัน</span>";
}
document.getElementById("passwordForm").addEventListener("submit", function(e) {
  var nw = document.getElementById("newPw").value, cf = document.getElementById("confPw").value;
  if (nw !== cf) {
    e.preventDefault();
    Swal.fire({icon:"error",title:"รหัสผ่านไม่ตรงกัน",toast:true,position:"top-end",showConfirmButton:false,timer:3000});
    return;
  }
  if (nw.length < 6) {
    e.preventDefault();
    Swal.fire({icon:"warning",title:"รหัสผ่านต้องมีอย่างน้อย 6 ตัว",toast:true,position:"top-end",showConfirmButton:false,timer:3000});
  }
});
</script>';
?>
