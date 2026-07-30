<?php $officeOptions = getOfficeOptions(); ?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">รายงานแจ้งปัญหา</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-danger"><i class="fas fa-chart-bar"></i></div>
    <div>
      <div class="page-banner-title">รายงานแจ้งปัญหา</div>
      <div class="page-banner-sub">สรุปสถิติการแจ้งปัญหาการใช้งานโปรแกรมทั้งหมด</div>
    </div>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="issue_reports">
    <div class="page-card mb-3">
      <div class="page-card-header"><i class="fas fa-filter me-2 text-secondary"></i>ตัวกรองรายงาน</div>
      <div class="page-card-body">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <option value="pending"      <?php echo $filters['status']==='pending'      ?'selected':''; ?>>รอตรวจสอบ</option>
              <option value="sent_central" <?php echo $filters['status']==='sent_central' ?'selected':''; ?>>ส่งส่วนกลาง</option>
              <option value="in_progress"  <?php echo $filters['status']==='in_progress'  ?'selected':''; ?>>กำลังดำเนินการ</option>
              <option value="completed"    <?php echo $filters['status']==='completed'    ?'selected':''; ?>>สำเร็จ</option>
            </select>
          </div>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">ประเภทปัญหา</label>
            <select name="issue_type" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <?php foreach (getIssueTypeOptions() as $k => $v): ?>
              <option value="<?php echo $k; ?>" <?php echo $filters['issue_type']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-sm-4 col-md-2">
            <label class="form-label">โปรแกรม</label>
            <select name="program_name" class="form-select form-select-sm filter-input">
              <option value="">ทั้งหมด</option>
              <?php foreach (getProgramOptions() as $k => $v): ?>
              <option value="<?php echo $k; ?>" <?php echo $filters['program_name']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if (Auth::hasRole('admin')): ?>
          <div class="col-12 col-sm-8 col-md-3">
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
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetIssueReportFilter()">
              <i class="fas fa-undo me-1"></i>รีเซ็ต
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="issueReportListContainer">
    <?php include 'views/issue_reports/_list_partial.php'; ?>
  </div>

</main>

<?php
$hasOffice = Auth::hasRole('admin') ? 'true' : 'false';
$extraJs = '<script>
var issueReportLoading = false;
var issueReportHasOfficeFilter = ' . $hasOffice . ';

function loadIssueReportList(page) {
  if (issueReportLoading) return;
  issueReportLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:          "issue_reports",
    action:        "ajax_list",
    status:        form.status.value,
    issue_type:    form.issue_type.value,
    program_name:  form.program_name.value,
    office_name:   issueReportHasOfficeFilter && form.office_name ? form.office_name.value : "",
    date_from:     form.date_from.value,
    date_to:       form.date_to.value,
    p: page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("issueReportListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    issueReportLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
      return;
    }
    container.innerHTML = text;
    bindIssueReportListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetIssueReportFilter() {
  var form = document.getElementById("filterForm");
  form.status.value = "";
  form.issue_type.value = "";
  form.program_name.value = "";
  if (form.office_name) form.office_name.value = "";
  form.date_from.value = "";
  form.date_to.value = "";
  loadIssueReportList(1);
}

function bindIssueReportListEvents() {
  var pagLinks = document.querySelectorAll("#issueReportListContainer .pagination a.page-link");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadIssueReportList(page);
    });
  }
}

function exportIssueReportCsv() {
  var rows = document.querySelectorAll("#issueReportTable tr"), csv = [];
  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].querySelectorAll("th,td"), row = [];
    for (var j = 0; j < cells.length; j++) row.push(cells[j].innerText.replace(/,/g, " "));
    csv.push(row.join(","));
  }
  var blob = new Blob(["\uFEFF" + csv.join("\n")], {type:"text/csv;charset=utf-8;"});
  var a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = "report_issue_" + new Date().toISOString().slice(0, 10) + ".csv";
  a.click();
}

// Auto-search: select และ date ยิงทันทีตอน change
var issueReportAutoFields = document.querySelectorAll("#filterForm select.filter-input, #filterForm input[type=date].filter-input");
for (var i = 0; i < issueReportAutoFields.length; i++) {
  issueReportAutoFields[i].addEventListener("change", function() { loadIssueReportList(1); });
}

bindIssueReportListEvents();
</script>';
?>
