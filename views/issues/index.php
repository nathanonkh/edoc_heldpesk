<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">แจ้งปัญหาการใช้งาน</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-red-600 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">แจ้งปัญหาการใช้งานโปรแกรม</div>
      <div class="text-sm text-slate-600">ทั้งหมด <span id="issueTotalCount"><?php echo $totalItems; ?></span> รายการ</div>
    </div>
    <a href="?page=issues&action=create" class="<?php echo uiBtnClasses('danger'); ?> flex-shrink-0">
      <i class="fas fa-plus mr-1"></i>แจ้งปัญหาใหม่
    </a>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="issues">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-filter mr-2 text-slate-500"></i>ตัวกรอง</div>
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
          <div class="col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-1">ค้นหา</label>
            <input type="text" name="keyword" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5"
                   placeholder="ชื่อเรื่อง / ชื่อสหกรณ์ / เลขที่แจ้ง"
                   value="<?php echo e($filters['keyword']); ?>">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่เริ่มต้น</label>
            <input type="date" name="date_from" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($filters['date_from']); ?>">
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่สิ้นสุด</label>
            <input type="date" name="date_to" class="filter-input w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($filters['date_to']); ?>">
          </div>
          <div>
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetIssueFilter()">
              <i class="fas fa-undo mr-1"></i>รีเซ็ต
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="issueListContainer">
    <?php include 'views/issues/_list_partial.php'; ?>
  </div>

</main>

<?php
$extraJs = '<script>
var issueLoading = false;

function loadIssueList(page) {
  if (issueLoading) return;
  issueLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:      "issues",
    action:    "ajax_list",
    status:    form.status.value,
    keyword:   form.keyword.value,
    date_from: form.date_from.value,
    date_to:   form.date_to.value,
    p:         page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("issueListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    issueLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
      return;
    }
    container.innerHTML = text;
    bindIssueListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetIssueFilter() {
  var form = document.getElementById("filterForm");
  form.status.value = "";
  form.keyword.value = "";
  form.date_from.value = "";
  form.date_to.value = "";
  loadIssueList(1);
}

function bindIssueListEvents() {
  var pagLinks = document.querySelectorAll("#issueListContainer nav a");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadIssueList(page);
    });
  }
}

document.getElementById("filterForm").addEventListener("keypress", function(e) {
  if (e.key === "Enter" || e.keyCode === 13) e.preventDefault();
});

var autoFieldsIssue = document.querySelectorAll("#filterForm select.filter-input, #filterForm input[type=date].filter-input");
for (var i = 0; i < autoFieldsIssue.length; i++) {
  autoFieldsIssue[i].addEventListener("change", function() { loadIssueList(1); });
}

var issueKeywordTimer;
var issueKeywordInput = document.querySelector("#filterForm input[name=keyword]");
if (issueKeywordInput) {
  issueKeywordInput.addEventListener("input", function() {
    clearTimeout(issueKeywordTimer);
    issueKeywordTimer = setTimeout(function() { loadIssueList(1); }, 600);
  });
}

bindIssueListEvents();
</script>';
?>
