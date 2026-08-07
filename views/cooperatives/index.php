<?php
$cooperativeTypes = getCooperativeTypeOptions();
$officeOptions    = getOfficeOptions();
$statusOptions    = getCooperativeStatusOptions();
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">จัดการสหกรณ์</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-amber-500 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-building"></i>
    </div>
    <div class="flex-1">
      <div class="text-base font-bold text-[#1a237e]">จัดการสหกรณ์</div>
      <div class="text-sm text-slate-600">ทั้งหมด <?php echo $totalItems; ?> แห่ง</div>
    </div>
    <a href="?page=cooperatives&action=create" class="<?php echo uiBtnClasses('success'); ?> flex-shrink-0">
      <i class="fas fa-plus mr-1"></i>เพิ่มสหกรณ์
    </a>
  </div>

  <!-- Search (AJAX) -->
  <form id="filterForm">
    <input type="hidden" name="page" value="cooperatives">
    <div class="bg-white border border-slate-200 rounded-md mb-4">
      <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm flex items-center justify-between">
        <span><i class="fas fa-filter mr-2 text-slate-500"></i>ค้นหาและกรอง</span>
        <button type="button" class="<?php echo uiBtnClasses('outline'); ?>" onclick="toggleAdvCoop()">
          <i class="fas fa-sliders-h mr-1"></i>ตัวกรองละเอียด
        </button>
      </div>
      <div class="p-3.5">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-2 items-end mb-2">
          <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-slate-700 mb-1">ค้นหาทั่วไป</label>
            <div class="flex rounded-md border border-slate-300 overflow-hidden">
              <span class="bg-slate-50 px-3 flex items-center border-r border-slate-300"><i class="fas fa-search text-slate-400"></i></span>
              <input type="text" name="keyword" id="coopSearchInput" class="flex-1 min-w-0 px-2 py-1.5 text-sm outline-none"
                     placeholder="ชื่อ / รหัส / จังหวัด / เลขทะเบียน"
                     value="<?php echo e(isset($keyword) ? $keyword : ''); ?>">
            </div>
          </div>
          <div>
            <button type="button" class="<?php echo uiBtnClasses('outline'); ?> w-full" onclick="resetCoopFilter()"><i class="fas fa-times mr-1"></i>ล้าง</button>
          </div>
        </div>
        <div id="advCoopFilters" class="<?php echo (!empty($filterType)||!empty($filterOffice)||!empty($filterStatus))?'block':'hidden'; ?>">
          <hr class="my-2 border-slate-200">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-end">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทสหกรณ์</label>
              <select name="filter_type" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                <option value="">ทุกประเภท</option>
                <?php foreach ($cooperativeTypes as $t): ?>
                <option value="<?php echo e($t); ?>" <?php echo (isset($filterType) && $filterType===e($t)) ? 'selected' : ''; ?>><?php echo e($t); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ</label>
              <select name="filter_status" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                <option value="">ทุกสถานะ</option>
                <?php foreach ($statusOptions as $sk => $sv): ?>
                <option value="<?php echo $sk; ?>" <?php echo (isset($filterStatus) && $filterStatus===$sk) ? 'selected' : ''; ?>><?php echo $sv; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงาน</label>
              <select name="filter_office" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
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
  el.classList.toggle("hidden");
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
      showToast("error", "โหลดข้อมูลไม่สำเร็จ");
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
  var pagLinks = document.querySelectorAll("#coopListContainer nav a");
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
