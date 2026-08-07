<?php $officeOptions = getOfficeOptions(); ?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">รายงานเอกสาร</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-sky-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-chart-bar"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">รายงานเอกสาร</div>
      <div class="text-sm text-slate-600">สรุปสถิติการนำส่งเอกสารทั้งหมด</div>
    </div>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="reports">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-filter mr-2 text-slate-500"></i>ตัวกรองรายงาน</div>
      <div class="p-3.5">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 items-end">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">ปีบัญชี</label>
            <select name="fiscal_year" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              <option value="">ทั้งหมด</option>
              <?php for ($y = thaiYear(); $y >= thaiYear() - 5; $y--): ?>
              <option value="<?php echo $y; ?>" <?php echo $filters['fiscal_year'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ</label>
            <select name="status" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
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
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetReportFilter()">
              <i class="fas fa-undo mr-1"></i>รีเซ็ต
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
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
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
  var pagLinks = document.querySelectorAll("#reportListContainer nav a");
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
