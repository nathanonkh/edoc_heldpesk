<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">รายการเอกสาร</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon"><i class="fas fa-folder-open"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">รายการเอกสาร</div>
      <div class="page-banner-sub">ทั้งหมด <span id="docTotalCount"><?php echo $totalItems; ?></span> รายการ</div>
    </div>
    <?php if (Auth::hasAnyRole(array('submitter','admin'))): ?>
    <a href="?page=documents&action=create" class="btn btn-success btn-sm flex-shrink-0">
      <i class="fas fa-plus me-1"></i>นำส่งเอกสาร
    </a>
    <?php endif; ?>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="documents">
    <div class="page-card mb-3">
      <div class="page-card-header"><i class="fas fa-filter me-2 text-secondary"></i>ตัวกรอง</div>
      <div class="page-card-body">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <option value="pending"    <?php echo $filters['status']==='pending'    ?'selected':''; ?>>รอตรวจสอบ</option>
              <option value="inspecting" <?php echo $filters['status']==='inspecting' ?'selected':''; ?>>กำลังตรวจสอบ</option>
              <option value="approving"  <?php echo $filters['status']==='approving'  ?'selected':''; ?>>รออนุมัติ</option>
              <option value="operating"  <?php echo $filters['status']==='operating'  ?'selected':''; ?>>รอดำเนินการ</option>
              <option value="revision"   <?php echo $filters['status']==='revision'   ?'selected':''; ?>>ส่งกลับแก้ไข</option>
              <option value="completed"  <?php echo $filters['status']==='completed'  ?'selected':''; ?>>เสร็จสิ้น</option>
            </select>
          </div>
          <div class="col-12 col-sm-8 col-md-3">
            <label class="form-label">ค้นหา</label>
            <input type="text" name="keyword" class="form-control form-control-sm filter-input"
                   placeholder="ชื่อสหกรณ์ หรือเลขที่เอกสาร"
                   value="<?php echo e($filters['keyword']); ?>">
          </div>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">วันที่เริ่มต้น</label>
            <input type="date" name="date_from" class="form-control form-control-sm filter-input"
                   value="<?php echo e($filters['date_from']); ?>">
          </div>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">วันที่สิ้นสุด</label>
            <input type="date" name="date_to" class="form-control form-control-sm filter-input"
                   value="<?php echo e($filters['date_to']); ?>">
          </div>
          <div class="col-12 col-sm-4 col-md-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetDocFilter()">
              <i class="fas fa-undo me-1"></i>รีเซ็ต
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
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
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
  var pagLinks = document.querySelectorAll("#docListContainer .pagination a.page-link");
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
    Swal.fire({icon:"warning",title:"กรุณาเลือกอย่างน้อย 1 รายการ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
    return;
  }
  Swal.fire({title:"อนุมัติ "+checked.length+" รายการ?",icon:"question",showCancelButton:true,confirmButtonText:"ยืนยัน",cancelButtonText:"ยกเลิก"})
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
