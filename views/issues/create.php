<?php $cooperativeTypes = getCooperativeTypeOptions(); ?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=issues">แจ้งปัญหาการใช้งาน</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">แจ้งปัญหาใหม่</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-red-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">แจ้งปัญหาการใช้งานโปรแกรม</div>
      <div class="text-sm text-slate-600">กรอกรายละเอียดปัญหาที่พบเพื่อให้เจ้าหน้าที่ดำเนินการแก้ไข</div>
    </div>
  </div>

  <form method="POST" action="?page=issues&action=store" enctype="multipart/form-data" id="issueForm">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
      <div class="lg:col-span-8">

        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-building mr-2 text-[#1565c0]"></i>ข้อมูลสหกรณ์</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="cooperative_type_name" id="cooperativeTypeName" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <option value="">— เลือกประเภทสหกรณ์ —</option>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="cooperative_id" id="cooperativeId" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 disabled:bg-slate-100" required disabled>
                  <option value="">— กรุณาเลือกประเภทก่อน —</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-md mt-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-bug mr-2 text-red-600"></i>รายละเอียดปัญหา</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทปัญหา <span class="text-red-600">*</span></label>
                <select name="issue_type" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <option value="">— เลือกประเภทปัญหา —</option>
                  <?php foreach (getIssueTypeOptions() as $k => $v): ?>
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">โปรแกรมที่พบปัญหา <span class="text-red-600">*</span></label>
                <select name="program_name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <option value="">— เลือกโปรแกรม —</option>
                  <?php foreach (getProgramOptions() as $k => $v): ?>
                  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อเรื่อง <span class="text-red-600">*</span></label>
                <input type="text" name="title" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required maxlength="255"
                       placeholder="สรุปปัญหาโดยย่อ">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รายละเอียด <span class="text-red-600">*</span></label>
                <textarea name="detail" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="5" required
                          placeholder="อธิบายอาการของปัญหา ขั้นตอนที่ทำก่อนพบปัญหา ฯลฯ"></textarea>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ไฟล์แนบ <small class="text-slate-400 font-normal">(ถ้ามี เช่น ภาพหน้าจอ Error)</small></label>
                <div class="flex items-center gap-2">
                  <input type="file" name="attachment" id="attachmentInput"
                         class="flex-1 text-sm border border-slate-300 rounded-md px-2 py-1.5 file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs"
                         accept=".pdf,.jpg,.jpeg,.png"
                         onchange="showAttachmentSize(this)">
                  <span id="attachmentSize" class="text-xs flex-shrink-0 min-w-[70px]"></span>
                </div>
                <small class="text-slate-400">รับไฟล์ PDF หรือรูปภาพ (JPG, PNG) ขนาดไม่เกิน 10 MB</small>
              </div>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-200 mt-3">
              <button type="submit" class="<?php echo uiBtnClasses('danger'); ?>" id="submitBtn">
                <i class="fas fa-paper-plane mr-2"></i>ส่งแจ้งปัญหา
              </button>
              <a href="?page=issues" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
            </div>
          </div>
        </div>

      </div>

      <!-- Guide card -->
      <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-info-circle mr-2 text-sky-600"></i>ขั้นตอนการดำเนินการ</div>
          <div class="p-0">
            <div class="px-3.5 py-2.5 border-b border-slate-100 text-sm text-slate-500">
              เมื่อแจ้งปัญหาแล้ว เจ้าหน้าที่จะตรวจสอบและดำเนินการตามขั้นตอน
            </div>
            <div class="flex items-center gap-2 flex-wrap text-sm px-3.5 py-2.5 border-b border-slate-100">
              <span class="bg-amber-500 text-white text-xs rounded px-1.5 py-0.5">รอตรวจสอบ</span>
              <i class="fas fa-arrow-right text-slate-400"></i>
              <span class="bg-blue-600 text-white text-xs rounded px-1.5 py-0.5">กำลังดำเนินการ</span>
              <i class="fas fa-arrow-right text-slate-400"></i>
              <span class="bg-green-600 text-white text-xs rounded px-1.5 py-0.5">สำเร็จ</span>
            </div>
            <div class="px-3.5 py-2.5 text-sm text-slate-500">
              หากปัญหาต้องส่งต่อให้ส่วนกลางดำเนินการ จะมีสถานะ
              <span class="bg-purple-600 text-white text-xs rounded px-1.5 py-0.5 mx-1">ส่งส่วนกลาง</span>
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
    ? "<span class=\"text-red-600\">" + mb + " MB</span>"
    : "<span class=\"text-green-600\">" + mb + " MB</span>";
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
    confirmButtonColor: "#dc2626",
    confirmButtonText: "ส่งเลย",
    cancelButtonText: "ตรวจสอบอีกครั้ง"
  }).then(function(r) {
    if (r.isConfirmed) {
      document.getElementById("submitBtn").disabled = true;
      document.getElementById("submitBtn").innerHTML = "<i class=\"fas fa-spinner fa-spin mr-2\"></i>กำลังส่ง...";
      form.submit();
    }
  });
});
</script>';
?>
