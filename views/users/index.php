<?php
$empTypeOptions = getEmployeeTypeOptions();
$officeOptions  = getOfficeOptions();
?>

<div class="breadcrumb-bar">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="?page=dashboard"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">จัดการสมาชิก</li>
  </ol></nav>
</div>

<main class="content-area">

  <div class="page-banner mb-3">
    <div class="page-banner-icon"><i class="fas fa-users"></i></div>
    <div class="flex-grow-1">
      <div class="page-banner-title">จัดการสมาชิก</div>
      <div class="page-banner-sub">ทั้งหมด <?php echo $totalItems; ?> คน</div>
    </div>
    <a href="?page=users&action=create" class="btn btn-success btn-sm flex-shrink-0">
      <i class="fas fa-user-plus me-1"></i>เพิ่มสมาชิก
    </a>
  </div>

  <!-- Search (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="users">
    <div class="page-card mb-3">
      <div class="page-card-header">
        <span><i class="fas fa-filter me-2 text-secondary"></i>ค้นหาและกรอง</span>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAdvanced()">
          <i class="fas fa-sliders-h me-1"></i>ตัวกรองละเอียด
        </button>
      </div>
      <div class="page-card-body">
        <div class="row g-2 align-items-end mb-2">
          <div class="col-12 col-sm-8 col-md-5">
            <label class="form-label">ค้นหาทั่วไป</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="fas fa-search text-secondary"></i></span>
              <input type="text" name="keyword" id="searchInput" class="form-control"
                     placeholder="ชื่อ / ชื่อผู้ใช้ / ตำแหน่ง / สำนักงาน"
                     value="<?php echo e(isset($keyword) ? $keyword : ''); ?>">
            </div>
          </div>
          <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetUserFilter()"><i class="fas fa-times me-1"></i>ล้าง</button>
          </div>
        </div>
        <div id="advancedFilters" style="display:<?php echo (!empty($filterRole)||!empty($filterEmpType)||$filterStatus!=='')?'block':'none'; ?>;">
          <hr class="my-2">
          <div class="row g-2 align-items-end">
            <div class="col-6 col-sm-4 col-md-3">
              <label class="form-label">บทบาท</label>
              <select name="filter_role" class="form-select form-select-sm">
                <option value="">ทุกบทบาท</option>
                <?php foreach (array('submitter','inspector','approver','operator','admin') as $rk): ?>
                <option value="<?php echo $rk; ?>" <?php echo (isset($filterRole) && $filterRole===$rk) ? 'selected' : ''; ?>>
                  <?php echo getRoleLabel($rk); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6 col-sm-4 col-md-3">
              <label class="form-label">ประเภทพนักงาน</label>
              <select name="filter_emp" class="form-select form-select-sm">
                <option value="">ทุกประเภท</option>
                <?php foreach ($empTypeOptions as $ek => $ev): ?>
                <option value="<?php echo $ek; ?>" <?php echo (isset($filterEmpType) && $filterEmpType===$ek) ? 'selected' : ''; ?>>
                  <?php echo $ev; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6 col-sm-4 col-md-3">
              <label class="form-label">สำนักงาน</label>
              <select name="filter_office" class="form-select form-select-sm">
                <option value="">ทุกสำนักงาน</option>
                <?php foreach ($officeOptions as $off): ?>
                <option value="<?php echo e($off); ?>" <?php echo (isset($filterOffice) && $filterOffice===e($off)) ? 'selected' : ''; ?>>
                  <?php echo e($off); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
              <label class="form-label">สถานะ</label>
              <select name="filter_status" class="form-select form-select-sm">
                <option value="">ทุกสถานะ</option>
                <option value="1" <?php echo (isset($filterStatus) && $filterStatus==='1') ? 'selected' : ''; ?>>ใช้งาน</option>
                <option value="0" <?php echo (isset($filterStatus) && $filterStatus==='0') ? 'selected' : ''; ?>>ระงับ</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="userListContainer">
    <?php include 'views/users/_list_partial.php'; ?>
  </div>

</main>

<?php
$extraJs = '<script>
function toggleAdvanced() {
  var el = document.getElementById("advancedFilters");
  el.style.display = (el.style.display === "none") ? "block" : "none";
}

var userLoading = false;
function loadUserList(page) {
  if (userLoading) return;
  userLoading = true;

  var form   = document.getElementById("filterForm");
  var params = {
    page:          "users",
    action:        "ajax_list",
    keyword:       form.keyword.value,
    filter_role:   form.filter_role ? form.filter_role.value : "",
    filter_emp:    form.filter_emp ? form.filter_emp.value : "",
    filter_office: form.filter_office ? form.filter_office.value : "",
    filter_status: form.filter_status ? form.filter_status.value : "",
    p: page || 1
  };
  var qs = [];
  for (var k in params) qs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));

  var container = document.getElementById("userListContainer");
  container.style.opacity = "0.5";

  ajaxGet("?" + qs.join("&"), function(ok, text) {
    userLoading = false;
    container.style.opacity = "1";
    if (!ok) {
      Swal.fire({icon:"error",title:"โหลดข้อมูลไม่สำเร็จ",toast:true,position:"top-end",showConfirmButton:false,timer:2000});
      return;
    }
    container.innerHTML = text;
    bindUserListEvents();

    var urlQs = [];
    for (var k in params) if (k !== "action") urlQs.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, "", "?" + urlQs.join("&"));
    }
  });
}

function resetUserFilter() {
  var form = document.getElementById("filterForm");
  form.keyword.value = "";
  if (form.filter_role)   form.filter_role.value = "";
  if (form.filter_emp)    form.filter_emp.value = "";
  if (form.filter_office) form.filter_office.value = "";
  if (form.filter_status) form.filter_status.value = "";
  loadUserList(1);
}

function bindUserListEvents() {
  var pagLinks = document.querySelectorAll("#userListContainer .pagination a.page-link");
  for (var i = 0; i < pagLinks.length; i++) {
    pagLinks[i].addEventListener("click", function(e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      var m = href.match(/p=(\\d+)/);
      var page = m ? parseInt(m[1], 10) : 1;
      loadUserList(page);
    });
  }
}

// Real-time search (debounce)
var searchTimer;
document.getElementById("searchInput").addEventListener("input", function() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(function() { loadUserList(1); }, 600);
});

// เปลี่ยน advanced filter select แล้วค้นหาทันที
var advSelects = document.querySelectorAll("#advancedFilters select");
for (var i = 0; i < advSelects.length; i++) {
  advSelects[i].addEventListener("change", function() { loadUserList(1); });
}

bindUserListEvents();
</script>';
?>
