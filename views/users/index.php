<?php
$empTypeOptions = getEmployeeTypeOptions();
$officeOptions  = getOfficeOptions();
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">จัดการสมาชิก</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-[#1565c0] text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-users"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">จัดการสมาชิก</div>
      <div class="text-sm text-slate-600">ทั้งหมด <?php echo $totalItems; ?> คน</div>
    </div>
    <a href="?page=users&action=create" class="<?php echo uiBtnClasses('success'); ?> flex-shrink-0">
      <i class="fas fa-user-plus mr-1"></i>เพิ่มสมาชิก
    </a>
  </div>

  <!-- Search (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="users">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between">
        <span><i class="fas fa-filter mr-2 text-slate-500"></i>ค้นหาและกรอง</span>
        <button type="button" class="<?php echo uiBtnClasses('outline'); ?>" onclick="toggleAdvanced()">
          <i class="fas fa-sliders-h mr-1"></i>ตัวกรองละเอียด
        </button>
      </div>
      <div class="p-3.5">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-2 items-end mb-2">
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-700 mb-1">ค้นหาทั่วไป</label>
            <div class="flex rounded-md border border-slate-300 overflow-hidden">
              <span class="bg-slate-50 px-3 flex items-center border-r border-slate-300"><i class="fas fa-search text-slate-400"></i></span>
              <input type="text" name="keyword" id="searchInput" class="flex-1 min-w-0 px-2 py-1.5 text-sm outline-none"
                     placeholder="ชื่อ / ชื่อผู้ใช้ / ตำแหน่ง / สำนักงาน"
                     value="<?php echo e(isset($keyword) ? $keyword : ''); ?>">
            </div>
          </div>
          <div>
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetUserFilter()"><i class="fas fa-times mr-1"></i>ล้าง</button>
          </div>
        </div>
        <div id="advancedFilters" class="<?php echo (!empty($filterRole)||!empty($filterEmpType)||$filterStatus!=='')?'block':'hidden'; ?>">
          <hr class="my-2 border-slate-200">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-2 items-end">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">บทบาท</label>
              <select name="filter_role" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                <option value="">ทุกบทบาท</option>
                <?php foreach (array('submitter','inspector','approver','operator','admin') as $rk): ?>
                <option value="<?php echo $rk; ?>" <?php echo (isset($filterRole) && $filterRole===$rk) ? 'selected' : ''; ?>>
                  <?php echo getRoleLabel($rk); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทพนักงาน</label>
              <select name="filter_emp" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                <option value="">ทุกประเภท</option>
                <?php foreach ($empTypeOptions as $ek => $ev): ?>
                <option value="<?php echo $ek; ?>" <?php echo (isset($filterEmpType) && $filterEmpType===$ek) ? 'selected' : ''; ?>>
                  <?php echo $ev; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงาน</label>
              <select name="filter_office" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                <option value="">ทุกสำนักงาน</option>
                <?php foreach ($officeOptions as $off): ?>
                <option value="<?php echo e($off); ?>" <?php echo (isset($filterOffice) && $filterOffice===e($off)) ? 'selected' : ''; ?>>
                  <?php echo e($off); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ</label>
              <select name="filter_status" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
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
  el.classList.toggle("hidden");
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
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
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
  var pagLinks = document.querySelectorAll("#userListContainer nav a");
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
