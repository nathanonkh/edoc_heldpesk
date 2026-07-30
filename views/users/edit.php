<?php
$employeeTypes  = getEmployeeTypeOptions();
$officeOptions  = getOfficeOptions();
$allRoleKeys    = array('submitter','inspector','approver','operator','admin');
$roleDesc       = array(
    'submitter' => 'ส่งเอกสาร ติดตามสถานะ และแก้ไขเมื่อถูกส่งกลับ',
    'inspector' => 'ตรวจสอบเอกสาร กรอกเลขรับหนังสือ',
    'approver'  => 'อนุมัติเอกสารที่ผ่านการตรวจสอบ',
    'operator'  => 'บันทึกผลดำเนินการขั้นสุดท้าย',
    'admin'     => 'เข้าถึงได้ทุกส่วน จัดการสมาชิกและสหกรณ์',
);
$empTypeTh     = getEmployeeTypeOptions();
$userRolesArr  = array_map('trim', explode(',', $user['roles']));
$validRoles    = array('submitter','inspector','approver','operator','admin');
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=users">จัดการสมาชิก</a></li>
    <li class="breadcrumb-item active">แก้ไขสมาชิก</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon"><i class="fas fa-user-edit"></i></div>
    <div>
      <div class="page-banner-title">แก้ไขข้อมูลสมาชิก</div>
      <div class="page-banner-sub"><?php echo e(getFullname($user)); ?> | @<?php echo e($user['username']); ?></div>
    </div>
  </div>

  <form method="POST" action="?page=users&action=update">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

    <div class="row g-3">
      <div class="col-12 col-lg-8">

        <!-- ข้อมูลส่วนตัว -->
        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-user me-2 text-primary"></i>ข้อมูลส่วนตัว</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-4 col-md-2">
                <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                <select name="prefix" class="form-select form-select-sm" required>
                  <?php foreach (array('นาย','นาง','นางสาว') as $p): ?>
                  <option value="<?php echo $p; ?>" <?php echo $user['prefix']===$p ? 'selected' : ''; ?>><?php echo $p; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-8 col-md-5">
                <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                <input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo e($user['firstname']); ?>">
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                <input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo e($user['lastname']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">เบอร์โทร</label>
                <input type="text" name="phone" class="form-control form-control-sm" value="<?php echo e($user['phone']); ?>">
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control form-control-sm" value="<?php echo e($user['email']); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- บัญชีและสิทธิ์ -->
        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-shield-alt me-2 text-warning"></i>บัญชีและสิทธิ์</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control form-control-sm bg-light" readonly value="<?php echo e($user['username']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">สถานะบัญชี</label>
                <select name="is_active" class="form-select form-select-sm">
                  <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>ใช้งาน</option>
                  <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>ระงับ</option>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">สำนักงาน <span class="text-danger">*</span></label>
                <select name="office_name" id="officeSelect" class="form-select form-select-sm" required>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>" <?php echo $user['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">รหัสผ่านใหม่ <small class="text-muted">(เว้นว่างถ้าไม่เปลี่ยน)</small></label>
                <input type="password" name="password" class="form-control form-control-sm" autocomplete="new-password">
              </div>
              <div class="col-12">
                <label class="form-label">บทบาท <span class="text-danger">*</span> <small class="text-muted">(เลือกได้มากกว่า 1 บทบาท)</small></label>
                <div class="row g-2">
                  <?php foreach ($allRoleKeys as $rk):
                    $checked = in_array($rk, $userRolesArr);
                  ?>
                  <div class="col-12 col-sm-6">
                    <div class="border rounded p-2 d-flex align-items-start gap-2 role-card" id="role-card-<?php echo $rk; ?>"
                         style="<?php echo $checked ? 'border-color:#0d6efd;background:#f0f7ff;' : ''; ?>">
                      <input type="checkbox" name="roles[]" value="<?php echo $rk; ?>"
                             id="role_<?php echo $rk; ?>" class="mt-1 role-checkbox"
                             <?php echo $checked ? 'checked' : ''; ?>>
                      <label for="role_<?php echo $rk; ?>" class="mb-0 flex-grow-1" style="cursor:pointer;">
                        <span class="badge <?php echo getRoleBadgeClass($rk); ?> me-1"><?php echo getRoleLabel($rk); ?></span>
                        <span class="text-muted small d-block mt-1"><?php echo $roleDesc[$rk]; ?></span>
                      </label>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ตำแหน่งงาน -->
        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-briefcase me-2 text-info"></i>ตำแหน่งงาน</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">ประเภทพนักงาน <span class="text-danger">*</span></label>
                <select name="employee_type" id="empType" class="form-select form-select-sm" required>
                  <?php foreach ($employeeTypes as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $user['employee_type']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">ตำแหน่ง <span class="text-danger">*</span></label>
                <select name="position" id="posSelect" class="form-select form-select-sm" required></select>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>บันทึกการแก้ไข</button>
          <a href="?page=users" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>
      </div>

      <!-- ขวา: ข้อมูลปัจจุบัน -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-id-card me-2 text-info"></i>ข้อมูลปัจจุบัน</div>
          <div class="page-card-body">
            <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded" style="background:linear-gradient(135deg,#e3f2fd,#f8f9ff);">
              <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                   style="width:48px;height:48px;font-size:1.3rem;background:#1565c0;">
                <?php echo mb_substr($user['firstname'], 0, 1, 'UTF-8'); ?>
              </div>
              <div>
                <div class="fw-bold small"><?php echo e(getFullname($user)); ?></div>
                <div class="d-flex flex-wrap gap-1 mt-1">
                  <?php foreach ($userRolesArr as $r): if (!in_array($r, $validRoles)) continue; ?>
                  <span class="badge <?php echo getRoleBadgeClass($r); ?>" style="font-size:0.7rem;"><?php echo getRoleLabel($r); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <table class="table table-sm table-borderless mb-0" style="font-size:0.82rem;">
              <tbody>
                <tr><td class="text-muted">ชื่อผู้ใช้</td><td><code><?php echo e($user['username']); ?></code></td></tr>
                <tr><td class="text-muted">สำนักงาน</td><td style="font-size:0.8rem;"><?php echo e($user['office_name']); ?></td></tr>
                <tr><td class="text-muted">ประเภท</td><td><?php echo isset($empTypeTh[$user['employee_type']]) ? $empTypeTh[$user['employee_type']] : e($user['employee_type']); ?></td></tr>
                <tr><td class="text-muted">ตำแหน่ง</td><td><?php echo e($user['position']); ?></td></tr>
                <tr><td class="text-muted">สถานะ</td><td><?php echo $user['is_active'] ? '<span class="badge bg-success">ใช้งาน</span>' : '<span class="badge bg-secondary">ระงับ</span>'; ?></td></tr>
                <tr><td class="text-muted">สร้างเมื่อ</td><td><?php echo thaiDate($user['created_at']); ?></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<?php
$currentPos = e($user['position']);
$extraJs = '<script>
setupPositionSelector("empType", "posSelect", "' . $currentPos . '");
var checkboxes = document.querySelectorAll(".role-checkbox");
for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener("change", function() {
    var card = document.getElementById("role-card-" + this.value);
    if (card) {
      card.style.borderColor = this.checked ? "#0d6efd" : "";
      card.style.background  = this.checked ? "#f0f7ff" : "";
    }
  });
}
</script>';
?>
