<?php $cooperativeTypes = getCooperativeTypeOptions(); ?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=documents">รายการเอกสาร</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">นำส่งเอกสารใหม่</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-green-700 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-upload"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">นำส่งเอกสารใหม่</div>
      <div class="text-sm text-slate-600">กรอกข้อมูลและอัปโหลดไฟล์ PDF ให้ครบ 4 ไฟล์</div>
    </div>
  </div>

  <form method="POST" action="?page=documents&action=store" enctype="multipart/form-data" id="uploadForm">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
      <div class="lg:col-span-2">

        <!-- ข้อมูลสหกรณ์ -->
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
            <i class="fas fa-building mr-2 text-[#1565c0]"></i>ข้อมูลสหกรณ์
          </div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="cooperative_type_name" id="cooperativeTypeName" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                  <option value="">— เลือกประเภทสหกรณ์ —</option>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="cooperative_id" id="cooperativeId" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200 disabled:bg-slate-100" required disabled>
                  <option value="">— กรุณาเลือกประเภทก่อน —</option>
                </select>
              </div>
              <div class="md:col-span-2 md:w-5/12">
                <label class="block text-sm font-semibold text-slate-700 mb-1">เลขที่หนังสือ <span class="text-red-600">*</span></label>
                <input type="text" name="document_number" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200"
                       placeholder="เช่น กษ 04 ขก /663" required maxlength="100"
                       value="<?php echo isset($_POST['document_number']) ? e($_POST['document_number']) : ''; ?>">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ปีบัญชี (พ.ศ.) <span class="text-red-600">*</span></label>
                <select name="fiscal_year" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                  <?php for ($y = thaiYear(); $y >= thaiYear() - 5; $y--): ?>
                  <option value="<?php echo $y; ?>" <?php echo $y == thaiYear() ? 'selected' : ''; ?>><?php echo $y; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่นำส่ง <span class="text-red-600">*</span></label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="text" name="submitted_date" id="submittedDateInput"
                         class="flex-1 min-w-0 text-sm px-2 py-1.5 outline-none"
                         placeholder="วว/ดด/ปปปป เช่น 31/03/2569"
                         maxlength="10" required
                         value="<?php echo date('d/m/') . thaiYear(); ?>">
                  <span id="submittedDatePreview" class="bg-slate-50 border-l border-slate-300 px-2 flex items-center text-xs text-slate-500 min-w-[100px]">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ไฟล์เอกสาร -->
        <div class="bg-white border border-slate-200 rounded-md mt-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
            <i class="fas fa-file-pdf mr-2 text-red-600"></i>ไฟล์เอกสาร PDF (ไม่เกิน 10 MB / ไฟล์)
          </div>
          <div class="p-3.5">
            <?php
            $fileLabels = array(1=>'หนังสือนำส่ง',2=>'รายงานผู้สอบบัญชี',3=>'รายงานผลการตรวจสอบ',4=>'งบฐานะการเงิน');
            for ($i = 1; $i <= 4; $i++):
            ?>
            <div class="mb-3">
              <label class="block text-sm font-semibold text-slate-700 mb-1">
                <span class="bg-[#1565c0] text-white text-xs rounded px-1.5 py-0.5 mr-1"><?php echo $i; ?></span>
                <?php echo $fileLabels[$i]; ?> <span class="text-red-600">*</span>
              </label>
              <div class="flex items-center gap-2">
                <input type="file" name="file_doc<?php echo $i; ?>" id="file<?php echo $i; ?>"
                       class="flex-1 text-sm border border-slate-300 rounded-md px-2 py-1.5 file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs"
                       accept=".pdf" required
                       onchange="showSize(<?php echo $i; ?>, this)">
                <span id="size<?php echo $i; ?>" class="text-xs flex-shrink-0 min-w-[70px]"></span>
              </div>
            </div>
            <?php endfor; ?>

            <div class="flex gap-2 pt-2 border-t border-slate-200 mt-1">
              <button type="submit" class="<?php echo uiBtnClasses('success'); ?>" id="submitBtn">
                <i class="fas fa-paper-plane mr-2"></i>นำส่งเอกสาร
              </button>
              <a href="?page=documents" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
            </div>
          </div>
        </div>

      </div>

      <!-- Guide card -->
      <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
            <i class="fas fa-info-circle mr-2 text-sky-600"></i>ลำดับไฟล์ที่ต้องอัปโหลด
          </div>
          <div class="p-0">
            <?php foreach ($fileLabels as $n => $lbl): ?>
            <div class="flex items-center gap-3 px-3.5 py-2.5 border-b border-slate-100">
              <div class="w-8 h-8 rounded-full bg-[#1565c0] text-white flex items-center justify-center font-bold text-sm flex-shrink-0"><?php echo $n; ?></div>
              <span class="text-sm"><?php echo $lbl; ?></span>
            </div>
            <?php endforeach; ?>
            <div class="px-3.5 py-3">
              <div class="border border-amber-200 bg-amber-50 text-amber-700 rounded px-3 py-2 text-sm">
                <i class="fas fa-exclamation-triangle mr-1"></i>
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
    ? "<span class=\"text-red-600\">" + mb + " MB</span>"
    : "<span class=\"text-green-600\">" + mb + " MB</span>";
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
    confirmButtonColor: "#1565c0",
    confirmButtonText: "นำส่งเลย",
    cancelButtonText: "ตรวจสอบอีกครั้ง"
  }).then(function(r) {
    if (r.isConfirmed) {
      document.getElementById("submitBtn").disabled = true;
      document.getElementById("submitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin mr-2\"></i>กำลังอัปโหลด...";
      form.submit();
    }
  });
});

// Date input
setupDateInput("submittedDateInput", "submittedDatePreview", true);
</script>';
?>
