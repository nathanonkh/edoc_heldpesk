<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">รายการเอกสาร</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-[#1565c0] text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-folder-open"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">รายการเอกสาร</div>
      <div class="text-sm text-slate-600">ทั้งหมด <span id="docTotalCount"><?php echo $totalItems; ?></span> รายการ</div>
    </div>
    <?php if (Auth::hasAnyRole(array('submitter','admin'))): ?>
    <a href="?page=documents&action=create" class="<?php echo uiBtnClasses('success'); ?> flex-shrink-0">
      <i class="fas fa-plus mr-1"></i>นำส่งเอกสาร
    </a>
    <?php endif; ?>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="documents">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm">
        <i class="fas fa-filter mr-2 text-slate-500"></i>ตัวกรอง
      </div>
      <div class="p-3.5">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 items-end">
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ</label>
            <select name="status" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200">
              <option value="">ทั้งหมด</option>
              <option value="pending"    <?php echo $filters['status']==='pending'    ?'selected':''; ?>>รอตรวจสอบ</option>
              <option value="inspecting" <?php echo $filters['status']==='inspecting' ?'selected':''; ?>>กำลังตรวจสอบ</option>
              <option value="approving"  <?php echo $filters['status']==='approving'  ?'selected':''; ?>>รออนุมัติ</option>
              <option value="operating"  <?php echo $filters['status']==='operating'  ?'selected':''; ?>>รอดำเนินการ</option>
              <option value="revision"   <?php echo $filters['status']==='revision'   ?'selected':''; ?>>ส่งกลับแก้ไข</option>
              <option value="completed"  <?php echo $filters['status']==='completed'  ?'selected':''; ?>>เสร็จสิ้น</option>
            </select>
          </div>
          <div class="col-span-2 md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">ค้นหา</label>
            <input type="text" name="keyword" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200"
                   placeholder="ชื่อสหกรณ์ หรือเลขที่เอกสาร"
                   value="<?php echo e($filters['keyword']); ?>">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่เริ่มต้น</label>
            <input type="date" name="date_from" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200"
                   value="<?php echo e($filters['date_from']); ?>">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่สิ้นสุด</label>
            <input type="date" name="date_to" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-200"
                   value="<?php echo e($filters['date_to']); ?>">
          </div>
          <div>
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetDocFilter()">
              <i class="fas fa-undo mr-1"></i>รีเซ็ต
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <?php if (Auth::hasRole('approver')): ?>
  <form method="POST" action="?page=documents&action=bulk_approve" id="bulkApproveForm">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
  </form>
  <?php endif; ?>

  <div id="docListContainer">
    <?php include 'views/documents/_list_partial.php'; ?>
  </div>

</main>

<?php
$extraJs = '<script>
var docLoading = false;

function loadDocList(page) {
  if (docLoading) return;
  docLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:      "documents",
    action:    "ajax_list",
    status:    form.status.value,
    keyword:   form.keyword.value,
    date_from: form.date_from.value,
    date_to:   form.date_to.value,
    p:         page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("docListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    docLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
      return;
    }
    container.innerHTML = text;
    bindDocListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetDocFilter() {
  var form = document.getElementById("filterForm");
  form.status.value = "";
  form.keyword.value = "";
  form.date_from.value = "";
  form.date_to.value = "";
  loadDocList(1);
}

function bindDocListEvents() {
  var pagLinks = document.querySelectorAll("#docListContainer nav a");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadDocList(page);
    });
  }

  var selectAll = document.getElementById("select-all");
  if (selectAll) {
    selectAll.addEventListener("change", function() {
      var cbs = document.querySelectorAll(".doc-checkbox");
      for (var i = 0; i < cbs.length; i++) cbs[i].checked = this.checked;
    });
  }
}

function submitBulkApprove() {
  var checked = document.querySelectorAll(".doc-checkbox:checked");
  if (checked.length === 0) {
    showToast("warning", "กรุณาเลือกอย่างน้อย 1 รายการ");
    return;
  }
  Swal.fire({title:"อนุมัติ "+checked.length+" รายการ?",icon:"question",showCancelButton:true,confirmButtonColor:"#1565c0",confirmButtonText:"ยืนยัน",cancelButtonText:"ยกเลิก"})
  .then(function(r) {
    if (r.isConfirmed) {
      var bulkForm = document.getElementById("bulkApproveForm");
      var existing = bulkForm.querySelectorAll("input[name=\\"doc_ids[]\\"]");
      for (var i = 0; i < existing.length; i++) existing[i].remove();
      for (var i = 0; i < checked.length; i++) {
        var inp = document.createElement("input");
        inp.type = "hidden";
        inp.name = "doc_ids[]";
        inp.value = checked[i].value;
        bulkForm.appendChild(inp);
      }
      bulkForm.submit();
    }
  });
}

document.getElementById("filterForm").addEventListener("keypress", function(e) {
  if (e.key === "Enter" || e.keyCode === 13) e.preventDefault();
});

// Auto-search: select และ date ยิงทันทีตอน change
var autoFields = document.querySelectorAll("#filterForm select.filter-input, #filterForm input[type=date].filter-input");
for (var i = 0; i < autoFields.length; i++) {
  autoFields[i].addEventListener("change", function() { loadDocList(1); });
}

// Auto-search: keyword ใช้ debounce
var docKeywordTimer;
var docKeywordInput = document.querySelector("#filterForm input[name=keyword]");
if (docKeywordInput) {
  docKeywordInput.addEventListener("input", function() {
    clearTimeout(docKeywordTimer);
    docKeywordTimer = setTimeout(function() { loadDocList(1); }, 600);
  });
}

bindDocListEvents();
</script>';
?>
