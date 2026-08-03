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
    <li class="breadcrumb-item active">เพิ่มสหกรณ์</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-success"><i class="fas fa-plus"></i></div>
    <div>
      <div class="page-banner-title">เพิ่มสหกรณ์ใหม่</div>
      <div class="page-banner-sub">กรอกข้อมูลให้ครบถ้วนแล้วกดบันทึก</div>
    </div>
  </div>

  <form method="POST" action="?page=cooperatives&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="row g-3">
      <div class="col-12 col-lg-8">

        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-building me-2 text-primary"></i>ข้อมูลพื้นฐาน</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">รหัสสหกรณ์ <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control form-control-sm" required placeholder="เช่น KKN001">
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">ชื่อสหกรณ์ <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form-control-sm" required placeholder="ชื่อเต็มสหกรณ์">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">เลขทะเบียนสหกรณ์</label>
                <input type="text" name="registration_no" class="form-control form-control-sm" placeholder="เช่น 0001/2530">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">รหัส 13 หลัก</label>
                <input type="text" name="regis_13digit" class="form-control form-control-sm" maxlength="13" placeholder="13 หลัก">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">วันที่จดทะเบียน</label>
                <div class="input-group input-group-sm">
                  <input type="text" name="register_date" id="registerDateInput"
                         class="form-control form-control-sm" placeholder="วว/ดด/ปปปป เช่น 31/03/2569" maxlength="10" value="">
                  <span class="input-group-text" id="registerDatePreview" style="font-size:0.76rem;min-width:100px;color:#555;">-</span>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">ประเภทสหกรณ์ <span class="text-danger">*</span></label>
                <select name="type_name" class="form-select form-select-sm" required>
                  <option value="">— เลือกประเภท —</option>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-3">
                <label class="form-label">วันสิ้นปีบัญชี</label>
                <div class="input-group input-group-sm">
                  <input type="text" name="fiscal_year" id="fiscalYearInput"
                         class="form-control form-control-sm" placeholder="วว/ดด เช่น 31/03" maxlength="5" value="">
                  <span class="input-group-text" id="fiscalPreview" style="font-size:0.78rem;min-width:90px;color:#555;">-</span>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <label class="form-label">สถานะ <span class="text-danger">*</span></label>
                <select name="status" class="form-select form-select-sm" required>
                  <?php foreach ($statusOptions as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $k==='active' ? 'selected' : ''; ?>><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-map-marker-alt me-2 text-danger"></i>ที่ตั้ง</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">ที่อยู่</label>
                <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="บ้านเลขที่ ถนน หมู่บ้าน"></textarea>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">ตำบล</label>
                <input type="text" name="subdistrict" class="form-control form-control-sm">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">อำเภอ</label>
                <input type="text" name="district" class="form-control form-control-sm">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">จังหวัด</label>
                <select name="province" class="form-select form-select-sm">
                  <option value="">— เลือกจังหวัด —</option>
                  <?php foreach ($provinces as $p): ?>
                  <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="page-card mb-3">
          <div class="page-card-header"><i class="fas fa-landmark me-2 text-warning"></i>สำนักงานที่รับผิดชอบ</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">สำนักงานตรวจบัญชีสหกรณ์ <span class="text-danger">*</span></label>
                <select name="office_name" class="form-select form-select-sm" required>
                  <option value="">— เลือกสำนักงาน —</option>
                  <?php foreach ($officeOptions as $off): ?>
                  <option value="<?php echo e($off); ?>"><?php echo e($off); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save me-1"></i>บันทึกสหกรณ์</button>
          <a href="?page=cooperatives" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>

      </div>

      <!-- ขวา: คำแนะนำ -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-info-circle me-2 text-info"></i>คำแนะนำ</div>
          <div class="page-card-body p-0">
            <?php
            $tips = array(
                array('icon'=>'fas fa-code text-primary',          'text'=>'<strong>รหัสสหกรณ์</strong> ต้องไม่ซ้ำ ใช้รูปแบบ จ.ลำดับ เช่น KKN001'),
                array('icon'=>'fas fa-building text-warning',      'text'=>'<strong>ประเภทสหกรณ์</strong> ต้องเลือกให้ตรงกับทะเบียนจดตั้ง'),
                array('icon'=>'fas fa-landmark text-info',         'text'=>'<strong>สำนักงาน</strong> กำหนดขอบเขตว่าใครจะเห็นและส่งเอกสารให้สหกรณ์นี้ได้'),
                array('icon'=>'fas fa-toggle-on text-success',     'text'=>'<strong>สถานะ "ปกติ"</strong> เท่านั้นที่จะปรากฏในรายการนำส่งเอกสาร'),
                array('icon'=>'fas fa-map-marker-alt text-danger', 'text'=>'ข้อมูลที่ตั้งช่วยในการอ้างอิงและรายงาน ไม่บังคับกรอก'),
            );
            foreach ($tips as $tip):
            ?>
            <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom">
              <i class="<?php echo $tip['icon']; ?> flex-shrink-0 mt-1"></i>
              <span style="font-size:0.82rem;color:#555;"><?php echo $tip['text']; ?></span>
            </div>
            <?php endforeach; ?>
            <div class="px-3 py-2">
              <div class="alert alert-light border mb-0 py-2 small">
                <i class="fas fa-asterisk text-danger me-1"></i>
                ช่องที่มีเครื่องหมาย <strong class="text-danger">*</strong> จำเป็นต้องกรอก
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </form>

</main>

<?php
$extraJs = '<script>
setupDateInput("registerDateInput", "registerDatePreview", true);
setupDateInput("fiscalYearInput", "fiscalPreview", false);
</script>';
?>