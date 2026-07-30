<?php
$cooperativeTypes = getCooperativeTypeOptions();
$officeOptions    = getOfficeOptions();
$statusOptions    = getCooperativeStatusOptions();
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">จัดการสหกรณ์</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon bg-warning"><i class="fas fa-building"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">จัดการสหกรณ์</div>
      <div class="page-banner-sub">ทั้งหมด <?php echo $totalItems; ?> แห่ง</div>
    </div>
    <a href="?page=cooperatives&action=create" class="btn btn-success btn-sm flex-shrink-0">
      <i class="fas fa-plus me-1"></i>เพิ่มสหกรณ์
    </a>
  </div>

  <!-- Search (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="cooperatives">
    <div class="page-card mb-3">
      <div class="page-card-header">
        <span><i class="fas fa-filter me-2 text-secondary"></i>ค้นหาและกรอง</span>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAdvCoop()">
          <i class="fas fa-sliders-h me-1"></i>ตัวกรองละเอียด
        </button>
      </div>
      <div class="page-card-body">
        <div class="row g-2 align-items-end mb-2">
          <div class="col-12 col-sm-8 col-md-5">
            <label class="form-label">ค้นหาทั่วไป</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search text-secondary"></i></span>
              <input type="text" name="keyword" id="coopSearchInput" class="form-control"
                     placeholder="ชื่อ / รหัส / จังหวัด / เลขทะเบียน"
                     value="<?php echo e(isset($keyword) ? $keyword : ''); ?>">
            </div>
          </div>
          <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetCoopFilter()"><i class="fas fa-times me-1"></i>ล้าง</button>
          </div>
        </div>
        <div id="advCoopFilters" style="display:<?php echo (!empty($filterType)||!empty($filterOffice)||!empty($filterStatus))?'block':'none'; ?>;">
          <hr class="my-2">
          <div class="row g-2 align-items-end">
            <div class="col-6 col-sm-4 col-md-3">
              <label class="form-label">ประเภทสหกรณ์</label>
              <select name="filter_type" class="form-select form-select-sm">
                <option value="">ทุกประเภท</option>
                <?php foreach ($cooperativeTypes as $t): ?>
                <option value="<?php echo e($t); ?>" <?php echo (isset($filterType) && $filterType===e($t)) ? 'selected' : ''; ?>><?php echo e($t); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6 col-sm-4 col-md-3">
              <label class="form-label">สถานะ</label>
              <select name="filter_status" class="form-select form-select-sm">
                <option value="">ทุกสถานะ</option>
                <?php foreach ($statusOptions as $sk => $sv): ?>
                <option value="<?php echo $sk; ?>" <?php echo (isset($filterStatus) && $filterStatus===$sk) ? 'selected' : ''; ?>><?php echo $sv; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12 col-sm-4 col-md-4">
              <label class="form-label">สำนักงาน</label>
              <select name="filter_office" class="form-select form-select-sm">
                <option value="">ทุกสำนักงาน</option>
                <?php foreach ($officeOptions as $off): ?>
                <option value="<?php echo e($off); ?>" <?php echo (isset($filterOffice) && $filterOffice===e($off)) ? 'selected' : ''; ?>><?php echo e($off); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="coopListContainer">
    <?php include 'views/cooperatives/_list_partial.php'; ?>
  </div>

</main>

<?php
$extraJs = '<script>
function toggleAdvCoop() {
  var el = document.getElementById("advCoopFilters");
  el.style.display = (el.style.display === "none") ? "block" : "none";
}

var coopLoading = false;
function loadCoopList(page) {
  if (coopLoading) return;
  coopLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:          "cooperatives",
    action:        "ajax_list",
    keyword:       form.keyword.value,
    filter_type:   form.filter_type ? form.filter_type.value : "",
    filter_status: form.filter_status ? form.filter_status.value : "",
    filter_office: form.filter_office ? form.filter_office.value : "",
    p: page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("coopListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    coopLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
      return;
    }
    container.innerHTML = text;
    bindCoopListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetCoopFilter() {
  var form = document.getElementById("filterForm");
  form.keyword.value = "";
  if (form.filter_type)   form.filter_type.value = "";
  if (form.filter_status) form.filter_status.value = "";
  if (form.filter_office) form.filter_office.value = "";
  loadCoopList(1);
}

function bindCoopListEvents() {
  var pagLinks = document.querySelectorAll("#coopListContainer .pagination a.page-link");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadCoopList(page);
    });
  }
}

var coopTimer;
document.getElementById("coopSearchInput").addEventListener("input", function() {
  clearTimeout(coopTimer);
  coopTimer = setTimeout(function() { loadCoopList(1); }, 600);
});

var advCoopSelects = document.querySelectorAll("#advCoopFilters select");
for (var i = 0; i < advCoopSelects.length; i++) {
  advCoopSelects[i].addEventListener("change", function() { loadCoopList(1); });
}

bindCoopListEvents();
</script>';
?>
