<?php $officeOptions = getOfficeOptions(); ?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">รายงานแจ้งปัญหา</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-red-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-chart-bar"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">รายงานแจ้งปัญหา</div>
      <div class="text-sm text-slate-600">สรุปสถิติการแจ้งปัญหาการใช้งานโปรแกรมทั้งหมด</div>
    </div>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="issue_reports">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-filter mr-2 text-slate-500"></i>ตัวกรองรายงาน</div>
      <div class="p-3.5">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 items-end">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ</label>
            <select name="status" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              <option value="">ทั้งหมด</option>
              <option value="pending"      <?php echo $filters['status']==='pending'      ?'selected':''; ?>>รอตรวจสอบ</option>
              <option value="sent_central" <?php echo $filters['status']==='sent_central' ?'selected':''; ?>>ส่งส่วนกลาง</option>
              <option value="in_progress"  <?php echo $filters['status']==='in_progress'  ?'selected':''; ?>>กำลังดำเนินการ</option>
              <option value="completed"    <?php echo $filters['status']==='completed'    ?'selected':''; ?>>สำเร็จ</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทปัญหา</label>
            <select name="issue_type" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              <option value="">ทั้งหมด</option>
              <?php foreach (getIssueTypeOptions() as $k => $v): ?>
              <option value="<?php echo $k; ?>" <?php echo $filters['issue_type']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">โปรแกรม</label>
            <select name="program_name" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              <option value="">ทั้งหมด</option>
              <?php foreach (getProgramOptions() as $k => $v): ?>
              <option value="<?php echo $k; ?>" <?php echo $filters['program_name']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if (Auth::hasRole('admin')): ?>
          <div class="col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงาน</label>
            <select name="office_name" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              <option value="">ทั้งหมด</option>
              <?php foreach ($officeOptions as $off): ?>
              <option value="<?php echo e($off); ?>" <?php echo $filters['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่เริ่มต้น</label>
            <input type="date" name="date_from" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($filters['date_from']); ?>">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่สิ้นสุด</label>
            <input type="date" name="date_to" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($filters['date_to']); ?>">
          </div>
          <div>
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetIssueReportFilter()">
              <i class="fas fa-undo mr-1"></i>รีเซ็ต
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
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
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
  var pagLinks = document.querySelectorAll("#issueReportListContainer nav a");
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
