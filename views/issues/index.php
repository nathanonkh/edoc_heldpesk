<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">แจ้งปัญหาการใช้งาน</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-danger"><i class="fas fa-exclamation-circle"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">แจ้งปัญหาการใช้งานโปรแกรม</div>
      <div class="page-banner-sub">ทั้งหมด <span id="issueTotalCount"><?php echo $totalItems; ?></span> รายการ</div>
    </div>
    <a href="?page=issues&action=create" class="btn btn-danger btn-sm flex-shrink-0">
      <i class="fas fa-plus me-1"></i>แจ้งปัญหาใหม่
    </a>
  </div>

  <!-- Filter (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="issues">
    <div class="page-card mb-3">
      <div class="page-card-header"><i class="fas fa-filter me-2 text-secondary"></i>ตัวกรอง</div>
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
          <div class="col-12 col-sm-8 col-md-3">
            <label class="form-label">ค้นหา</label>
            <input type="text" name="keyword" class="form-control form-control-sm filter-input"
                   placeholder="ชื่อเรื่อง / ชื่อสหกรณ์ / เลขที่แจ้ง"
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
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetIssueFilter()">
              <i class="fas fa-undo me-1"></i>รีเซ็ต
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
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
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
  var pagLinks = document.querySelectorAll("#issueListContainer .pagination a.page-link");
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
