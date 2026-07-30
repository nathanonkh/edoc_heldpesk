<?php $cooperativeTypes = getCooperativeTypeOptions(); ?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=issues">แจ้งปัญหาการใช้งาน</a></li>
    <li class="breadcrumb-item active">แจ้งปัญหาใหม่</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-danger"><i class="fas fa-exclamation-circle"></i></div>
    <div>
      <div class="page-banner-title">แจ้งปัญหาการใช้งานโปรแกรม</div>
      <div class="page-banner-sub">กรอกรายละเอียดปัญหาที่พบเพื่อให้เจ้าหน้าที่ดำเนินการแก้ไข</div>
    </div>
  </div>

  <form method="POST" action="?page=issues&action=store" enctype="multipart/form-data" id="issueForm">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="row g-3">
      <div class="col-12 col-lg-8">

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
            </div>
          </div>
        </div>

        <div class="page-card mt-3">
          <div class="page-card-header"><i class="fas fa-bug me-2 text-danger"></i>รายละเอียดปัญหา</div>
          <div class="page-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">ประเภทปัญหา <span class="text-danger">*</span></label>
                <select name="issue_type" class="form-select form-select-sm" required>
                  <option value="">— เลือกประเภทปัญหา —</option>
                  <?php foreach (getIssueTypeOptions() as $k => $v): ?>
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">โปรแกรมที่พบปัญหา <span class="text-danger">*</span></label>
                <select name="program_name" class="form-select form-select-sm" required>
                  <option value="">— เลือกโปรแกรม —</option>
                  <?php foreach (getProgramOptions() as $k => $v): ?>
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">ชื่อเรื่อง <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-sm" required maxlength="255"
                       placeholder="สรุปปัญหาโดยย่อ">
              </div>
              <div class="col-12">
                <label class="form-label">รายละเอียด <span class="text-danger">*</span></label>
                <textarea name="detail" class="form-control form-control-sm" rows="5" required
                          placeholder="อธิบายอาการของปัญหา ขั้นตอนที่ทำก่อนพบปัญหา ฯลฯ"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">ไฟล์แนบ <small class="text-muted">(ถ้ามี เช่น ภาพหน้าจอ Error)</small></label>
                <div class="d-flex align-items-center gap-2">
                  <input type="file" name="attachment" id="attachmentInput"
                         class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png"
                         onchange="showAttachmentSize(this)">
                  <span id="attachmentSize" class="small flex-shrink-0" style="min-width:70px;"></span>
                </div>
                <small class="text-muted">รับไฟล์ PDF หรือรูปภาพ (JPG, PNG) ขนาดไม่เกิน 10 MB</small>
              </div>
            </div>

            <div class="d-flex gap-2 pt-3 border-top mt-3">
              <button type="submit" class="btn btn-danger btn-sm" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>ส่งแจ้งปัญหา
              </button>
              <a href="?page=issues" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
            </div>
          </div>
        </div>

      </div>

      <!-- Guide card -->
      <div class="col-12 col-lg-4">
        <div class="page-card">
          <div class="page-card-header"><i class="fas fa-info-circle me-2 text-info"></i>ขั้นตอนการดำเนินการ</div>
          <div class="page-card-body p-0">
            <div class="px-3 py-2 border-bottom small text-muted">
              เมื่อแจ้งปัญหาแล้ว เจ้าหน้าที่จะตรวจสอบและดำเนินการตามขั้นตอน
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap small px-3 py-2 border-bottom">
              <span class="badge bg-warning text-dark">รอตรวจสอบ</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge bg-primary">กำลังดำเนินการ</span>
              <i class="fas fa-arrow-right text-muted"></i>
              <span class="badge bg-success">สำเร็จ</span>
            </div>
            <div class="px-3 py-2 small text-muted">
              หากปัญหาต้องส่งต่อให้ส่วนกลางดำเนินการ จะมีสถานะ
              <span class="badge badge-purple text-white mx-1">ส่งส่วนกลาง</span>
              คั่นก่อนเข้าสู่ขั้น "กำลังดำเนินการ"
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<?php
$extraJs = '<script>
function showAttachmentSize(input) {
  var el = document.getElementById("attachmentSize");
  if (!input.files[0]) { el.innerHTML = ""; return; }
  var mb = (input.files[0].size / 1024 / 1024).toFixed(2);
  el.innerHTML = mb > 10
    ? "<span class=\"text-danger\">" + mb + " MB</span>"
    : "<span class=\"text-success\">" + mb + " MB</span>";
}

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

document.getElementById("issueForm").addEventListener("submit", function(e) {
  e.preventDefault();
  var form = this;
  Swal.fire({
    title: "ยืนยันการแจ้งปัญหา?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "ส่งเลย",
    cancelButtonText: "ตรวจสอบอีกครั้ง"
  }).then(function(r) {
    if (r.isConfirmed) {
      document.getElementById("submitBtn").disabled = true;
      document.getElementById("submitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin me-2\"></i>กำลังส่ง...";
      form.submit();
    }
  });
});
</script>';
?>
