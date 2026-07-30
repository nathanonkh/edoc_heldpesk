<?php $cooperativeTypes = getCooperativeTypeOptions(); ?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=documents">รายการเอกสาร</a></li>
    <li class="breadcrumb-item active">นำส่งเอกสารใหม่</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-success"><i class="fas fa-upload"></i></div>
    <div>
      <div class="page-banner-title">นำส่งเอกสารใหม่</div>
      <div class="page-banner-sub">กรอกข้อมูลและอัปโหลดไฟล์ PDF ให้ครบ 4 ไฟล์</div>
    </div>
  </div>

  <form method="POST" action="?page=documents&action=store" enctype="multipart/form-data" id="uploadForm">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="row g-3">
      <div class="col-12 col-lg-8">

        <!-- ข้อมูลสหกรณ์ -->
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-building me-2 text-primary"></i>ข้อมูลสหกรณ์</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">ประเภทสหกรณ์ <span class="text-danger">*</span></label>
                <select name="cooperative_type_name" id="cooperativeTypeName" class="form-select form-select-sm" required>
                  <option value="">— เลือกประเภทสหกรณ์ —</option>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">ชื่อสหกรณ์ <span class="text-danger">*</span></label>
                <select name="cooperative_id" id="cooperativeId" class="form-select form-select-sm" required disabled>
                  <option value="">— กรุณาเลือกประเภทก่อน —</option>
                </select>
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label">เลขที่หนังสือ <span class="text-danger">*</span></label>
                <input type="text" name="document_number" class="form-control form-control-sm"
                       placeholder="เช่น กษ 04 ขก /663" required maxlength="100"
                       value="<?php echo isset($_POST['document_number']) ? e($_POST['document_number']) : ''; ?>">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">ปีบัญชี (พ.ศ.) <span class="text-danger">*</span></label>
                <select name="fiscal_year" class="form-select form-select-sm" required>
                  <?php for ($y = thaiYear(); $y >= thaiYear() - 5; $y--): ?>
                  <option value="<?php echo $y; ?>" <?php echo $y == thaiYear() ? 'selected' : ''; ?>><?php echo $y; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="col-6 col-md-4">
                <label class="form-label">วันที่นำส่ง <span class="text-danger">*</span></label>
                <div class="input-group input-group-sm">
                  <input type="text" name="submitted_date" id="submittedDateInput"
                         class="form-control form-control-sm"
                         placeholder="วว/ดด/ปปปป เช่น 31/03/2569"
                         maxlength="10" required
                         value="<?php echo date('d/m/') . thaiYear(); ?>">
                  <span class="input-group-text" id="submittedDatePreview" style="font-size:0.76rem;min-width:100px;color:#555;">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ไฟล์เอกสาร -->
        <div class="page-card mt-3">
          <div class="page-card-header"><i class="fas fa-file-pdf me-2 text-danger"></i>ไฟล์เอกสาร PDF (ไม่เกิน 10 MB / ไฟล์)</div>
          <div class="page-card-body">
            <?php
            $fileLabels = array(1=>'หนังสือนำส่ง',2=>'รายงานผู้สอบบัญชี',3=>'รายงานผลการตรวจสอบ',4=>'งบฐานะการเงิน');
            for ($i = 1; $i <= 4; $i++):
            ?>
            <div class="mb-3">
              <label class="form-label">
                <span class="badge bg-primary me-1"><?php echo $i; ?></span>
                <?php echo $fileLabels[$i]; ?> <span class="text-danger">*</span>
              </label>
              <div class="d-flex align-items-center gap-2">
                <input type="file" name="file_doc<?php echo $i; ?>" id="file<?php echo $i; ?>"
                       class="form-control form-control-sm" accept=".pdf" required
                       onchange="showSize(<?php echo $i; ?>, this)">
                <span id="size<?php echo $i; ?>" class="small flex-shrink-0" style="min-width:70px;"></span>
              </div>
            </div>
            <?php endfor; ?>

            <div class="d-flex gap-2 pt-2 border-top mt-1">
              <button type="submit" class="btn btn-success btn-sm" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>นำส่งเอกสาร
              </button>
              <a href="?page=documents" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
            </div>
          </div>
        </div>

      </div>

      <!-- Guide card -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-info-circle me-2 text-info"></i>ลำดับไฟล์ที่ต้องอัปโหลด</div>
          <div class="page-card-body p-0">
            <?php foreach ($fileLabels as $n => $lbl): ?>
            <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                   style="width:30px;height:30px;font-size:0.85rem;"><?php echo $n; ?></div>
              <span style="font-size:0.88rem;"><?php echo $lbl; ?></span>
            </div>
            <?php endforeach; ?>
            <div class="px-3 py-2">
              <div class="alert alert-warning py-2 mb-0 small">
                <i class="fas fa-exclamation-triangle me-1"></i>
                รับเฉพาะ <strong>PDF</strong> ขนาดไม่เกิน <strong>10 MB</strong>
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
// โหลดรายชื่อสหกรณ์ตามประเภท
document.getElementById("cooperativeTypeName").addEventListener("change", function() {
  var sel = document.getElementById("cooperativeId");
  sel.disabled = true;
  sel.innerHTML = "<option>กำลังโหลด...</option>";
  if (!this.value) {
    sel.innerHTML = "<option value=\"\">-- กรุณาเลือกประเภทก่อน --</option>";
    return;
  }
  ajaxGet("?page=documents&action=ajax_cooperatives&type_name=" + encodeURIComponent(this.value), function(ok, text) {
    if (!ok) { sel.innerHTML = "<option value=\"\">-- เกิดข้อผิดพลาด --</option>"; return; }
    try {
      var data = JSON.parse(text);
      sel.innerHTML = "<option value=\"\">-- เลือกสหกรณ์ --</option>";
      for (var i = 0; i < data.length; i++) {
        sel.innerHTML += "<option value=\"" + data[i].id + "\">" + data[i].name + " (" + data[i].code + ")</option>";
      }
      sel.disabled = false;
      if (!data.length) sel.innerHTML = "<option value=\"\">-- ไม่พบสหกรณ์ --</option>";
    } catch(e) {
      sel.innerHTML = "<option value=\"\">-- เกิดข้อผิดพลาด --</option>";
    }
  });
});

// แสดงขนาดไฟล์
function showSize(n, input) {
  var el = document.getElementById("size" + n);
  if (!input.files[0]) { el.innerHTML = ""; return; }
  var mb = (input.files[0].size / 1024 / 1024).toFixed(2);
  el.innerHTML = mb > 10
    ? "<span class=\"text-danger\">" + mb + " MB</span>"
    : "<span class=\"text-success\">" + mb + " MB</span>";
}

// Submit confirm
document.getElementById("uploadForm").addEventListener("submit", function(e) {
  e.preventDefault();
  var form = this;
  Swal.fire({
    title: "ยืนยันการนำส่งเอกสาร?",
    text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนนำส่ง",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "นำส่งเลย",
    cancelButtonText: "ตรวจสอบอีกครั้ง"
  }).then(function(r) {
    if (r.isConfirmed) {
      document.getElementById("submitBtn").disabled = true;
      document.getElementById("submitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin me-2\"></i>กำลังอัปโหลด...";
      form.submit();
    }
  });
});

// Date input
setupDateInput("submittedDateInput", "submittedDatePreview", true);
</script>';
?>
