<?php $officeOptions = getOfficeOptions(); ?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">รายงานเอกสาร</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-info"><i class="fas fa-chart-bar"></i></div>
    <div>
      <div class="page-banner-title">รายงานเอกสาร</div>
      <div class="page-banner-sub">สรุปสถิติการนำส่งเอกสารทั้งหมด</div>
    </div>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="reports">
    <div class="page-card mb-3">
      <div class="page-card-header"><i class="fas fa-filter me-2 text-secondary"></i>ตัวกรองรายงาน</div>
      <div class="page-card-body">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">ปีบัญชี</label>
            <select name="fiscal_year" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <?php for ($y = thaiYear(); $y >= thaiYear() - 5; $y--): ?>
              <option value="<?php echo $y; ?>" <?php echo $filters['fiscal_year'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
              <?php endfor; ?>
            </select>
          </div>
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
          <?php if (Auth::hasRole('admin')): ?>
          <div class="col-12 col-sm-8 col-md-4">
            <label class="form-label">สำนักงาน</label>
            <select name="office_name" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <?php foreach ($officeOptions as $off): ?>
              <option value="<?php echo e($off); ?>" <?php echo $filters['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">วันที่เริ่มต้น</label>
            <input type="date" name="date_from" class="form-control form-control-sm filter-input" value="<?php echo e($filters['date_from']); ?>">
          </div>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">วันที่สิ้นสุด</label>
            <input type="date" name="date_to" class="form-control form-control-sm filter-input" value="<?php echo e($filters['date_to']); ?>">
          </div>
          <div class="col-12 col-sm-4 col-md-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetReportFilter()">
              <i class="fas fa-undo me-1"></i>รีเซ็ต
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="reportListContainer">
    <?php include 'views/reports/_list_partial.php'; ?>
  </div>

</main>

<?php
$hasOffice = Auth::hasRole('admin') ? 'true' : 'false';
$extraJs = '<script>
var reportLoading = false;
var reportHasOfficeFilter = ' . $hasOffice . ';

function loadReportList(page) {
  if (reportLoading) return;
  reportLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:        "reports",
    action:      "ajax_list",
    fiscal_year: form.fiscal_year.value,
    status:      form.status.value,
    office_name: reportHasOfficeFilter && form.office_name ? form.office_name.value : "",
    date_from:   form.date_from.value,
    date_to:     form.date_to.value,
    p: page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("reportListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    reportLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
      return;
    }
    container.innerHTML = text;
    bindReportListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetReportFilter() {
  var form = document.getElementById("filterForm");
  form.fiscal_year.value = "";
  form.status.value = "";
  if (form.office_name) form.office_name.value = "";
  form.date_from.value = "";
  form.date_to.value = "";
  loadReportList(1);
}

function bindReportListEvents() {
  var pagLinks = document.querySelectorAll("#reportListContainer .pagination a.page-link");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadReportList(page);
    });
  }
}

function exportCsv() {
  var rows = document.querySelectorAll("#reportTable tr"), csv = [];
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].querySelectorAll("th,td"), row = [];
    for (var j = 0; j < cells.length; j++) row.push(cells[j].innerText.replace(/,/g, " "));
    csv.push(row.join(","));
  }
  var blob = new Blob(["\uFEFF" + csv.join("\n")], {type:"text/csv;charset=utf-8;"});
  var a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = "report_edoc_" + new Date().toISOString().slice(0, 10) + ".csv";
  a.click();
}

// Auto-search: select และ date ยิงทันทีตอน change
var reportAutoFields = document.querySelectorAll("#filterForm select.filter-input, #filterForm input[type=date].filter-input");
for (var i = 0; i < reportAutoFields.length; i++) {
  reportAutoFields[i].addEventListener("change", function() { loadReportList(1); });
}

bindReportListEvents();
</script>';
?>
