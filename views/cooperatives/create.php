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
      <li class="text-slate-700 font-medium">เพิ่มสหกรณ์</li>
    </ol>
  </nav>
</div>

<main class="p-3 md:p-5 pb-6 md:pb-8 max-w-full overflow-x-hidden">

  <div class="rounded-lg border border-blue-200 px-4 md:px-5 py-3.5 flex items-center gap-3.5 flex-wrap mb-4"
       style="background: linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);">
    <div class="w-11 h-11 rounded-[10px] bg-green-700 text-white flex items-center justify-center text-xl flex-shrink-0">
      <i class="fas fa-plus"></i>
    </div>
    <div>
      <div class="text-base font-bold text-[#1a237e]">เพิ่มสหกรณ์ใหม่</div>
      <div class="text-sm text-slate-600">กรอกข้อมูลให้ครบถ้วนแล้วกดบันทึก</div>
    </div>
  </div>

  <form method="POST" action="?page=cooperatives&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo Session::getCsrf(); ?>">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
      <div class="lg:col-span-8">

        <div class="bg-white border border-slate-200 rounded-md mb-3">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-building mr-2 text-[#1565c0]"></i>ข้อมูลพื้นฐาน</div>
          <div class="p-3.5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัสสหกรณ์ <span class="text-red-600">*</span></label>
                <input type="text" name="code" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required placeholder="เช่น KKN001">
              </div>
              <div class="md:col-span-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ชื่อสหกรณ์ <span class="text-red-600">*</span></label>
                <input type="text" name="name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required placeholder="ชื่อเต็มสหกรณ์">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">เลขทะเบียนสหกรณ์</label>
                <input type="text" name="registration_no" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" placeholder="เช่น 0001/2530">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">รหัส 13 หลัก</label>
                <input type="text" name="regis_13digit" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" maxlength="13" placeholder="13 หลัก">
              </div>
              <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">วันที่จดทะเบียน</label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="text" name="register_date" id="registerDateInput"
                         class="flex-1 min-w-0 text-sm px-2 py-1.5 outline-none" placeholder="วว/ดด/ปปปป เช่น 31/03/2569" maxlength="10" value="">
                  <span id="registerDatePreview" class="bg-slate-50 border-l border-slate-300 px-2 flex items-center text-xs text-slate-500 min-w-[100px]">-</span>
                </div>
              </div>
              <div class="md:col-span-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ประเภทสหกรณ์ <span class="text-red-600">*</span></label>
                <select name="type_name" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <option value="">— เลือกประเภท —</option>
                  <?php foreach ($cooperativeTypes as $t): ?>
                  <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">วันสิ้นปีบัญชี</label>
                <div class="flex rounded-md border border-slate-300 overflow-hidden">
                  <input type="text" name="fiscal_year" id="fiscalYearInput"
                         class="flex-1 min-w-0 text-sm px-2 py-1.5 outline-none" placeholder="วว/ดด เช่น 31/03" maxlength="5" value="">
                  <span id="fiscalPreview" class="bg-slate-50 border-l border-slate-300 px-2 flex items-center text-xs text-slate-500 min-w-[90px]">-</span>
                </div>
              </div>
              <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">สถานะ <span class="text-red-600">*</span></label>
                <select name="status" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" required>
                  <?php foreach ($statusOptions as $k => $v): ?>
                  <option value="<?php echo $k; ?>" <?php echo $k==='active' ? 'selected' : ''; ?>><?php echo $v; ?></option>
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
                <textarea name="address" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5" rows="2" placeholder="บ้านเลขที่ ถนน หมู่บ้าน"></textarea>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">ตำบล</label>
                <input type="text" name="subdistrict" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">อำเภอ</label>
                <input type="text" name="district" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">จังหวัด</label>
                <select name="province" class="w-full text-sm border border-slate-300 rounded-md px-2 py-1.5">
                  <option value="">— เลือกจังหวัด —</option>
                  <?php foreach ($provinces as $p): ?>
                  <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
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
              <option value="">— เลือกสำนักงาน —</option>
              <?php foreach ($officeOptions as $off): ?>
              <option value="<?php echo e($off); ?>"><?php echo e($off); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="<?php echo uiBtnClasses('success'); ?>"><i class="fas fa-save mr-1"></i>บันทึกสหกรณ์</button>
          <a href="?page=cooperatives" class="<?php echo uiBtnClasses('outline'); ?>">ยกเลิก</a>
        </div>

      </div>

      <!-- ขวา: คำแนะนำ -->
      <div class="lg:col-span-4">
        <div class="bg-white border border-slate-200 rounded-md">
          <div class="bg-slate-50 border-b border-slate-200 px-3.5 py-2.5 font-semibold text-sm"><i class="fas fa-info-circle mr-2 text-sky-600"></i>คำแนะนำ</div>
          <div class="p-0">
            <?php
            $tips = array(
                array('icon'=>'fas fa-code text-blue-600',          'text'=>'<strong>รหัสสหกรณ์</strong> ต้องไม่ซ้ำ ใช้รูปแบบ จ.ลำดับ เช่น KKN001'),
                array('icon'=>'fas fa-building text-amber-500',     'text'=>'<strong>ประเภทสหกรณ์</strong> ต้องเลือกให้ตรงกับทะเบียนจดตั้ง'),
                array('icon'=>'fas fa-landmark text-sky-600',       'text'=>'<strong>สำนักงาน</strong> กำหนดขอบเขตว่าใครจะเห็นและส่งเอกสารให้สหกรณ์นี้ได้'),
                array('icon'=>'fas fa-toggle-on text-green-600',    'text'=>'<strong>สถานะ "ปกติ"</strong> เท่านั้นที่จะปรากฏในรายการนำส่งเอกสาร'),
                array('icon'=>'fas fa-map-marker-alt text-red-600', 'text'=>'ข้อมูลที่ตั้งช่วยในการอ้างอิงและรายงาน ไม่บังคับกรอก'),
            );
            foreach ($tips as $tip):
            ?>
            <div class="flex items-start gap-2 px-3.5 py-2.5 border-b border-slate-100 last:border-b-0">
              <i class="<?php echo $tip['icon']; ?> flex-shrink-0 mt-1"></i>
              <span class="text-[0.82rem] text-slate-600"><?php echo $tip['text']; ?></span>
            </div>
            <?php endforeach; ?>
            <div class="px-3.5 py-2.5">
              <div class="border border-slate-200 bg-slate-50 text-slate-600 rounded px-3 py-2 text-sm">
                <i class="fas fa-asterisk text-red-600 mr-1"></i>
                ช่องที่มีเครื่องหมาย <strong class="text-red-600">*</strong> จำเป็นต้องกรอก
              </div>
            </div>
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
