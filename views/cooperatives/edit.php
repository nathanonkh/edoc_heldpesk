<?php
$cooperativeTypes = getCooperativeTypeOptions();
$officeOptions    = getOfficeOptions();
$statusOptions    = getCooperativeStatusOptions();
$provinces        = getProvinceOptions();
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=cooperatives">จัดการสหกรณ์</a></li>
    <li class="breadcrumb-item active">แก้ไขสหกรณ์</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-warning"><i class="fas fa-edit"></i></div>
    <div>
      <div class="page-banner-title">แก้ไขข้อมูลสหกรณ์</div>
      <div class="page-banner-sub"><?php echo e($coop['name']); ?></div>
    </div>
  </div>

  <form method="POST" action="?page=cooperatives&action=update">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $coop['id']; ?>">

    <div class="row g-3">
      <div class="col-12 col-lg-8">

        <div class="page-card mb-3">
          <div class="page-card-header"><span><i class="fas fa-building me-2 text-primary"></i>ข้อมูลพื้นฐาน</span></div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">รหัสสหกรณ์ <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control form-control-sm" required
                       value="<?php echo e($coop['code']); ?>" placeholder="เช่น KKN001">
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">ชื่อสหกรณ์ <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form-control-sm" required value="<?php echo e($coop['name']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">เลขทะเบียนสหกรณ์</label>
                <input type="text" name="registration_no" class="form-control form-control-sm" value="<?php echo e($coop['registration_no']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">รหัส 13 หลัก</label>
                <input type="text" name="regis_13digit" class="form-control form-control-sm" maxlength="13" value="<?php echo e($coop['regis_13digit']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">วันที่จดทะเบียน</label>
                <div class="input-group input-group-sm">
                  <input type="text" name="register_date" id="registerDateInput"
                         class="form-control form-control-sm" maxlength="10" value="<?php echo e($coop['register_date']); ?>">
                  <span class="input-group-text" id="registerDatePreview" style="font-size:0.76rem;min-width:100px;color:#555;">
                    <?php echo formatThaiDate2($coop['register_date']); ?>
                  </span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">ประเภทสหกรณ์ <span class="text-danger">*</span></label>
                <select name="type_name" class="form-select form-select-sm" required>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>" <?php echo $coop['type_name']===$t ? 'selected' : ''; ?>><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-3">
                <label class="form-label">วันสิ้นปีบัญชี</label>
                <div class="input-group input-group-sm">
                  <input type="text" name="fiscal_year" id="fiscalYearInput"
                         class="form-control form-control-sm" maxlength="5"
                         value="<?php echo e($coop['fiscal_year']); ?>" oninput="previewFiscalYear(this.value)">
                  <span class="input-group-text" id="fiscalPreview" style="font-size:0.78rem;min-width:90px;color:#555;">
                    <?php echo formatFiscalYear($coop['fiscal_year']); ?>
                  </span>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <label class="form-label">สถานะ <span class="text-danger">*</span></label>
                <select name="status" class="form-select form-select-sm" required>
                  <?php foreach ($statusOptions as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $coop['status']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="page-card mb-3">
          <div class="page-card-header"><span><i class="fas fa-map-marker-alt me-2 text-danger"></i>ที่ตั้ง</span></div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">ที่อยู่</label>
                <textarea name="address" class="form-control form-control-sm" rows="2"><?php echo e($coop['address']); ?></textarea>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">ตำบล</label>
                <input type="text" name="subdistrict" class="form-control form-control-sm" value="<?php echo e($coop['subdistrict']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">อำเภอ</label>
                <input type="text" name="district" class="form-control form-control-sm" value="<?php echo e($coop['district']); ?>">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">จังหวัด</label>
                <select name="province" class="form-select form-select-sm">
                  <option value="">— เลือกจังหวัด —</option>
                  <?php foreach ($provinces as $p): ?>
                  <option value="<?php echo e($p); ?>" <?php echo $coop['province']===$p ? 'selected' : ''; ?>><?php echo e($p); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="page-card mb-3">
          <div class="page-card-header"><span><i class="fas fa-landmark me-2 text-warning"></i>สำนักงานที่รับผิดชอบ</span></div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">สำนักงานตรวจบัญชีสหกรณ์ <span class="text-danger">*</span></label>
                <select name="office_name" class="form-select form-select-sm" required>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>" <?php echo $coop['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>บันทึกการแก้ไข</button>
          <a href="?page=cooperatives" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>

      </div>

      <!-- ขวา: ข้อมูลปัจจุบัน -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><span><i class="fas fa-info-circle me-2 text-info"></i>ข้อมูลปัจจุบัน</span></div>
          <div class="page-card-body p-0">
            <table class="table table-sm table-borderless mb-0" style="font-size:0.82rem;">
              <tbody>
                <tr><td class="text-muted ps-3" style="width:45%;white-space:nowrap;">รหัส</td><td><code><?php echo e($coop['code']); ?></code></td></tr>
                <tr><td class="text-muted ps-3">ชื่อ</td><td><?php echo e($coop['name']); ?></td></tr>
                <tr><td class="text-muted ps-3">ประเภท</td><td><?php echo e($coop['type_name']); ?></td></tr>
                <tr><td class="text-muted ps-3">จังหวัด</td><td><?php echo e($coop['province']); ?></td></tr>
                <tr><td class="text-muted ps-3">สถานะ</td><td><span class="badge <?php echo getCooperativeStatusBadge($coop['status']); ?>"><?php echo getCooperativeStatusLabel($coop['status']); ?></span></td></tr>
                <tr><td class="text-muted ps-3">เลขทะเบียน</td><td><?php echo e($coop['registration_no']); ?></td></tr>
                <tr><td class="text-muted ps-3">13 หลัก</td><td style="font-size:0.75rem;"><?php echo e($coop['regis_13digit']); ?></td></tr>
                <tr><td class="text-muted ps-3">วันจดทะเบียน</td><td><?php echo formatThaiDate2($coop['register_date']); ?></td></tr>
                <tr><td class="text-muted ps-3">ที่อยู่</td><td><?php echo e($coop['address']); ?></td></tr>
                <tr><td class="text-muted ps-3">ตำบล</td><td><?php echo e($coop['subdistrict']); ?></td></tr>
                <tr><td class="text-muted ps-3">อำเภอ</td><td><?php echo e($coop['district']); ?></td></tr>
                <tr><td class="text-muted ps-3">สิ้นปีบัญชี</td><td><?php echo formatFiscalYear($coop['fiscal_year']); ?></td></tr>
                <tr><td class="text-muted ps-3 pb-3">สำนักงาน</td><td class="pb-3" style="font-size:0.78rem;"><?php echo e($coop['office_name']); ?></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </form>

</main>

<?php
$extraJs = '<script>
document.getElementById("fiscalYearInput").addEventListener("keypress", function(e) {
  if (!/[\\d\\/]/.test(String.fromCharCode(e.which))) e.preventDefault();
});
document.getElementById("fiscalYearInput").addEventListener("input", function() {
  var v = this.value.replace(/[^\\d\\/]/g,"");
  var d = v.replace(/\\//g,"");
  if (d.length >= 2 && v.indexOf("/")===-1) v = d.slice(0,2)+"/"+d.slice(2);
  this.value = v;
  previewFiscalYear(v, "fiscalPreview");
});
setupDateInput("registerDateInput", "registerDatePreview", true);
previewFiscalYear(document.getElementById("fiscalYearInput").value, "fiscalPreview");
</script>';
?>
