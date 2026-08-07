<?php
$cooperativeTypes = getCooperativeTypeOptions();
$officeOptions    = getOfficeOptions();
$statusOptions    = getCooperativeStatusOptions();
$provinces        = getProvinceOptions();
?>

<div class="bg-white border-b border-slate-200 px-4 py-1.5 text-sm">
  <nav aria-label="breadcrumb">
    <ol class="flex items-center gap-1.5 text-slate-500">
      <li><a class="hover:text-[#1565c0]" href="?page=dashboard"><i class="fas fa-home"></i></a></li>
      <li class="text-slate-300">/</li>
      <li><a class="hover:text-[#1565c0]" href="?page=cooperatives">จัดการสหกรณ์</a></li>
      <li class="text-slate-300">/</li>
      <li class="text-slate-700 font-medium">แก้ไขสหกรณ์</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-amber-500 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-edit"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">แก้ไขข้อมูลสหกรณ์</div>
      <div class="text-sm text-slate-600"><?php echo e($coop['name']); ?></div>
    </div>
  </div>

  <form method="POST" action="?page=cooperatives&action=update">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $coop['id']; ?>">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
      <div class="lg:col-span-8">

        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-building mr-2 text-[#1565c0]"></i>ข้อมูลพื้นฐาน</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสสหกรณ์ <span class="text-red-600">*</span></label>
                <input type="text" name="code" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required
                       value="<?php echo e($coop['code']); ?>" placeholder="เช่น KKN001">
              </div>
              <div class="md:col-span-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อสหกรณ์ <span class="text-red-600">*</span></label>
                <input type="text" name="name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required value="<?php echo e($coop['name']); ?>">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">เลขทะเบียนสหกรณ์</label>
                <input type="text" name="registration_no" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($coop['registration_no']); ?>">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัส 13 หลัก</label>
                <input type="text" name="regis_13digit" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" maxlength="13" value="<?php echo e($coop['regis_13digit']); ?>">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่จดทะเบียน</label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="text" name="register_date" id="registerDateInput"
                         class="flex-1 min-w-0 text-sm px-2 py-1.5 outline-none" maxlength="10" value="<?php echo e($coop['register_date']); ?>">
                  <span id="registerDatePreview" class="bg-slate-50 border-l border-slate-300 px-2 flex items-center text-xs text-slate-500 min-w-[100px]">
                    <?php echo formatThaiDate2($coop['register_date']); ?>
                  </span>
                </div>
              </div>
              <div class="md:col-span-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="type_name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>" <?php echo $coop['type_name']===$t ? 'selected' : ''; ?>><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">วันสิ้นปีบัญชี</label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="text" name="fiscal_year" id="fiscalYearInput"
                         class="flex-1 min-w-0 text-sm px-2 py-1.5 outline-none" maxlength="5"
                         value="<?php echo e($coop['fiscal_year']); ?>">
                  <span id="fiscalPreview" class="bg-slate-50 border-l border-slate-300 px-2 flex items-center text-xs text-slate-500 min-w-[90px]">
                    <?php echo formatFiscalYear($coop['fiscal_year']); ?>
                  </span>
                </div>
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ <span class="text-red-600">*</span></label>
                <select name="status" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($statusOptions as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $coop['status']===$k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-map-marker-alt mr-2 text-red-600"></i>ที่ตั้ง</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ที่อยู่</label>
                <textarea name="address" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2"><?php echo e($coop['address']); ?></textarea>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ตำบล</label>
                <input type="text" name="subdistrict" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($coop['subdistrict']); ?>">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">อำเภอ</label>
                <input type="text" name="district" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" value="<?php echo e($coop['district']); ?>">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">จังหวัด</label>
                <select name="province" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                  <option value="">— เลือกจังหวัด —</option>
                  <?php foreach ($provinces as $p): ?>
                  <option value="<?php echo e($p); ?>" <?php echo $coop['province']===$p ? 'selected' : ''; ?>><?php echo e($p); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-landmark mr-2 text-amber-500"></i>สำนักงานที่รับผิดชอบ</div>
          <div class="p-3.5">
            <label class="block text-sm font-semibold text-slate-700 mb-1">สำนักงานตรวจบัญชีสหกรณ์ <span class="text-red-600">*</span></label>
            <select name="office_name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
              <?php foreach ($officeOptions as $off): ?>
              <option value="<?php echo e($off); ?>" <?php echo $coop['office_name']===$off ? 'selected' : ''; ?>><?php echo e($off); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="<?php echo uiBtnClasses('primary'); ?>"><i class="fas fa-save mr-1"></i>บันทึกการแก้ไข</button>
          <a href="?page=cooperatives" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
        </div>

      </div>

      <!-- ขวา: ข้อมูลปัจจุบัน -->
      <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-info-circle mr-2 text-sky-600"></i>ข้อมูลปัจจุบัน</div>
          <div class="p-0">
            <table class="w-full text-[0.82rem]">
              <tbody>
                <tr><td class="text-slate-500 pl-3.5 py-1.5 w-[45%] whitespace-nowrap">รหัส</td><td class="py-1.5"><code class="tag"><?php echo e($coop['code']); ?></code></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">ชื่อ</td><td class="py-1.5"><?php echo e($coop['name']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">ประเภท</td><td class="py-1.5"><?php echo e($coop['type_name']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">จังหวัด</td><td class="py-1.5"><?php echo e($coop['province']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">สถานะ</td><td class="py-1.5"><?php echo uiBadge(getCooperativeStatusLabel($coop['status']), getCooperativeStatusBadge($coop['status'])); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">เลขทะเบียน</td><td class="py-1.5"><?php echo e($coop['registration_no']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">13 หลัก</td><td class="py-1.5 text-[0.75rem]"><?php echo e($coop['regis_13digit']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">วันจดทะเบียน</td><td class="py-1.5"><?php echo formatThaiDate2($coop['register_date']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">ที่อยู่</td><td class="py-1.5"><?php echo e($coop['address']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">ตำบล</td><td class="py-1.5"><?php echo e($coop['subdistrict']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">อำเภอ</td><td class="py-1.5"><?php echo e($coop['district']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5">สิ้นปีบัญชี</td><td class="py-1.5"><?php echo formatFiscalYear($coop['fiscal_year']); ?></td></tr>
                <tr><td class="text-slate-500 pl-3.5 py-1.5 pb-3.5">สำนักงาน</td><td class="pb-3.5 py-1.5 text-[0.78rem]"><?php echo e($coop['office_name']); ?></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </form>

</main>

<?php
$extraJs = '<script>
setupDateInput("registerDateInput", "registerDatePreview", true);
setupDateInput("fiscalYearInput", "fiscalPreview", false);
</script>';
?>
