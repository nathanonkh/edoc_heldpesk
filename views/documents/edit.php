<?php
// $doc = array เอกสารที่ต้องการแก้ไข (status = revision)
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="?page=documents">รายการเอกสาร</a></li>
    <li class="breadcrumb-item active">แก้ไขเอกสาร</li>
  </ol></nav>
</div>

<main class="content-area">
<div class="row">
<div class="col-12 col-lg-9">

  <div class="alert alert-danger d-flex gap-2 align-items-start mb-3 py-2">
    <i class="fas fa-exclamation-triangle mt-1"></i>
    <div>
      <strong>เอกสารถูกส่งกลับให้แก้ไข:</strong> <?php echo e($doc['revision_note']); ?>
    </div>
  </div>

  <div class="page-card">
    <div class="page-card-header"><i class="fas fa-edit me-1"></i> แก้ไขเอกสาร: <?php echo e($doc['ticket_code']); ?></div>
    <div class="page-card-body">

      <!-- ข้อมูลสหกรณ์ (อ่านอย่างเดียว) -->
      <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
          <label class="form-label small fw-semibold">สหกรณ์</label>
          <input type="text" class="form-control form-control-sm" readonly
                 value="<?php echo e($doc['cooperative_name']); ?>">
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label small fw-semibold">ปีบัญชี</label>
          <input type="text" class="form-control form-control-sm" readonly value="<?php echo e($doc['fiscal_year']); ?>">
        </div>
      </div>

      <hr class="my-3">

      <form method="POST" action="?page=documents&action=resubmit" enctype="multipart/form-data" id="resubmitForm">
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
        <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">

        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-pdf me-1"></i> อัปโหลดไฟล์ใหม่ (เฉพาะไฟล์ที่ต้องการเปลี่ยน)</h6>

        <?php for ($i = 1; $i <= 4; $i++):
          $nameField = 'file_doc' . $i . '_name';
        ?>
        <div class="row g-2 mb-3 align-items-center">
          <div class="col-auto" style="width:200px;">
            <label class="form-label small fw-semibold mb-0"><?php echo docFileLabel($i); ?></label>
          </div>
          <div class="col">
            <input type="file" name="file_doc<?php echo $i; ?>" class="form-control form-control-sm" accept=".pdf">
          </div>
          <div class="col-12 col-md-auto ps-4 ps-md-0">
            <small class="text-muted"><i class="fas fa-file-pdf text-danger me-1"></i>ปัจจุบัน: <?php echo e($doc[$nameField]); ?></small>
          </div>
        </div>
        <?php endfor; ?>

        <p class="small text-muted"><i class="fas fa-info-circle me-1"></i>หากไม่เลือกไฟล์ใหม่ ระบบจะใช้ไฟล์เดิม</p>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success btn-sm" id="resubmitBtn">
            <i class="fas fa-paper-plane me-1"></i> ส่งเอกสารใหม่
          </button>
          <a href="?page=documents" class="btn btn-outline-secondary btn-sm">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>

</div>
</div>
</main>

<?php
$extraJs = '<script>
document.getElementById("resubmitForm").addEventListener("submit", function(e) {
  e.preventDefault();
  var form = this;
  Swal.fire({
    title: "ยืนยันการส่งเอกสารใหม่?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "ยืนยัน",
    cancelButtonText: "ยกเลิก"
  }).then(function(result) {
    if (result.isConfirmed) {
      document.getElementById("resubmitBtn").disabled = true;
      document.getElementById("resubmitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin me-1\"></i> กำลังส่ง...";
      form.submit();
    }
  });
});
</script>';
?>
