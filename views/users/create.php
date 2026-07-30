<?php
$employeeTypes = getEmployeeTypeOptions();
$officeOptions = getOfficeOptions();
$allRoleKeys   = array('submitter','inspector','approver','operator','admin');
$roleDesc      = array(
    'submitter' => 'ส่งเอกสาร ติดตามสถานะ และแก้ไขเมื่อถูกส่งกลับ',
    'inspector' => 'ตรวจสอบเอกสาร กรอกเลขรับหนังสือ',
    'approver'  => 'อนุมัติเอกสารที่ผ่านการตรวจสอบ',
    'operator'  => 'บันทึกผลดำเนินการขั้นสุดท้าย',
    'admin'     => 'เข้าถึงได้ทุกส่วน จัดการสมาชิกและสหกรณ์',
);
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=users">จัดการสมาชิก</a></li>
    <li class="breadcrumb-item active">เพิ่มสมาชิก</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-success"><i class="fas fa-user-plus"></i></div>
    <div>
      <div class="page-banner-title">เพิ่มสมาชิกใหม่</div>
      <div class="page-banner-sub">สมาชิก 1 คนสามารถมีได้หลายบทบาท</div>
    </div>
  </div>

  <form method="POST" action="?page=users&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

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
                  <option value="นาย">นาย</option>
                  <option value="นาง">นาง</option>
                  <option value="นางสาว">นางสาว</option>
                </select>
              </div>
              <div class="col-8 col-md-5">
                <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                <input type="text" name="firstname" class="form-control form-control-sm" required>
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                <input type="text" name="lastname" class="form-control form-control-sm" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">เบอร์โทร</label>
                <input type="text" name="phone" class="form-control form-control-sm">
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control form-control-sm">
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
                <label class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control form-control-sm" required autocomplete="off">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control form-control-sm" required autocomplete="new-password">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">สำนักงาน <span class="text-danger">*</span></label>
                <select name="office_name" id="officeSelect" class="form-select form-select-sm" required>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>"><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">บทบาท <span class="text-danger">*</span> <small class="text-muted">(เลือกได้มากกว่า 1 บทบาท)</small></label>
                <div class="row g-2">
                  <?php foreach ($allRoleKeys as $rk): ?>
                  <div class="col-12 col-sm-6">
                    <div class="border rounded p-2 d-flex align-items-start gap-2 role-card" id="role-card-<?php echo $rk; ?>">
                      <input type="checkbox" name="roles[]" value="<?php echo $rk; ?>"
                             id="role_<?php echo $rk; ?>" class="mt-1 role-checkbox"
                             <?php echo $rk === 'submitter' ? 'checked' : ''; ?>>
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
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
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
          <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save me-1"></i>บันทึกสมาชิก</button>
          <a href="?page=users" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>
      </div>

      <!-- ขวา: คำแนะนำ -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-info-circle me-2 text-info"></i>การทำงานของบทบาท</div>
          <div class="page-card-body p-0">
            <div class="px-3 py-2 border-bottom">
              <div class="small fw-semibold text-muted mb-2">ลำดับ Workflow เอกสาร</div>
              <div class="d-flex align-items-center gap-2 flex-wrap small">
                <span class="badge bg-secondary">submitter</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="badge bg-primary">inspector</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="badge bg-info text-dark">approver</span>
                <i class="fas fa-arrow-right text-muted"></i>
                <span class="badge badge-purple text-white">operator</span>
              </div>
            </div>
            <?php foreach ($allRoleKeys as $rk): ?>
            <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom">
              <span class="badge <?php echo getRoleBadgeClass($rk); ?> flex-shrink-0 mt-1"><?php echo getRoleLabel($rk); ?></span>
              <span class="text-muted" style="font-size:0.8rem;"><?php echo $roleDesc[$rk]; ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<?php
$extraJs = '<script>
setupPositionSelector("empType", "posSelect", "");
var checkboxes = document.querySelectorAll(".role-checkbox");
for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener("change", function() {
    var card = document.getElementById("role-card-" + this.value);
    if (card) {
      card.style.borderColor = this.checked ? "#0d6efd" : "";
      card.style.background  = this.checked ? "#f0f7ff" : "";
    }
  });
  var card = document.getElementById("role-card-" + checkboxes[i].value);
  if (card && checkboxes[i].checked) {
    card.style.borderColor = "#0d6efd";
    card.style.background  = "#f0f7ff";
  }
}
</script>';
?>
