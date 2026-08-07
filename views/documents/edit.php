<?php
// $doc = array เอกสารที่ต้องการแก้ไข (status = revision)
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=documents">รายการเอกสาร</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">แก้ไขเอกสาร</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
<div class="lg:col-span-9">

  <div class="border border-red-200 bg-red-50 text-red-700 rounded-md flex gap-2 items-start mb-3 px-3.5 py-2.5">
    <i class="fas fa-exclamation-triangle mt-1"></i>
    <div>
      <strong>เอกสารถูกส่งกลับให้แก้ไข:</strong> <?php echo e($doc['revision_note']); ?>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-md">
    <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
      <i class="fas fa-edit mr-1"></i> แก้ไขเอกสาร: <?php echo e($doc['ticket_code']); ?>
    </div>
    <div class="p-3.5">

      <!-- ข้อมูลสหกรณ์ (อ่านอย่างเดียว) -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-3">
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-slate-500 mb-1">สหกรณ์</label>
          <input type="text" class="w-full text-sm border border-slate-200 bg-slate-50 rounded-md px-2 py-1.5" readonly
                 value="<?php echo e($doc['cooperative_name']); ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">ปีบัญชี</label>
          <input type="text" class="w-full text-sm border border-slate-200 bg-slate-50 rounded-md px-2 py-1.5" readonly value="<?php echo e($doc['fiscal_year']); ?>">
        </div>
      </div>

      <hr class="my-3 border-slate-200">

      <form method="POST" action="?page=documents&action=resubmit" enctype="multipart/form-data" id="resubmitForm">
        <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
        <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">

        <h6 class="font-bold text-[#1565c0] mb-3"><i class="fas fa-file-pdf mr-1"></i> อัปโหลดไฟล์ใหม่ (เฉพาะไฟล์ที่ต้องการเปลี่ยน)</h6>

        <?php for ($i = 1; $i <= 4; $i++):
          $nameField = 'file_doc' . $i . '_name';
        ?>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-3 items-center">
          <div class="md:col-span-3">
            <label class="text-sm font-semibold text-slate-700"><?php echo docFileLabel($i); ?></label>
          </div>
          <div class="md:col-span-5">
            <input type="file" name="file_doc<?php echo $i; ?>" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" accept=".pdf">
          </div>
          <div class="md:col-span-4">
            <small class="text-slate-500 text-xs"><i class="fas fa-file-pdf text-red-600 mr-1"></i>ปัจจุบัน: <?php echo e($doc[$nameField]); ?></small>
          </div>
        </div>
        <?php endfor; ?>

        <p class="text-slate-500 text-sm"><i class="fas fa-info-circle mr-1"></i>หากไม่เลือกไฟล์ใหม่ ระบบจะใช้ไฟล์เดิม</p>

        <div class="flex gap-2">
          <button type="submit" class="<?php echo uiBtnClasses('success'); ?>" id="resubmitBtn">
            <i class="fas fa-paper-plane mr-1"></i> ส่งเอกสารใหม่
          </button>
          <a href="?page=documents" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
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
    confirmButtonColor: "#1565c0",
    confirmButtonText: "ยืนยัน",
    cancelButtonText: "ยกเลิก"
  }).then(function(result) {
    if (result.isConfirmed) {
      document.getElementById("resubmitBtn").disabled = true;
      document.getElementById("resubmitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin mr-1\"></i> กำลังส่ง...";
      form.submit();
    }
  });
});
</script>';
?>
